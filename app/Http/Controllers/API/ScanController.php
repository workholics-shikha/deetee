<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{ErpSalesOrder, MachineMaster, MachineWiseOperation, SalesOrderProduct, SalesOrderTracking, SOProductOperationDetails};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log, Validator};

class ScanController extends Controller
{

    public function scan_machine($id)
    {
        $data = MachineMaster::select('id', 'unit_name', 'machine', 'machine_image', 'machine_type', 'machine_status')->find($id);

        if (!$data) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid machine QR code scanned.',
                'data'    => null
            ], 404);
        }

        $message = match ($data->machine_status) {
            'in-working'  => 'Machine is already assigned and currently in use.',
            'maintenance' => 'Oops! Machine is in maintenance mode.',
            'breakdown'   => 'Oops! Machine is currently not operational.',
            default       => null
        };

        if ($message) {
            return response()->json([
                'status'  => false,
                'message' => $message,
                'data'    => $data
            ], 200);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Machine is available and ready to use.',
            'data'    => $data
        ], 200);
    }

    public function scan_so(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machine_id' => 'required|integer|exists:machine_master,id',
            'so_id' => 'required|integer|exists:erp_sales_orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $machine_id = $request->input('machine_id');
        $so_id = $request->input('so_id');

        // check machice & SO industry
        $machine = MachineMaster::select('unit_name')->find($machine_id);
  
        $data = ErpSalesOrder::select('id', 'so_no', 'so_id', 'so_unitid')
            ->with(['soProducts' => function ($query) {
                $query->select('id', 'so_id', 'sub_product_id', 'item_name', 'sales_order_products.so_id');
            }])->find($so_id);

        if ($machine->unit_name != $data->industry) {
            return response()->json(['status' => false, 'message' => 'Machine and SO are not mapping', 'data' => []], 404);
        }

        if ($data) {
            return response()->json(['status' => true, 'message' => 'Sales Order found', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Sales Order not found', 'data' => []], 404);
        }
    }

    public function scan_product(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:sales_order_products,id',
            'so_id' => 'required|integer|exists:erp_sales_orders,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $product_id = $request->input('product_id');
        $so_id = $request->input('so_id');

        $erpSOID = ErpSalesOrder::select('so_id')->find($so_id);

        $data = SalesOrderProduct::select('id', 'so_id', 'drawingno', 'sub_product_id', 'item_name', 'description', 'measureunit', 'poquantity as quantity', 'operation1', 'operation2', 'operation3')->find($product_id);

        if ($erpSOID->so_id != $data->so_id) {
            return response()->json(['status' => false, 'message' => 'Product selection is wrong', 'data' => []], 404);
        }

        if (is_null($data->sub_product_id)) {
            return response()->json(['status' => false, 'message' => "Can't proceed with this product", 'data' => []], 404);
        }

        if ($data) {
            return response()->json(['status' => true, 'message' => 'Product details', 'data' => $data], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Product details', 'data' => $data], 404);
        }
    }
 
    public function operation_start_old(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_id'          => 'required|integer|exists:erp_sales_orders,id', // Added
            'machine_id'     => 'required|integer|exists:machine_master,id',
            'so_product_id'  => 'required|integer|exists:sales_order_products,id',
            'pass_id'        => 'nullable|integer',
            'operation_id'   => 'required|integer|exists:operation_masters,id',
            'current_roll_no' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }
 
            $so_id = $request->input('so_id');
            $machine_id = $request->input('machine_id');
            $so_product_id = $request->input('so_product_id');
            $pass_id = $request->input('pass_id');
            $operation_id = $request->input('operation_id');
            $current_roll = $request->input('current_roll_no');

            $userData = Auth::user()->load('roleName');
            $operator_id = $userData->id;

            // Validate SO existence
            $so_details = ErpSalesOrder::find($so_id);
            if (!$so_details) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sales order not found.'
                ], 404);
            }

            // Get SO product details
            $so_product_details = SalesOrderProduct::select('id', 'so_id', 'poquantity', 'sub_product_id', 'product_id', 'measureunit')
                ->find($so_product_id);

            if (!$so_product_details) {
                return response()->json([
                    'status' => false,
                    'message' => 'Sales order product not found.'
                ], 404);
            }

            // Check operation mapping
            $getDetails = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id
            ])->first();

            if (!$getDetails) {
                return response()->json([
                    'status' => false,
                    'message' => 'This operation detail is not mapped with SO.'
                ], 404);
            }
 
            // Check if operation is already completed/in-progress
            $existingTracking = SalesOrderTracking::where([
                'sub_product_id' => $so_product_details->sub_product_id,
                'so_product_id' => $so_product_details->product_id,
                'pass_id'       => is_numeric($pass_id) ? (int) $pass_id : null,
                'operation_id'  => $operation_id,
                'quantity_processed' => $current_roll,
                'so_pid_primary' => $so_product_id
            ])->orderBy('id', 'desc')->first();
 
            if (!empty($existingTracking)) { 
                if ($existingTracking->reason == 'completed' || $existingTracking->roll_status == 'completed') {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Operation is already completed.',
                        'data'    => null
                    ], 409); // Use 409 Conflict
                }

                if (!empty($existingTracking->start_date_time) && empty($existingTracking->end_date_time)) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Operation is already in-progress.',
                        'data'    => null
                    ], 409); // Use 409 Conflict
                }
            }  

            // Check machine availability
            $machine = MachineMaster::select('id', 'unit_name', 'machine', 'machine_image', 'machine_type', 'machine_status')
                ->find($machine_id);

            if (!$machine) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Invalid machine QR code scanned.',
                    'data'    => null
                ], 404);
            }

            $message = match ($machine->machine_status) {
                'in-working'   => 'Machine is already assigned and currently in use.',
                'maintenance'  => 'Oops! Machine is in maintenance mode.',
                'breakdown'    => 'Oops! Machine is currently not operational.',
                default        => null
            };

            if ($message) {
                return response()->json([
                    'status'  => false,
                    'message' => $message
                ], 423); // 423 Locked - Resource is locked/temporarily unavailable
            }

            // Check if operation can be performed on this machine
            $operationIds = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id
            ])->pluck('operation_id')->toArray();

            $getOperationsIds = MachineWiseOperation::where('machine_id', $machine->id)
                ->whereIn('operation_id', $operationIds)
                ->pluck('operation_id');

            if ($getOperationsIds->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This operation can not be performed.',
                ], 400);
            }

            // Prepare data for tracking
            if ($so_product_details->measureunit != 'SET') {
                $pass_id = null;
            }

        $so_product_id = $so_product_details->product_id;
        $sub_product_id = $so_product_details->sub_product_id;
        $so_id = $so_details->so_id;

        $ict = 'NA';
        
        if($so_details->industry === 'Tooling'){  
            $ict = \App\Helpers\MyHelper::getCycleTimeForTooling($operation_id, $so_id, $so_product_id, $sub_product_id, $machine_id);
        } else if($so_details->industry === 'RMR'){  
            $ict = \App\Helpers\MyHelper::getCycleTimeForRMR($operation_id, $so_id, $so_product_id, $sub_product_id, $machine_id);
        } else if($so_details->industry === 'TMR'){  
            $ict = \App\Helpers\MyHelper::getCycleTimeForTMR($operation_id, $so_id, $so_product_id, $sub_product_id, $machine_id, $pass_id);
        }
 

            $data = [
                'so_id'              => $so_product_details->so_id,
                'operator_id'        => $operator_id,
                'so_product_id'      => $so_product_details->product_id, // Use the correct product_id
                'pass_id'            => is_numeric($pass_id) ? (int) $pass_id : null,
                'machine_id'         => $machine_id,
                'operation_id'       => $operation_id,
                'total_quantity'     => $so_product_details->poquantity,
                'sub_product_id'     => $so_product_details->sub_product_id,
                'start_date_time'    => now(),
                'quantity_processed' => $current_roll,
                'ideal_cycle_time'   => $ict,
                'created_at'         => now(),
                'so_pid_primary'     => $so_product_details->id,
                'roll_status'        => 'pending'
            ];

            $tracking = SalesOrderTracking::create($data);
            $lastInsertedId = $tracking->id;

            // Update machine status
            MachineMaster::where('id', $machine_id)->update([
                'machine_status' => 'in-working',
                'operator_id' => $operator_id,
                'tracking_id' => $lastInsertedId
            ]);

            // Update roll status to in-progress (REMOVED THE print_r exit)
            $operationDetails = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id
            ])->first(['id', 'processed_qty', 'qty']);

            if ($operationDetails && $operationDetails->processed_qty) {
                $processedQty = json_decode($operationDetails->processed_qty, true);
                $updated = false;

                foreach ($processedQty as &$item) {
                    $item['pass_sheet_id'] = $item['pass_sheet_id'] ?? 0;
                    $item['quantity']      = $item['quantity'] ?? 0;
                    $item['status']        = $item['status'] ?? 'not-started';

                    $itemPassId = (int) $item['pass_sheet_id'];
                    $itemQty    = (int) $item['quantity'];

                    if ($pass_id > 0) {
                        if (
                            $item['status'] === 'not-started' &&
                            $itemPassId === (int) $pass_id &&
                            $itemQty === (int) $current_roll
                        ) {
                            $item['status']     = 'in-progress';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $updated = true;
                            break;
                        }
                    } else {
                        if ($item['status'] === 'not-started' && $itemQty === (int) $current_roll) {
                            $item['status']     = 'in-progress';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $updated = true;
                            break;
                        }
                    }
                }

                if ($updated) {
                    $operationDetails->processed_qty = json_encode($processedQty);
                    $operationDetails->save();
                }
            }

            Log::info('Operation started OK', ['tracking_id' => $lastInsertedId]);


            return response()->json([
                'status'  => true,
                'message' => 'Operation started successfully',
                'tracking_id' => $lastInsertedId
            ], 200);
 
    }
 


}
