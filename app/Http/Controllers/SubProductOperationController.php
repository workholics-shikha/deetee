<?php

namespace App\Http\Controllers;

use App\Models\{ErpSalesOrder, OperationMaster, PassSheet, SalesOrderProduct, SalesOrderTracking, SOProductOperationDetails, SubProduct, SubproductWiseOperation};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SubProductOperationController extends Controller
{

    public function route_card_details($id, $pass_no = null)
    {
        $data  = SalesOrderProduct::find($id);
        $so    = ErpSalesOrder::where('so_id', $data->so_id)->first(['so_no', 'id']);

        $getOperationList = DB::table('subproduct_wise_operation')
            ->select('subproduct_wise_operation.*', 'operation_masters.id as operation_id', 'operation_masters.parameters', 'operation_masters.unit', 'operation_masters.parameters as parameter_input', 'operation_masters.operation_qr_code', 'operation_masters.parameter1', 'operation_masters.parameter2')
            ->whereRaw("FIND_IN_SET(?, subproduct_id)", [$data->sub_product_id])
            ->leftJoin('operation_masters', 'subproduct_wise_operation.operation_id', '=', 'operation_masters.id')
            ->get();

        foreach ($getOperationList as $operation) {

            $operation->parameters_array = $operation->parameter1;

            $operation->op_table = '';

            if ($operation->unit == "TMR") {

                if ($operation->parameter1 == 'Length') {
                    $operation->op_table = 'length_with_cycle_time';
                }
                if ($operation->parameter1 == 'Material') {
                    $operation->op_table = 'material_with_cycle_time';
                }
                if ($operation->parameter1 == 'Bore') {
                    $operation->op_table = 'bore_with_cycle_time';
                }
                if ($operation->parameter1 == 'Corners') {
                    $operation->op_table = 'corners_with_cycle_time';
                }
                if ($operation->parameter1 == 'Bearing Seat Size') {
                    $operation->op_table = 'bearing_seat_with_cycle_time';
                }
                if ($operation->parameter1 == 'Keyway Width') {
                    $operation->op_table = 'width_with_cycle_time';
                }
                if ($operation->parameter1 == 'Tapping') {
                    $operation->op_table = 'tapping_with_cycle_time';
                }
                if ($operation->parameter1 == 'Thickness') {
                    $operation->op_table = 'thickness_with_cycle_time';
                }
                if ($operation->parameter1 == 'Width') {
                    $operation->op_table = 'width_with_cycle_time';
                }
            } else if ($operation->unit == "Tooling") {
            }
        }

        $subProductName    = SubProduct::where('id', $data->sub_product_id)->value('sub_product_name');

        $parameters        = OperationMaster::select('parameters')->get()->unique('parameters');

        $getOperationList  = SOProductOperationDetails::where(['sub_product_id' => $data->sub_product_id, 'sales_order_product_id' => $id])->with('operation_params')->get();

        $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code')->where('id', $pass_no)->first();

        return view('sales-order.route-card-details', compact('data', 'so', 'getOperationList', 'subProductName', 'parameters', 'pass_sheet'));
    }

    public function route_card_operation_cycletime(Request $request)
    {
        $operations = $request->get('operations');

        if (count($operations) > 0) {
            foreach ($operations as $operation) {
                if ($operation['operationType'] === "Manual") {
                    $soOpTable = SOProductOperationDetails::find($operation['id']);
                    $soOpTable->operation_type = "Manual";
                    $soOpTable->parameters = "";
                    $soOpTable->cycle_time = $operation['cycleTime'];
                    $soOpTable->save();
                }
            }

            return response()->json([
                'status'  => true,
                'message' => 'CycleTime Updated Successfully.',
            ]);
        }
    }

    public function route_card_details_new($id, $pass_no = null)
    {
        $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code')->where('id', $pass_no)->first();
        $data = SalesOrderProduct::find($id); // details of that product by id
        $so = ErpSalesOrder::where('so_id', $data->so_id)->first(['so_no', 'id', 'so_unitid']); // get industry, so no & so id
        $subProductName = SubProduct::where('id', $data->sub_product_id)->value('sub_product_name'); // sub product name
        $getOperationList = SubproductWiseOperation::where(['subproduct_id' => $data->sub_product_id, 'product_master_id' => $data->product_id])->with('operationData')->get(); // operation list for product

        return view('sales-order.route-card-details-new', compact('data', 'so', 'getOperationList', 'subProductName', 'pass_sheet'));
    }

    public function route_card_operation_details($id, Request $request)
    {
        $so = ErpSalesOrder::where('id', $id)->first(['id', 'so_no', 'so_id']);

        $pass_id = $request->pass_id;
        $operation_id = $request->operation_id; // primary id of subproduct_wise_operation
        $sprd_id = $request->sprd_id; // sales_order_products 's promary id

        $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code')->where('id', $pass_id)->first();
        $data = SalesOrderProduct::find($sprd_id); // details of SO product
        $getOperationList = SOProductOperationDetails::where(['so_id' => $so->so_id, 'sales_order_product_id' => $sprd_id, 'product_id' => $data->product_id, 'sub_product_id' => $data->sub_product_id])->first();

        $getDataFromSubPwise = SubproductWiseOperation::find($operation_id);
        $getOperationData = SalesOrderTracking::where(['operation_id' => $getDataFromSubPwise->operation_id, 'so_id' => $so->so_id, 'pass_id' => $pass_id, 'so_pid_primary' => $sprd_id])->orderBy('quantity_processed')->get();

        $subProductName = SubProduct::where('id', $getOperationList->sub_product_id)->value('sub_product_name');

        return view('sales-order.route-card-operation-details', compact('so', 'getDataFromSubPwise', 'subProductName', 'data', 'getOperationData', 'pass_sheet', 'getOperationList'));
    }

    // updated on 04-11-25 
    public function route_card_operation_cycletime_new_olddd(Request $request) // updateRouteCardOperationCycleTimeNew
    {
        $operations = $request->get('operations');

        if (empty($operations)) {
            return response()->json([
                'status' => false,
                'message' => 'No operations received.',
            ]);
        }

        $firstOp = $operations[0];

        $data = SalesOrderProduct::find($firstOp['soProductId'] ?? null);
        if (!$data) {
            return response()->json([
                'status' => false,
                'message' => 'Sales order product not found.',
            ]);
        }

        foreach ($operations as $operation) {
            // Debug if needed 
            if ($operation['operationType'] === "ManualIn" || $operation['operationType'] === "Manual_ICT") {
                if (!empty($operation['cycleTime'])) {
                    //  DB::enableQueryLog(); 
                    $soOpTable = SOProductOperationDetails::where([
                        'operation_id'    => $operation['id'],
                        'so_id'           => $data->so_id,
                        'product_id'      => $data->product_id,
                        'sub_product_id'  => $data->sub_product_id
                    ])->first();

                    if ($soOpTable) {
                        $soOpTable->cycle_time = $operation['cycleTime'];
                        $soOpTable->cycle_time_value2 = $operation['cycleTimeVal2'] ?? null;
                        $soOpTable->save();
                    }
                    //  dd(DB::getQueryLog());
                }
            }
        }

        return response()->json([
            'status'  => true,
            'message' => 'Cycle Time Updated Successfully.',
        ]);
    }

    public function route_card_operation_cycletime_new(Request $request)
    {
        $operations = $request->get('operations');

        if (empty($operations)) {
            return response()->json([
                'status' => false,
                'message' => 'No operations received.',
            ]);
        }

        $firstOp = $operations[0] ?? [];
        $salesOrderProduct = SalesOrderProduct::find($firstOp['soProductId'] ?? null);

        if (!$salesOrderProduct) {
            return response()->json([
                'status' => false,
                'message' => 'Sales order product not found.',
            ]);
        }

        DB::beginTransaction();
        try {
            foreach ($operations as $operation) {
                $operationType = $operation['operationType'] ?? null;
                $cycleTime = $operation['cycleTime'] ?? null;
                $opId = $operation['id'] ?? null;

                if (in_array($operationType, ["ManualIn", "Manual_ICT"]) && !empty($cycleTime)) {
                    $soOpTable = SOProductOperationDetails::where([
                        'operation_id' => $opId,
                        'sales_order_product_id' => $salesOrderProduct->id ,
                        'so_id' => $salesOrderProduct->so_id,
                        'product_id' => $salesOrderProduct->product_id,
                        'sub_product_id' => $salesOrderProduct->sub_product_id
                    ])->first();

                    if ($soOpTable) {
                        $soOpTable->cycle_time = $cycleTime;
                        $soOpTable->cycle_time_value2 = $operation['cycleTimeVal2'] ?? null;
                        $soOpTable->save();
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => true, 'message' => 'Cycle Time Updated Successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()]);
        }
    }

}
