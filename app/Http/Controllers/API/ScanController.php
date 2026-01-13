<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\{ErpSalesOrder, MachineMaster, SalesOrderProduct};
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
                $query->select('id', 'so_id', 'sub_product_id', 'item_name', 'sales_order_products.so_id')->where('soquantity', '!=', 0);;
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
  
}
