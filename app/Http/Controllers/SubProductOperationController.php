<?php

namespace App\Http\Controllers;

use App\Models\ErpSalesOrder;
use App\Models\PassSheet;
use App\Models\SalesOrderProduct;
use App\Models\SalesOrderTracking;
use App\Models\SOProductOperationDetails;
use App\Models\SubProduct;
use App\Models\SubproductWiseOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubProductOperationController extends Controller
{
    public function route_card_details($id, $pass_no = null)
    {
        $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code', 'size1', 'size2', 'size3')->where('id', $pass_no)->first();
        $data = SalesOrderProduct::find($id); // details of that product by id
        $so = ErpSalesOrder::where('so_id', $data->so_id)->first(['so_no', 'id', 'so_unitid']); // get industry, so no & so id
        $subProductName = SubProduct::where('id', $data->sub_product_id)->value('sub_product_name'); // sub product name
        $getOperationList = SubproductWiseOperation::with('operationData')
            ->where(['subproduct_id' => $data->sub_product_id, 'product_master_id' => $data->product_id])
            ->orderByRaw("CASE WHEN s_no IS NULL OR s_no = '' THEN 1 ELSE 0 END")
            ->orderBy('s_no')
            ->get();

        return view('sales-order.route-card-details-new', compact('data', 'so', 'getOperationList', 'subProductName', 'pass_sheet'));
    }

    public function route_card_operation_cycletime(Request $request)
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

        if (! $salesOrderProduct) {
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

                if (in_array($operationType, ['ManualIn', 'Manual_ICT']) && ! empty($cycleTime)) {
                    $soOpTable = SOProductOperationDetails::where([
                        'operation_id' => $opId,
                        'sales_order_product_id' => $salesOrderProduct->id,
                        'so_id' => $salesOrderProduct->so_id,
                        'product_id' => $salesOrderProduct->product_id,
                        'sub_product_id' => $salesOrderProduct->sub_product_id,
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

    public function lockRouteCard(Request $request)
    {
        $tbSOProductId = $request->get('tbSOProductId');

        $salesOrderProduct = SalesOrderProduct::find($tbSOProductId);

        if (! $salesOrderProduct) {
            return response()->json([
                'status' => false,
                'message' => 'Sales order product not found.',
            ]);
        }

        $salesOrderProduct->is_route_card_locked = 1;
        $salesOrderProduct->save();

        return response()->json([
            'status' => true,
            'message' => 'Route Card Locked Successfully.',
        ]);
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
}
