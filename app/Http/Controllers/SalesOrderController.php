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

        $typeOfProduct = $salesOrderProduct->measureunit; // e.g. SET / NOS
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

        $getOperationList = SubproductWiseOperation::with('operationData')
            ->where(['subproduct_id' => $data->sub_product_id, 'product_master_id' => $data->product_id])
            ->orderByRaw("CASE WHEN s_no IS NULL OR s_no = '' THEN 1 ELSE 0 END")
            ->orderBy('s_no')
            ->get();

        return view('sales-order.route-card', compact('data', 'saleOrder', 'getOperationList'));
    }

    public function buildProcessedQtyRowsNew1()
    {

        $getSOProducts = DB::select(
            "SELECT
            sopod.*,
            sop.measureunit
            FROM sales_order_product_operation_details sopod
            JOIN sales_order_products sop
            ON sop.id = sopod.sales_order_product_id
            WHERE sopod.qty1 IS NOT NULL
            AND sopod.qty1 <> 0
            AND sopod.qty1 <> sopod.qty
            AND sopod.processed_qty NOT LIKE '%in-progress%'
            AND sopod.processed_qty NOT LIKE '%partial%'
            ORDER BY sopod.qty1 ASC;

            "
        );

        print_r($getSOProducts);
        exit;


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

    public function buildProcessedQtyRowsNew()
    {
        $items = DB::select("
                SELECT
                    sopod.id AS sopod_id,
                    sopod.sales_order_product_id,
                    sopod.qty,
                    sopod.processed_qty,
                    sop.measureunit,
                    sop.soquantity,
                    sop.cpoitemid,
                    sop.so_id,
                    sop.sub_product_id
                FROM sales_order_product_operation_details sopod
                JOIN sales_order_products sop
                    ON sop.id = sopod.sales_order_product_id
                WHERE sopod.qty IS NOT NULL
                AND sopod.qty <> 0
                AND (sopod.qty1 IS NULL OR sopod.qty1 <> sopod.qty)
                ORDER BY sopod.id ASC
            ");

        foreach ($items as $row) {

            $targetQty = (int) $row->qty;
            if ($targetQty <= 0) {
                continue;
            }

            // decode existing JSON safely
            $existing = [];
            if (!empty($row->processed_qty)) {
                $decoded = json_decode($row->processed_qty, true);
                if (is_array($decoded)) {
                    $existing = $decoded;
                }
            }

            // index existing rows by quantity so we can preserve status/updated_by/etc.
            $byQty = [];
            foreach ($existing as $e) {
                if (!is_array($e) || !isset($e['quantity'])) continue;
                $q = (int) $e['quantity'];
                if ($q > 0) $byQty[$q] = $e;
            }

            $newRows = [];

            if ($row->measureunit === 'SET') {

                $passSheets = PassSheet::where([
                    'cpoitemid'     => $row->cpoitemid,
                    'so_id'         => $row->so_id,
                    'subproduct_id' => $row->sub_product_id,
                ])->get(['id', 'qty']);

                // If no pass sheets, fallback to simple 1..qty (otherwise you'll blow up jobs)
                if ($passSheets->isEmpty()) {
                    for ($i = 1; $i <= $targetQty; $i++) {
                        $old = $byQty[$i] ?? [];
                        $newRows[] = [
                            'quantity'       => $i,
                            'status'         => $old['status'] ?? 'not-started',
                            'updated_by'     => (int)($old['updated_by'] ?? 0),
                            'completed_date' => $old['completed_date'] ?? null,
                            'pass_sheet_id'  => $old['pass_sheet_id'] ?? null,
                        ];
                    }
                } else {
                    // Build sequential quantities across all pass sheets, but cap at targetQty
                    $seq = 1;
                    foreach ($passSheets as $sheet) {
                        for ($i = 1; $i <= (int)$sheet->qty; $i++) {
                            if ($seq > $targetQty) break 2;

                            $old = $byQty[$seq] ?? [];
                            $newRows[] = [
                                'pass_sheet_id'  => (int) $sheet->id,
                                'quantity'       => $seq,
                                'status'         => $old['status'] ?? 'not-started',
                                'updated_by'     => (int)($old['updated_by'] ?? 0),
                                'completed_date' => $old['completed_date'] ?? null,
                            ];
                            $seq++;
                        }
                    }

                    // If pass sheets total < targetQty, pad remaining
                    for (; $seq <= $targetQty; $seq++) {
                        $old = $byQty[$seq] ?? [];
                        $newRows[] = [
                            'pass_sheet_id'  => $old['pass_sheet_id'] ?? null,
                            'quantity'       => $seq,
                            'status'         => $old['status'] ?? 'not-started',
                            'updated_by'     => (int)($old['updated_by'] ?? 0),
                            'completed_date' => $old['completed_date'] ?? null,
                        ];
                    }
                }
            } else {
                // NOS / others: simple 1..qty
                for ($i = 1; $i <= $targetQty; $i++) {
                    $old = $byQty[$i] ?? [];
                    $newRows[] = [
                        'quantity'       => $i,
                        'status'         => $old['status'] ?? 'not-started',
                        'updated_by'     => (int)($old['updated_by'] ?? 0),
                        'completed_date' => $old['completed_date'] ?? null,
                    ];
                }
            }

            // Save updated JSON back
            DB::table('sales_order_product_operation_details')
                ->where('id', $row->sopod_id)
                ->update([
                    'processed_qty' => json_encode($newRows),
                ]);
        }

        return true;
    }

    public function syncProcessedQtyJsonByQty()
    {
        $items = DB::select("
            SELECT
                sopod.id AS sopod_id,
                sopod.qty,
                sopod.processed_qty,
                sop.measureunit,
                sop.cpoitemid,
                sop.so_id,
                sop.sub_product_id
            FROM sales_order_product_operation_details sopod
            JOIN sales_order_products sop
            ON sop.id = sopod.sales_order_product_id
            WHERE sopod.qty IS NOT NULL
            AND sopod.qty <> 0
            AND (sopod.qty1 IS NULL OR sopod.qty1 <> sopod.qty)
            ORDER BY sopod.id ASC
        ");

        $updatedIds = [];
        $skippedIds = []; // skipped because we'd delete in-progress/partial/completed beyond qty

        foreach ($items as $row) {

            $targetQty = (int) $row->qty;
            if ($targetQty <= 0) {
                continue;
            }

            // decode existing JSON safely
            $existing = [];
            if (!empty($row->processed_qty)) {
                $decoded = json_decode($row->processed_qty, true);
                if (is_array($decoded)) $existing = $decoded;
            }

            // index by quantity
            $byQty = [];
            foreach ($existing as $e) {
                if (!is_array($e) || !isset($e['quantity'])) continue;
                $q = (int) $e['quantity'];
                if ($q > 0) $byQty[$q] = $e;
            }

            $oldMaxQty = empty($byQty) ? 0 : max(array_keys($byQty));

            // block shrinking if it would delete active states
            if ($targetQty < $oldMaxQty) {
                for ($q = $targetQty + 1; $q <= $oldMaxQty; $q++) {
                    if (!isset($byQty[$q])) continue;
                    $st = strtolower((string)($byQty[$q]['status'] ?? ''));
                    if (in_array($st, ['in-progress', 'partial', 'completed'], true)) {
                        $skippedIds[] = $row->sopod_id;
                        continue 2;
                    }
                }
            }

            // build resized JSON
            $newRows = [];
            for ($q = 1; $q <= $targetQty; $q++) {
                if (isset($byQty[$q])) {
                    $rowData = $byQty[$q];
                    $rowData['quantity'] = $q;
                    $newRows[] = $rowData;
                } else {
                    $newRows[] = [
                        'quantity'       => $q,
                        'status'         => 'not-started',
                        'updated_by'     => 0,
                        'completed_date' => null,
                    ];
                }
            }

            // SET: attach pass_sheet_id only if missing
            if ($row->measureunit === 'SET') {
                $passSheets = PassSheet::where([
                    'cpoitemid'     => $row->cpoitemid,
                    'so_id'         => $row->so_id,
                    'subproduct_id' => $row->sub_product_id,
                ])->get(['id', 'qty']);

                if ($passSheets->isNotEmpty()) {
                    $map = [];
                    $seq = 1;
                    foreach ($passSheets as $sheet) {
                        for ($i = 1; $i <= (int)$sheet->qty; $i++) {
                            if ($seq > $targetQty) break 2;
                            $map[$seq] = (int)$sheet->id;
                            $seq++;
                        }
                    }
                    foreach ($newRows as &$nr) {
                        $q = (int)$nr['quantity'];
                        if (empty($nr['pass_sheet_id']) && isset($map[$q])) {
                            $nr['pass_sheet_id'] = $map[$q];
                        }
                    }
                    unset($nr);
                }
            }

            // compare old vs new to avoid fake updates
            $oldJson = json_encode(array_values($existing));
            $newJson = json_encode($newRows);

            if ($oldJson === $newJson && (int)$row->qty === (int)($row->qty1 ?? 0)) {
                // nothing really changed (qty1 not selected in query; ignore if you want)
                continue;
            }

            $affected = DB::table('sales_order_product_operation_details')
                ->where('id', $row->sopod_id)
                ->update([
                    'processed_qty' => $newJson,
                    'qty1'          => $targetQty,
                ]);

            if ($affected > 0) {
                $updatedIds[] = $row->sopod_id;
            }
        }

        return [
            'updated_count' => count($updatedIds),
            'updated_ids'   => $updatedIds,
            'skipped_count' => count($skippedIds),
            'skipped_ids'   => $skippedIds,
        ];
    }

    public function getAllSoids()
    {


        $getData = SalesOrderProduct::where(['measureunit' => 'SET'])->whereNotNull('sub_product_id')->groupBy('so_id')->pluck('so_id')->toArray();


        echo "<pre>";
        // print_r($getData); 

        $soIds = [
            12192,
            12416,
            12500,
            12625,
            12628,
            12696,
            12723,
            12795,
            12830,
            12832,
            12843,
            12865,
            12873,
            12903,
            12919,
            12925,
            12933,
            12976,
            12981,
            12985,
            12987,
            12996,
            13007,
            13011,
            13012,
            13015,
            13016,
            13046,
            13065,
            13081,
            13083,
            13092,
            13108,
            13143,
            13144,
            13147,
            13160,
            13177,
            13182,
            13189,
            13193,
            13194,
            13218,
            13219,
            13235,
            13236,
            13239,
            13248,
            13249,
            13250,
            13251,
            13267,
            13268,
            13269,
            13277,
            13304,
            13309,
            13312,
            13361,
            13371,
            13376,
            13385,
            13388,
            13400,
            13402,
            13406,
            13426,
            13427,
            13454,
            13505,
            13516,
            13525,
            13552
        ];

        // $rows = DB::table('sales_order_product_operation_details')
        $rows = SOProductOperationDetails::whereIn('so_id', $soIds)
            ->where(function ($q) {
                $q->where('processed_qty', 'like', '%in-progress%')
                    ->orWhere('processed_qty', 'like', '%partial%')
                    ->orWhere('processed_qty', 'like', '%pending%');
            })->groupBy('so_id')
            ->pluck('so_id')->toArray();

        echo "<pre>";
        print_r($rows);

        // $allUnique = array_values(array_unique(array_merge($getData, $rows)));

        $filtered = array_values(array_diff($getData, $rows));

        echo '<pre>';
        print_r($filtered);
        exit;


        $common = array_values(array_intersect($getData, $rows));
        //print_r($common);


        exit;

        $passSheetDetails = PassSheet::whereIn('id', function ($q) use ($getData) {
            $q->selectRaw('MAX(id)')
                ->from('pass_sheets')
                ->whereIn('so_id', $getData)
                ->groupBy('cpoitemid');
        })->get();

        $firstOperationDetailSub = DB::table('sales_order_product_operation_details as sod1')
            ->selectRaw('MIN(sod1.id) as id, sod1.so_id, sod1.sub_product_id')
            ->groupBy('sod1.so_id', 'sod1.sub_product_id');

        $passSheetDetails = PassSheet::query()
            ->whereIn('pass_sheets.id', function ($q) use ($getData) {
                $q->selectRaw('MAX(ps.id)', 'pass_sheets.id as pass_sheet_id')
                    ->from('pass_sheets as ps')
                    ->whereIn('ps.so_id', $getData)
                    ->groupBy('ps.cpoitemid');
            })
            ->leftJoinSub($firstOperationDetailSub, 'first_sod', function ($join) {
                $join->on('first_sod.so_id', '=', 'pass_sheets.so_id')
                    ->on('first_sod.sub_product_id', '=', 'pass_sheets.subproduct_id');
            })
            ->leftJoin(
                'sales_order_product_operation_details as sod',
                'sod.id',
                '=',
                'first_sod.id'
            )
            ->select(
                'pass_sheets.*',
                'sod.processed_qty',
                'sod.created_at'
            )
            ->get();
        $html = '<table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pass ID</th>
                    <th>SO ID</th>
                    <th>json</th>
                     <th>created_at</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($passSheetDetails as $details) {
            $html .= '<tr>
                <td>' . $details->id . '</td>
                <td>' . $details->pass_sheet_id . '</td>
                <td>' . $details->so_id . '</td>
                <td>' . $details->processed_qty . '</td>
                <td>' . $details->created_at . '</td>
              </tr>';
        }

        $html .= '</tbody></table>';

        echo $html;
    }

    function fixPassSheetIdsForSO()
    {
       // all ids
          $soIds = [
            12192, 12416, 12500, 12625, 12628, 12696, 12723, 12795, 12830, 12832, 12843, 12865, 12873, 12903, 12919, 12925, 12933,   12976, 12981, 12985, 12987, 12996, 13007, 13011, 13012, 13015, 13016, 13046, 13065, 13081, 13083, 13092, 13108, 13143, 13144, 13147, 13160, 13177, 13182, 13189, 13193, 13194, 13218, 13219, 13235, 13236, 13239, 13248, 13249, 13250, 13251, 13267, 13268, 13269, 13277, 13304, 13309, 13312, 13361, 13371, 13376, 13385, 13388, 13400, 13402, 13406, 13426, 13427, 13454, 13505, 13516, 13525, 13552
        ];

        // for-testing
        $soIds = [ 13177, 13219, 13235, 13250, 13304, 13309, 13312, 13361 ];

        DB::transaction(function () use ($soIds) {

            foreach ($soIds as $soId) {

                /* ----------------------------------------------------
             | 1️⃣ Get NEW pass_sheets (source of truth)
             ---------------------------------------------------- */
                $passSheets = DB::table('pass_sheets')
                    ->where('so_id', $soId)
                    ->orderBy('id', 'ASC')
                    ->pluck('id')
                    ->values()
                    ->toArray();

                if (empty($passSheets)) {
                    continue;
                }

             /* ----------------------------------------------------
             | 2️⃣ Fix processed_qty JSON
             ---------------------------------------------------- */
                $opRows = DB::table('sales_order_product_operation_details')
                    ->where('so_id', $soId)
                    ->select('id', 'processed_qty')
                    ->get();

                // we will collect mapping OLD → NEW completed IDs
                $oldCompletedIds = [];
                $newCompletedIds = [];

                foreach ($opRows as $row) {

                    $json = json_decode($row->processed_qty, true);
                    if (!is_array($json)) {
                        continue;
                    }

                    /* ---- capture OLD completed IDs BEFORE change ---- */
                    foreach ($json as $item) {
                        if (($item['status'] ?? null) === 'completed') {
                            $oldCompletedIds[] = $item['pass_sheet_id'];
                        }
                    }

                    /* ---- update JSON (index-based, as per your working logic) ---- */
                    foreach ($json as $index => &$item) {
                        if (isset($passSheets[$index])) {
                            $item['pass_sheet_id'] = $passSheets[$index];
                        }
                    }
                    unset($item);

                    /* ---- capture NEW completed IDs AFTER change ---- */
                    foreach ($json as $item) {
                        if (($item['status'] ?? null) === 'completed') {
                            $newCompletedIds[] = $item['pass_sheet_id'];
                        }
                    }

                    DB::table('sales_order_product_operation_details')
                        ->where('id', $row->id)
                        ->update([
                            'processed_qty' => json_encode($json)
                        ]);
                }

                /* ----------------------------------------------------
             | 3️⃣ Update sales_order_trackings.pass_id
             | Map OLD completed → NEW completed
             ---------------------------------------------------- */
                if (!empty($oldCompletedIds) && !empty($newCompletedIds)) {

                    DB::table('sales_order_trackings')
                        ->where('so_id', $soId)
                        ->whereIn('pass_id', $oldCompletedIds)
                        ->update([
                            'pass_id' => DB::raw("
                            CASE pass_id
                            " . collect($oldCompletedIds)
                                ->values()
                                ->map(function ($oldId, $i) use ($newCompletedIds) {
                                    return isset($newCompletedIds[$i])
                                        ? "WHEN {$oldId} THEN {$newCompletedIds[$i]}"
                                        : null;
                                })
                                ->filter()
                                ->implode(' ') . "
                            END
                        ")
                        ]);
                }
            }
        });
    }
}
