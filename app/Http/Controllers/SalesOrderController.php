<?php

namespace App\Http\Controllers;

use App\Models\{
    ErpSalesOrder,
    SalesOrderProduct,
    SOProductOperationDetails,
    SubProduct,
    PassSheet,
    ProductMasters,
    SalesOrderTracking,
    SubproductWiseOperation
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, DB};
use Barryvdh\DomPDF\Facade\Pdf;

class SalesOrderController extends Controller
{

    public function index(Request $request)
    {
        $record = ErpSalesOrder::where('so_status', '!=', 'Closed')->where('scr_status', 'Scrutinized')->orderBy('so_date', 'desc');
        $data = $record->paginate(PAGE_NO);
        return view('sales-order.index', compact('data'));
    }

    public function searchInSo(Request $request)
    {
        $search = $request->input('search');
        $getData = ErpSalesOrder::where('so_status', '!=', 'Closed')->where('scr_status', 'Scrutinized')->orderBy('so_date', 'desc');

        if ($search != '') {
            $getData->where(function ($query) use ($search) {
                $query->where('so_no', 'like', "%$search%")
                    ->orWhere('so_id', 'like', "%$search%")
                    ->orWhere('so_date', 'like', "%$search%")
                    ->orWhere('scr_status', 'like', "$search%");
            });
        }
        $data = $getData->paginate(PAGE_NO);
        return response()->json([
            'html' => view('sales-order.search-table', compact('data'))->render(),
            'pagination' => (string) $data->links(),
        ]);
    }

    public function so_details($id)
    {
        $data = ErpSalesOrder::findOrFail($id);
        $soId = $data->so_id;

        // Check and update products in single query
        $itemsExist = SalesOrderProduct::where('so_id', $soId)->exists();

        if ($itemsExist) {
            updateSubProductDetails($soId);
        } else {  
            addSubProductDetails($soId);
        }

        // Eager load all required data in single queries
        $items = SalesOrderProduct::where('so_id', $soId)
            ->with(['item', 'product'])
            ->get(); 

        $subProducts = SOProductOperationDetails::where('so_id', $soId)->get();

        return view('sales-order.details', compact('data', 'items', 'subProducts'));
    }

    public function route_card_preview(Request $request)
    {
        $data = SalesOrderProduct::find($request->id);
        $saleOrder = ErpSalesOrder::where('so_id', $data->so_id)->first();
        $getSubProductId = SalesOrderProduct::where('id', $request->id)->value('sub_product_id');
        $getOperationList = SOProductOperationDetails::where('sales_order_product_id', $request->id)->whereNotIn('operation_status', [' ', 'NA', 'Outsourced'])->get();
        return view('sales-order.route-card', compact('data', 'saleOrder', 'getOperationList'));
    }

    public function printPreview($id)
    {
        $container['data'] = SalesOrderProduct::find($id);
        $container['saleOrder'] = ErpSalesOrder::where('so_id', $container['data']->so_id)->first();
        $container['getSubProductId'] = $container['data']->sub_product_id;
        $container['getOperationList'] = SOProductOperationDetails::where('sales_order_product_id', $id)->whereNotIn('operation_status', [' ', 'NA', 'Outsourced'])->get();
        $container['title'] = 'Printable PDF';
        $pdf = Pdf::setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true])->loadView('sales-order.pdf.qr-pdf', $container);
        return $pdf->stream('document.pdf');
    }

    public function pass_sheet_preview(Request $request)
    {
        $data = SalesOrderProduct::find($request->id);
        $saleOrder = ErpSalesOrder::where('so_id', $data->so_id)->first();
        $pass_sheet = PassSheet::where('cpoitemid', $data->cpoitemid)->get();
        return view('sales-order.pass-sheet-preview', compact('data', 'saleOrder', 'pass_sheet'));
    }

    public function printPassSheet($id)
    {
        $container['data'] = SalesOrderProduct::find($id);
        $container['saleOrder'] = ErpSalesOrder::where('so_id', $container['data']->so_id)->first();
        $container['pass_sheet'] = PassSheet::where('cpoitemid', $container['data']->cpoitemid)->get();
        $container['title'] = 'Printable PDF';
        $pdf =  Pdf::setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true])->loadView('sales-order.pdf.pass-sheet-pdf', $container);
        return $pdf->stream('document.pdf');
    }

    public function fetch_sub_product(Request $request)
    {
        $data = SalesOrderProduct::find($request->id);
        $saleOrder  = ErpSalesOrder::where('so_id', $data->so_id)->first();
        $unit = $saleOrder->industry; // calls getIndustryAttribute()
        $ids = [44, 45, 46, 47, 49, 53, 56, 59, 61, 62, 64];

        if ($data->product_id != 0) {
            $subProducts = SubProduct::where('product_master_id', $data->product_id)->with('product')->get();
            if (in_array($data->product_id, $ids)) {
                $getAllIdsByGroup = ProductMasters::where(['unit' => $unit, 'group' => $saleOrder->so_group])->pluck('id')->toArray();
                $subProducts = SubProduct::whereIn('product_master_id', $getAllIdsByGroup)->with('product')->get();
            }
        } else {
            $getProductId = ProductMasters::where('erp_product', $data->item_name)->where(['unit' => $unit, 'group' => $saleOrder->so_group])->pluck('id')->toArray();
            $subProducts = SubProduct::whereIn('product_master_id', $getProductId)->with('product')->get();
        }
        $so_no = $saleOrder->so_no;
        return view('sales-order.sub-product-modal', compact('data', 'so_no', 'subProducts'));
    }

    public function pass_sheet($sop_id)
    {
        $data = SalesOrderProduct::find($sop_id);
        $so = ErpSalesOrder::where('so_id', $data->so_id)->first(['so_no', 'id', 'so_group']);
        $subProductName = SubProduct::where('id', $data->sub_product_id)->value('sub_product_name');
        $pass_sheet = PassSheet::where('cpoitemid', $data->cpoitemid)->get();
        if ($pass_sheet->isEmpty()) {
            $pass_sheet = PassSheet::where('cpoitemid', $data->cpoitemid)->get();
        }
        return view('sales-order.pass-sheet', compact('data', 'so', 'subProductName', 'pass_sheet', 'sop_id'));
    }

    public function getOperationDetails($id, $so_id)
    {
        $getRolls = SalesOrderTracking::where(['operation_id' => $id, 'so_id' => $so_id])->groupBy('operation_id');
        $getOperationData = SalesOrderTracking::where(['operation_id' => $id])->get();
        return view('sales-order.tracking', compact('getRolls', 'getOperationData'));
    }

    public function update_sub_product(Request $request)
    {
        /* -----------------------------------------------------------------
        | 1.  Validate & fetch the main Sales‑Order‑Product
        *-----------------------------------------------------------------*/
        $so_pid = $request->so_product_id;
        $salesOrderProduct = SalesOrderProduct::find($so_pid);

        if (! $salesOrderProduct) {
            return response()->json(['error' => 'Sales Order Product not found.'], 404);
        }

        // Update the chosen sub‑product on the SOP itself
        $salesOrderProduct->sub_product_id = $request->sub_product_id;
        $salesOrderProduct->save();

        $typeOfProduct = $salesOrderProduct->measureunit;      // e.g. SET / NOS
        if ($typeOfProduct === 'SET') {
            addPassSheetDetails($salesOrderProduct->cpoitemid);
        }

        $order = ErpSalesOrder::where('so_id', $salesOrderProduct->so_id)->first(['so_no', 'id']);

        if ($order) {
            $soNo = $order->so_no;
            $orderId = $order->id;
        }

        /* -----------------------------------------------------------------
     | 2.  Build once: processed‑qty JSON structure
     *-----------------------------------------------------------------*/
        $processedQtyRows = $this->buildProcessedQtyRows($typeOfProduct, $salesOrderProduct);

        /* -----------------------------------------------------------------
     | 3.  Loop through operations for the selected sub‑product
     *-----------------------------------------------------------------*/
        $operations = SubproductWiseOperation::where('subproduct_id', $request->sub_product_id)->get();

        foreach ($operations as $op) {

            $opMaster = DB::table('operation_masters')
                ->select('operation_qr_code', 'parameter_input')
                ->where('id', $op->operation_id)
                ->first();

            /* ------------ Composite key that identifies one row ---------- */
            $key = [
                'so_id' => $salesOrderProduct->so_id,
                'sales_order_product_id' => $salesOrderProduct->id,
                'operation_id' => is_numeric($op->operation_id) ? (int) $op->operation_id : null,
            ];

            /* ------------ Data needed when we first create the row -------- */
            $createData = [
                'so_no'             => $soNo,
                'product_id'        => $op->product_master_id,
                'sub_product_id'    => $op->subproduct_id,
                'operation_name'    => $op->operation_name,
                'operation_stage'   => $op->sub_operations,
                'operation_qr_code' => $opMaster->operation_qr_code ?? '',
                'operation_status' => $opMaster->parameter_input ?? '',
                'qty' => $salesOrderProduct->soquantity,        // ← set only on create
                'processed_qty' => json_encode($processedQtyRows),
            ];

            /** @var SOProductOperationDetails $detail */
            $detail = SOProductOperationDetails::firstOrCreate($key, $createData);

            /* ------------ If it already existed, update only selected cols */
            if (! $detail->wasRecentlyCreated) {
                $detail->fill([
                    // update anything *except* qty
                    'so_no'             => $soNo,
                    'product_id'        => $op->product_master_id,
                    'sub_product_id'    => $op->subproduct_id,
                    'operation_name'    => $op->operation_name,
                    'operation_stage'   => $op->sub_operations,
                    'operation_qr_code' => $opMaster->operation_qr_code ?? '',
                    'operation_status'  => $opMaster->parameter_input ?? '',
                    'processed_qty'     => json_encode($processedQtyRows),
                ])->save();
            }
        }

        /* -----------------------------------------------------------------
           | 4.  Redirect to the correct screen
        *----------------------------------------------------------------- */
        return redirect('admin/sales-order-details/' . $orderId);
    }

    /**
     * Build the processed‑qty array once.
     */
    private function buildProcessedQtyRows(string $type, SalesOrderProduct $sop): array
    {
        $rows = [];

        if ($type === 'SET') {
            $passSheets = PassSheet::where('cpoitemid', $sop->cpoitemid)->pluck('id');

            if ($passSheets->isEmpty()) {
                throw new \RuntimeException('No pass sheets found for SET product.');
            }

            foreach ($passSheets as $sheetId) {
                for ($i = 1; $i <= $sop->soquantity; $i++) {
                    $rows[] = [
                        'pass_sheet_id' => $sheetId,
                        'quantity'      => $i,
                        'status'        => 'not-started',
                        'updated_by'    => 0,
                        'completed_date' => ''
                    ];
                }
            }
        } else { // NOS or other measure‑unit
            for ($i = 1; $i <= $sop->soquantity; $i++) {
                $rows[] = [
                    'quantity'   => $i,
                    'status'     => 'not-started',
                    'updated_by' => 0,
                    'completed_date' => ''
                ];
            }
        }
        return $rows;
    }

    public function operationReview(Request $request)
    {
        try {

            // Validate the request
            $validated = $request->validate([
                'routecardid' => 'required|integer',
                'odid' => 'required|integer',
                'type' => 'required|string',
                'reviewText' => 'required|string|max:1000', // Note: using reviewText to match your form field name
            ]);

            $trackingId =  $request->odid;

            $review = SalesOrderTracking::where('id', $trackingId)->first();
            $review->roll_status = $request->type;
            $review->comment = $request->reviewText;
            $review->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Review added successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
