<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{ErpSalesOrder, MachineMaster, MachineWiseOperation, PassSheet, SalesOrderProduct, SalesOrderTracking, SOProductOperationDetails, MachineHealthMonitoring};
use Carbon\Carbon;
use DateTime; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Validator};

class SalesOrderController extends Controller
{
    public function index()
    {
        $data = ErpSalesOrder::select('id', 'so_no', 'so_cpono', 'so_date', 'so_department', 'so_region', 'so_customername', 'so_contactperson')->paginate(10);
        return response()->json([
            'status'  => true,
            'message' => 'SO list fetched succesfully',
            'data'    => $data
        ]);
    }

    public function so_details($id)
    {
        $data = ErpSalesOrder::find($id);

        return response()->json([
            'status'  => true,
            'message' => 'SO details fetched succesfully',
            'data'    => $data
        ]);
    }

    public function sales_order_summary(Request $request)
    {

        $machine_id     = $request->input('machine_id');
        $so_primary_id  = $request->input('so_id');
        $so_product_id  = $request->input('so_product_id');

        $user     = $machine = [];
        $userData = Auth::user()->load('roleName'); // eager load role relation

        if ($userData) {
            $user = [
                'id'            => $userData->id,
                'name'          => $userData->name,
                'profile_image' => $userData->profile_image,
                'department'    => $userData->department,
                'role_name'     => $userData->roleName->name ?? null, // safely access role name
                'unit'          => $userData->unit ?? null, // safely access role name
                'unit_no'       => $userData->unit_name ?? null, // safely access role name
                'username'          => $userData->username ?? null, // safely access role name
            ];
        }

        $machine = MachineMaster::select('id', 'unit_name', 'machine', 'machine_image', 'machine_type', 'machine_status')->find($machine_id);

        $so_product_details = ErpSalesOrder::select('id', 'so_no', 'so_id')
            ->with(['soProducts' => function ($query) {
                $query->select('id', 'so_id', 'sub_product_id', 'item_name', 'poquantity as quantity', 'measureunit');
            }])
            ->find($so_primary_id);

        $product_details = SalesOrderProduct::select(
            'id',
            'so_id',
            'drawingno',
            'sub_product_id',
            'item_name',
            'description',
            'measureunit',
            'poquantity as quantity',
            'operation1',

        )->find($so_product_id);

        if (!$so_product_details || !$product_details) {
            return response()->json([
                'status' => false,
                'message' => 'SO details not found'
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Product details',
            'data'    => ['user' => $user, 'machine' => $machine, 'so_details' => $so_product_details, 'product_details' => $product_details]
        ], 200);

        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized'
        ], 401);
    }

    public function sales_order_details(Request $request)
    {
        /** Expected input: so_id:86, machine_id:1, so_product_id:349 */
        $machine_id     = $request->input('machine_id');
        $so_product_id  = $request->input('so_product_id');

        // Fetch SO Product details ====
        $so_product_details = SalesOrderProduct::select(
            'id',
            'so_id',
            'drawingno',
            'sub_product_id',
            'item_name',
            'description',
            'measureunit',
            'poquantity as quantity',
            'operation1',
            'operation2',
            'operation3',
            'cpoitemid',
        )->find($so_product_id);

        if (!$so_product_details) {
            return response()->json([
                'status' => false,
                'message' => 'SO Product not found'
            ], 404);
        }

        if ($so_product_details->sub_product_id == null) {
            return response()->json([
                'status' => false,
                'message' => 'SO Sub Product not found'
            ], 404);
        }

        if ($so_product_details->measureunit === 'SET') {
            $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code')->where('cpoitemid', $so_product_details->cpoitemid)->get();
            $so_product_details->pass_sheet = $pass_sheet;
        } else {
            $so_product_details->pass_sheet = null;
        }

        // Get allowed operation IDs for the product
        $operationIds = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id])
            ->pluck('operation_id')->toArray();
        $getOperationsIds = MachineWiseOperation::where('machine_id', $machine_id)->whereIn('operation_id', $operationIds)->pluck('operation_id')->toArray();
        $getDetails = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id, 'sales_order_product_id' => $so_product_id])->whereIn('operation_id', $getOperationsIds)->get();

        $operations = [];

        if (!empty($getDetails)) {
            foreach ($getDetails as $details) {

                $completedCount = 0;

                if ($getDetails) {
                    $processedQty = json_decode($details->processed_qty, true);
                    foreach ($processedQty as $item) {
                        if (isset($item['status']) && $item['status'] === 'completed') {
                            $completedCount++;
                        }
                    }
                }

                $operations[] = [
                    'id'               => $details->id,
                    'operation_id'     => $details->operation_id,
                    'operation_name'   => $details->operation_name ?? null,
                    'completed_roll'   => $completedCount,
                    'ideal_cycle_time' => '80 Mins'
                ];
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Product details',
            'data'    => [
                'so_details' => $so_product_details,
                'operations' => $operations,
            ]
        ], 200);
    }

    public function operation_stop(Request $request)
    {
        $validator  =  Validator::make($request->all(), [
            'id' => 'required|integer|exists:sales_order_trackings,id',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $id = $request->input('id');
        $reason = $request->input('reason');

        $getData =  SalesOrderTracking::find($id);

        if (!empty($getData)) {
            if ($getData->reason == 'completed' || $getData->roll_status == 'completed') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Operation is already completed.',
                    'data'    => null
                ], 404);
            }

            if (!empty($getData->start_date_time) && !empty($getData->end_date_time)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Operation is completed.',
                    'data'    => null
                ], 404);
            }
        }

        $start = new DateTime($getData->start_date_time);
        $end   = new DateTime(); // gets current date & time

        $durationInSeconds = $end->getTimestamp() - $start->getTimestamp();

        $data = [
            'reason'        => $reason,
            'roll_status'   => 'pending',
            'end_date_time' => $end->format('Y-m-d H:i:s'),
            'time_taken'    => $durationInSeconds,
            'updated_at' => date('Y-m-d h:i:s'),
        ];

        SalesOrderTracking::where('id', $id)->update($data);

        // machine free update
        MachineMaster::where('id', $getData->machine_id)->update(['machine_status' => 'active']);

        return response()->json([
            'status'  => true,
            'message' => 'Operation stopped successfully',
        ], 200);
    }

    public function fetch_operation_details(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id'       => 'nullable|integer',
            'current_roll_no' => 'required|integer',
            'operation_id'  => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get inputs 
        $so_product_id  =  $request->input('so_product_id');
        $pass_id        =  $request->input('pass_id');
        $operation_id   =  $request->input('operation_id');
        $roll_no        =  $request->input('current_roll_no');
 
        $so_product_details = SalesOrderProduct::select('id', 'so_id', 'sub_product_id', 'product_id')->find($so_product_id);

        $mdata = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id, 'sales_order_product_id' => $so_product_id, 'product_id' => $so_product_details->product_id, 'sub_product_id' => $so_product_details->sub_product_id, 'operation_id' => $operation_id])->first();

        if (empty($mdata)) {
            return response()->json([
                'status'  => false,
                'message' => 'operation details not found',
                'data'    => ['details' => []]
            ], 404);
        }
 
        // Fetch operation tracking data
        $getData = SalesOrderTracking::where([
            'so_product_id' => $mdata->product_id,
            'sub_product_id' => $mdata->sub_product_id,
            'pass_id'       => is_numeric($pass_id) ? (int) $pass_id : null,
            'operation_id'  => $operation_id,
            'quantity_processed'  => $roll_no,
            'so_id' => $mdata->so_id,
            'so_pid_primary' => $mdata->sales_order_product_id
        ])->orderBy('id', 'desc')->get();
 
        if ($getData->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No details found',
                'data'    => ['details' => []]
            ], 404);
        }

        // Modify time_taken to formatted H M S
        foreach ($getData as $datum) {
            if (is_numeric($datum->time_taken)) {
                $seconds = (int) $datum->time_taken;

                $hours   = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $remainingSeconds = $seconds % 60;

                $datum->time_taken_formatted = "{$hours}h {$minutes}m {$remainingSeconds}s";
            } else {
                $datum->time_taken_formatted = '0h 0m 0s';
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Operation details',
            'data'    => ['details' => $getData]
        ], 200);
    }

    public function scan_process(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'operation_id'  => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $so_product_id = $request->input('so_product_id');
        $operation_id  = $request->input('operation_id');

        $mdata = SOProductOperationDetails::where([
            'operation_id' => $operation_id,
            'sales_order_product_id' => $so_product_id
        ])->first();

        if (!$mdata) {
            return response()->json([
                'status'  => false,
                'message' => 'No details found',
                'data'    => []
            ], 404);
        }

        $data['so_product_id'] = $mdata->sales_order_product_id;
        $data['so_id']         = $mdata->so_id;
        $data['operation_id']  = $mdata->operation_id;
        $data['operation_name'] = $mdata->operation_name;

        return response()->json([
            'status'  => true,
            'message' => 'QR code details',
            'data'    => $data
        ], 200);
    }

    public function scan_pass_qr(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id' => 'required|integer|exists:pass_sheets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $so_product_id = $request->input('so_product_id');
        $pass_id       = $request->input('pass_id');

        $sopdata = SalesOrderProduct::find($so_product_id);

        $mdata = PassSheet::where(['id' => $pass_id, 'cpoitemid' => $sopdata->cpoitemid])->first();

        $data  = [];

        if (!$mdata || !$sopdata) {
            return response()->json([
                'status'  => false,
                'message' => 'No details found',
                'data'    => []
            ], 404);
        }

        $data['so_product_id'] =  $sopdata->id;
        $data['so_id']         =  $sopdata->so_id;
        $data['pass_id']       =  $mdata->id;
        $data['pass_no']       = $mdata->pass_no;

        return response()->json([
            'status'  => true,
            'message' => 'QR code details',
            'data'    => $data
        ], 200);
    }

    public function update_machine_status(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'machine_id' => 'required|integer|exists:machine_master,id',
            'status'     => 'required|in:maintenance,breakdown,active',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get inputs
        $machine_id = $request->input('machine_id');
        $status     = $request->input('status');

        // Get machine
        $machine = MachineMaster::find($machine_id);

        if (!$machine) {
            return response()->json([
                'status'  => false,
                'message' => 'Machine not found',
            ], 404);
        }

        // 🚫 Check if machine is already in the same status
        if ($machine->machine_status === $status && in_array($status, ['maintenance', 'breakdown'])) {
            return response()->json([
                'status'  => false,
                'message' => "Machine is already in $status mode.",
            ], 409);
        }

        // 🚫 Check if machine is running in any operation
        $checkMachineStatus = SalesOrderTracking::where('machine_id', $machine_id)
            ->whereNull('end_date_time')
            ->whereNotNull('start_date_time')
            ->latest('id')
            ->first();

        if ($checkMachineStatus) {
            return response()->json([
                'status'  => false,
                'message' => 'Machine is working on Roll, please stop the roll first!',
            ], 409);
        }

        // ✅ Update end_time for previous maintenance/breakdown when status becomes active
        if (
            in_array($machine->machine_status, ['maintenance', 'breakdown']) &&
            $status === 'active'
        ) {
            $lastEntry = MachineHealthMonitoring::where('master_id', $machine_id)
                ->whereNull('end_date_time')
                ->latest('id')
                ->first();

            if ($lastEntry) {
                $lastEntry->end_date_time = date('Y-m-d H:i:s');
                $lastEntry->save();
            }
        }

        // ✅ Insert new monitoring entry if entering maintenance or breakdown
        if (in_array($status, ['maintenance', 'breakdown'])) {
            MachineHealthMonitoring::create([
                'master_id'       => $machine_id,
                'start_date_time' => date('Y-m-d H:i:s'),
                'monitor_for'     => $status,
            ]);
        }

        // ✅ Update machine status
        $machine->machine_status = $status;

        if ($machine->save()) {
            return response()->json([
                'status'  => true,
                'message' => "Machine put on $status mode",
            ], 200);
        } else {
            return response()->json([
                'status'  => false,
                'message' => 'Machine status not updated',
            ], 200);
        }
    }

    function OPERATIONSTOP($t_id, $reason)
    {
        $getData = SalesOrderTracking::find($t_id);

        if (!empty($getData)) {
            $start = new DateTime($getData->start_date_time);
            $end   = new DateTime(); // gets current date & time

            $durationInSeconds = $end->getTimestamp() - $start->getTimestamp();

            $data = [
                'reason'        => $reason,
                'roll_status'   => 'pending',
                'end_date_time' => $end->format('Y-m-d H:i:s'),
                'time_taken'    => $durationInSeconds,
                'updated_at' => date('Y-m-d h:i:s'),
            ];

            SalesOrderTracking::where('id', $t_id)->update($data);
        }
    }

    public function task_submit(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id'   => 'required|integer|exists:sales_order_products,id',
            'operation_id'    => 'required|integer|exists:operation_masters,id',
            'current_roll_no' => 'nullable|integer',
            'so_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Get inputs 
        $so_id = $request->input('so_id');
        $so_product_id = $request->input('so_product_id');
        $pass_id = $request->input('pass_id');
        $operation_id = $request->input('operation_id');
        $current_roll_no = $request->input('current_roll_no');

        $reason = 'completed';

        $so_product_details = SalesOrderProduct::select('id', 'so_id', 'sub_product_id', 'product_id')->find($so_product_id);

        $getDetails = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id, 'sales_order_product_id' => $so_product_id, 'product_id' => $so_product_details->product_id, 'sub_product_id' => $so_product_details->sub_product_id, 'operation_id' => $operation_id])->first();

        // Fetch operation tracking data
        $query = SalesOrderTracking::where('so_pid_primary', $so_product_id)
            ->where('operation_id', $operation_id)
            ->where('quantity_processed', $current_roll_no);

        if (is_numeric($pass_id)) {
            $query->where('pass_id', (int) $pass_id);
        } else {
            $query->whereNull('pass_id');
        }

        $getData = $query->orderBy('id', 'desc')->first();

        if (!$getData) {

            return response()->json([
                'status'  => false,
                'message' => 'No details found',
                'data'    => []
            ], 404);
        } else {

            if ($getData->roll_status === 'completed') {
                return response()->json([
                    'status'  => false,
                    'message' => 'Already completed',
                    'data'    => []
                ], 404);
            }
        }

        $start = Carbon::parse($getData->start_date_time);
        $end = Carbon::now();

        $durationInSeconds = $end->diffInSeconds($start);

        $updateData = [
            'reason'        => $reason,
            'roll_status'   => 'completed',
            'end_date_time' => $end,
            'time_taken'    => $durationInSeconds,
            'updated_at'    => Carbon::now(),
        ];

        $getData->update($updateData);
  
        if ($getDetails->qty > $getDetails->processed_qty) {
            $processed_qty  =  $getDetails->processed_qty + 1;
        } else {
            $processed_qty  =  $getDetails->processed_qty;
        }

        if ($getDetails->qty == $getDetails->processed_qty) {
            $final_status = 'completed';
        } else {
            $final_status = 'pending';
        }

        $getDetails->update([
            'processed_qty'  => $processed_qty,
            'final_status'   => $final_status,
            'process_status' => 'completed',
            'updated_at'     => Carbon::now()
        ]);

        if ($getDetails && $getDetails->processed_qty) {
            $processedQty = json_decode($getDetails->processed_qty, true);
            $updated = false;

            foreach ($processedQty as &$item) {
                $itemPassId = isset($item['pass_sheet_id']) ? (int) $item['pass_sheet_id'] : 0;
                $itemQty    = isset($item['quantity']) ? (int) $item['quantity'] : 0;
                $itemStatus = isset($item['status']) ? strtolower($item['status']) : '';

                if ($itemStatus === 'in-progress') {
                    if ($pass_id > 0) {
                        // ✅ Match both pass_sheet_id and quantity
                        if ($itemPassId === (int) $pass_id && $itemQty === (int) $current_roll_no) {
                            $item['status'] = 'completed';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $item['completed_date'] = Carbon::now()->format('Y-m-d H:i:s');
                            $updated = true;
                            break; // update only one
                        }
                    } else {
                        // ✅ Match only by quantity
                        if ($itemQty === (int) $current_roll_no) {
                            $item['status'] = 'completed';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $item['completed_date'] = Carbon::now()->format('Y-m-d H:i:s');
                            $updated = true;
                            break; // update only one
                        }
                    }
                }
            }

            if ($updated) {
                // ✅ Check if all entries are now completed
                $allCompleted = collect($processedQty)->every(function ($item) {
                    return strtolower($item['status']) === 'completed';
                });

                if ($allCompleted) {
                    $getDetails->final_status = 'completed';
                }
                $getDetails->completed_qty = $getDetails->completed_qty + 1;
                $getDetails->processed_qty = json_encode($processedQty);
                $getDetails->save();
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Operation Status Updated'
        ], 200);
    }

    public function get_processed_rolls(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id'       => 'nullable|integer',
            'operation_id'  => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors()
            ], 422);
        }

        $so_product_id = $request->so_product_id; // primary id of so products
        $pass_id       = $request->pass_id;
        $operation_id  = $request->operation_id;

        // Get one instance with required fields and processed_qty
        $operationDetail = SOProductOperationDetails::select(
            'id',
            'so_id',
            'sales_order_product_id',
            'sub_product_id',
            'operation_id',
            'operation_name',
            'processed_qty'
        )
            ->where([
                'sales_order_product_id' => $so_product_id,
                'operation_id' => $operation_id
            ])
            ->orderByDesc('id')
            ->first();

        if (!$operationDetail) {
            return response()->json([
                'status'  => false,
                'message' => 'No details found',
                'data'    => [
                    'total_roll'   => 0,
                    'details'      => [],
                    'roll_details' => []
                ]
            ], 404);
        }

        $so_product_details = SalesOrderProduct::select('measureunit')->find($so_product_id);

        // Decode processed_qty JSON
        $processedQty = json_decode($operationDetail->processed_qty, true) ?? [];

        // If pass_id is provided, return the matching roll directly
        if (!empty($pass_id) && $so_product_details->measureunit == 'SET') {

            $processedDetails = collect($processedQty)
                ->filter(fn($item) => $item['pass_sheet_id'] == $pass_id)
                ->map(function ($item) {
                    return [
                        'pass_sheet_id' => $item['pass_sheet_id'],
                        'roll_no'       => $item['quantity'] ?? null,
                        'roll_status'   => $item['status'] ?? null,
                    ];
                })->values()->toArray();
        } else {
            // Build roll details array
            $processedDetails = collect($processedQty)->map(function ($item) {
                return [
                    'roll_no'     => $item['quantity'] ?? null,
                    'roll_status' => $item['status'] ?? null,
                ];
            })->toArray();
        }

        return response()->json([
            'status'  => true,
            'message' => 'Operation details',
            'data'    => [
                'total_roll'   => count($processedDetails),
                'roll_details' => $processedDetails
            ]
        ]);
    }
 
    public function operation_start(Request $request)
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

        try {
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
                    'message' => 'No operation can be performed for this SO on this machine.',
                ], 400);
            }

            // Prepare data for tracking
            if ($so_product_details->measureunit != 'SET') {
                $pass_id = null;
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
                'ideal_cycle_time'   => 160,
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

            return response()->json([
                'status'  => true,
                'message' => 'Operation started successfully',
                'tracking_id' => $lastInsertedId
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Operation start error: ' . $e->getMessage());
            return response()->json([
                'status'  => false,
                'message' => 'Internal server error'
            ], 500);
        }
    }

}
