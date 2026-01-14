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
use Illuminate\Support\Facades\{Storage, DB, Log};
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

    public function product_list_sheet_preview(Request $request)
    {
        $saleOrder = ErpSalesOrder::where('so_id', $request->id)->first();
        $data = SalesOrderProduct::where('so_id', $request->id)->get();

        return view('sales-order.product-list-sheet', compact('data', 'saleOrder'));
    }

    public function printProductList($id)
    {
        $container['saleOrder'] = ErpSalesOrder::where('so_id', $id)->first();
        $container['data'] = SalesOrderProduct::where('so_id', $id)->get();

        $container['title'] = 'Printable PDF';
        $pdf = Pdf::setOptions(['isPhpEnabled' => true, 'isRemoteEnabled' => true])->loadView('sales-order.pdf.product-list-sheet-print', $container);
        return $pdf->stream('document.pdf');
    }

    public function printPreview($id)
    {
        $container['data'] = SalesOrderProduct::find($id);
        $container['saleOrder'] = ErpSalesOrder::where('so_id', $container['data']->so_id)->first();
        $container['getSubProductId'] = $sub_product_id = $container['data']->sub_product_id;

        // $container['getOperationList'] = SOProductOperationDetails::where('sales_order_product_id', $id)
        //                                 ->whereNotIn('operation_status', [' ', 'NA', 'Outsourced'])
        //                                  ->orderByRaw("CASE WHEN sr_no IS NULL OR sr_no = '' THEN 1 ELSE 0 END")
        //                       ->orderBy('sr_no')
        //                       ->get();

        $container['getOperationList'] = SubproductWiseOperation::with('operationData')
            ->where(['subproduct_id' => $sub_product_id, 'product_master_id' => $container['data']->product_id])
            ->orderByRaw("CASE WHEN s_no IS NULL OR s_no = '' THEN 1 ELSE 0 END")
            ->orderBy('s_no')
            ->get();

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

    public function getOperationDetails($id, $so_id)
    {
        $getRolls = SalesOrderTracking::where(['operation_id' => $id, 'so_id' => $so_id])->groupBy('operation_id');
        $getOperationData = SalesOrderTracking::where(['operation_id' => $id])->get();
        return view('sales-order.tracking', compact('getRolls', 'getOperationData'));
    }

    /**
     * Build the processed‑qty array once.
     */
    private function buildProcessedQtyRows(string $type, SalesOrderProduct $sop): array
    {
        $rows = [];

        if ($type === 'SET') {
            $passSheets = PassSheet::where([
                'cpoitemid'      => $sop->cpoitemid,
                'so_id'          => $sop->so_id,
                'subproduct_id'  => $sop->sub_product_id,
            ])->get(['id', 'qty']);

            if ($passSheets->isEmpty()) {
                throw new \RuntimeException('No pass sheets found for SET product.');
            }

            foreach ($passSheets as $sheet) {
                for ($i = 1; $i <= (int) $sheet->qty; $i++) {
                    $rows[] = [
                        'pass_sheet_id'  => $sheet->id,
                        'quantity'       => $i,        // each row represents 1 unit
                        'status'         => 'not-started',
                        'updated_by'     => 0,
                        'completed_date' => null,     // better than ''
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
                'odid'        => 'required|integer',
                'type'        => 'required|string',
                'reviewText'  => 'required|string|max:1000',
            ]);

            $trackingId = $request->odid;
            $reviewType = strtolower(trim($request->type)); // normalized type
            $reviewText = trim($request->reviewText);

            // =======================
            // 1️⃣ Update main tracking
            // =======================
            $review = SalesOrderTracking::find($trackingId);
            if (!$review) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tracking record not found.'
                ], 404);
            }

            $review->roll_status = $reviewType;
            $review->comment     = $reviewText;
            $review->save();

            // =======================
            // 2️⃣ Get related details
            // =======================
            $trackingDetails   = $review;
            $so_product_id     = $trackingDetails->so_pid_primary;
            $pass_id           = $trackingDetails->pass_id ?? 0;
            $current_roll      = (int) $trackingDetails->quantity_processed;
            $operator_id       = $trackingDetails->operator_id;

            $so_product_details = SalesOrderProduct::select('id', 'so_id', 'poquantity', 'sub_product_id', 'product_id', 'measureunit')
                ->find($so_product_id);

            if (!$so_product_details) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sales order product not found.'
                ], 404);
            }

            // =======================
            // 3️⃣ Find operation detail
            // =======================
            $operationDetails = SOProductOperationDetails::where([
                'so_id'                 => $trackingDetails->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id'            => $so_product_details->product_id,
                'sub_product_id'        => $so_product_details->sub_product_id,
                'operation_id'          => $trackingDetails->operation_id
            ])->first(['id', 'processed_qty', 'qty']);

            if (!$operationDetails || !$operationDetails->processed_qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Operation details not found or no processed data.'
                ], 404);
            }

            $processedQty = json_decode($operationDetails->processed_qty, true);
            if (!is_array($processedQty)) {
                Log::error('Invalid JSON in processed_qty', [
                    'operation_detail_id' => $operationDetails->id,
                    'value' => $operationDetails->processed_qty
                ]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid JSON data in processed_qty.'
                ], 500);
            }

            // =======================
            // 4️⃣ Update status inside JSON
            // =======================
            $updated = false;
            foreach ($processedQty as &$item) {
                $item['pass_sheet_id'] = $item['pass_sheet_id'] ?? 0;
                $item['quantity']      = $item['quantity'] ?? 0;

                $itemPassId = (int) $item['pass_sheet_id'];
                $itemQty    = (int) $item['quantity'];

                if ($reviewType === 'rework' || $reviewType === 'approved') {
                    if ($pass_id > 0) {
                        if ($itemPassId == $pass_id && $itemQty == $current_roll) {
                            $item['status']     = $reviewType;
                            $item['updated_by'] = $operator_id;
                            $updated = true;
                            break;
                        }
                    } else {
                        if ($itemQty == $current_roll) {
                            $item['status']     = $reviewType;
                            $item['updated_by'] = $operator_id;
                            $updated = true;
                            break;
                        }
                    }
                }
            }
            unset($item); // ✅ Important to release reference

            // =======================
            // 5️⃣ Save if updated
            // =======================
            if ($updated) {
                $operationDetails->processed_qty = json_encode($processedQty, JSON_UNESCAPED_UNICODE);
                $operationDetails->save();

                Log::info('Operation updated successfully', [
                    'operation_detail_id' => $operationDetails->id,
                    'updated_by' => $operator_id,
                    'review_type' => $reviewType,
                    'current_roll' => $current_roll,
                ]);
            } else {
                Log::warning('No matching item found for update', [
                    'operation_detail_id' => $operationDetails->id,
                    'pass_id' => $pass_id,
                    'current_roll' => $current_roll,
                ]);
            }

            // =======================
            // 6️⃣ Save review log
            // =======================
            DB::table('rc_review')->insert([
                'so_track_id' => $trackingId,
                'review_for'  => $reviewType,
                'review'      => $reviewText,
                'review_by'   => auth()->id() ?? 0,
                'created_at'  => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Review added and operation status updated successfully!',
                'data' => [
                    'operation_detail_id' => $operationDetails->id,
                    'updated' => $updated,
                    'review_type' => $reviewType
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in operationReview', ['error' => $e->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOperationReviewList($id)
    {
        return DB::table('rc_review')->where('so_track_id', $id)->get();
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
            ->with(['product'])
            ->get();

        $subProducts = SOProductOperationDetails::where('so_id', $soId)->get();

        return view('sales-order.details', compact('data', 'items', 'subProducts'));
    }

    public function addMissingOperation()
    {
        // DB::transaction(function () {

        $products = SalesOrderProduct::whereBetween('sub_product_id', [34, 42])
            ->whereNotNull('sub_product_id')->orderBy('id')->take(10)
            ->get();

        foreach ($products as $data) {

            // Route card operations
            $routeCardOperationIds = SubproductWiseOperation::where('subproduct_id', $data->sub_product_id)->whereNotNull('operation_id')
                ->pluck('operation_id')
                ->toArray();

            // Already added operations
            $usedOperationIds = SOProductOperationDetails::where([
                'sub_product_id' => $data->sub_product_id,
                'so_id' => $data->so_id
            ])
                ->pluck('operation_id')
                ->toArray();

            // Missing operations
            $missingOperationIds = array_diff($routeCardOperationIds, $usedOperationIds);

            if (empty($missingOperationIds)) {
                continue;
            }

            // Build processed qty ONCE
            $processedQtyRows = $this->buildProcessedQtyRows(
                $data->measureunit,
                $data
            );

            foreach ($missingOperationIds as $operationId) {

                $op = SubproductWiseOperation::where('subproduct_id', $data->sub_product_id)
                    ->where('operation_id', $operationId)
                    ->first();

                if (! $op) {
                    continue;
                }

                $opMaster = DB::table('operation_masters')
                    ->select('operation_qr_code', 'parameter_input')
                    ->where('id', $operationId)
                    ->first();

                SOProductOperationDetails::firstOrCreate(
                    [
                        'so_id'          => $data->so_id,
                        'sub_product_id' => $data->sub_product_id,
                        'operation_id'   => $operationId,
                    ],
                    [
                        'so_no'             => ErpSalesOrder::where('so_id', $data->so_id)->value('so_no') ?? '',
                        'sales_order_product_id' => $data->id,
                        'product_id'        => $op->product_master_id,
                        'operation_name'    => $op->operation_name,
                        'operation_stage'   => $op->sub_operations,
                        'operation_qr_code' => $opMaster->operation_qr_code ?? '',
                        'operation_status'  => $opMaster->parameter_input ?? '',
                        'qty'               => $data->soquantity,
                        'processed_qty'     => json_encode($processedQtyRows),
                    ]
                );
            }
        }
        // });
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
            // if not added only 
            $checkPass = PassSheet::where(['so_id' => $salesOrderProduct->so_id, 'subproduct_id' => $so_pid])->find('id');
            if (empty($checkPass)) {
                addPassSheetDetails($salesOrderProduct->cpoitemid, $salesOrderProduct->so_id);
            }
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
        * -----------------------------------------------------------------*/
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
                'qty' => $salesOrderProduct->soquantity, // ← set only on create
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

    public function pass_sheet($sop_id)
    {
        $data = SalesOrderProduct::find($sop_id);
        $so = ErpSalesOrder::where('so_id', $data->so_id)->first(['so_no', 'id', 'so_group']);
        $subProductName = SubProduct::where('id', $data->sub_product_id)->value('sub_product_name');

        $pass_sheet = PassSheet::where('cpoitemid', $data->cpoitemid)->get();

        if ($pass_sheet->isEmpty()) {
            $pass_sheet = PassSheet::where(['cpoitemid' => $data->cpoitemid, 'so_id' => $data->so_id])->get();
        }
        return view('sales-order.pass-sheet', compact('data', 'so', 'subProductName', 'pass_sheet', 'sop_id'));
    }

    public function route_card_preview(Request $request)
    {
        $data = SalesOrderProduct::find($request->id);
        $saleOrder = ErpSalesOrder::where('so_id', $data->so_id)->first();
        $getSubProductId = SalesOrderProduct::where('id', $request->id)->value('sub_product_id');

        // $getOperationList = SOProductOperationDetails::where('sales_order_product_id', $request->id)
        //                      ->whereNotIn('operation_status', [' ', 'NA', 'Outsourced'])
        //                       ->orderByRaw("CASE WHEN sr_no IS NULL OR sr_no = '' THEN 1 ELSE 0 END")
        //                       ->orderBy('sr_no')
        //                      ->get();

        $getOperationList = SubproductWiseOperation::with('operationData')
            ->where(['subproduct_id' => $data->sub_product_id, 'product_master_id' => $data->product_id])
            ->orderByRaw("CASE WHEN s_no IS NULL OR s_no = '' THEN 1 ELSE 0 END")
            ->orderBy('s_no')
            ->get();

        // echo "<pre>";  print_r($getOperationList); exit;

        return view('sales-order.route-card', compact('data', 'saleOrder', 'getOperationList'));
    }
}
