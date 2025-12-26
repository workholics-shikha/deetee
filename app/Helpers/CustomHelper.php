<?php

use App\Models\{PassSheet, SalesOrderProduct, ProductMasters, ErpSalesOrder, OperationMaster, SOProductOperationDetails, SubProduct, Notification};
use Illuminate\Support\Facades\{Http, Image, Storage, DB};
use Illuminate\Support\{Carbon, Str};
use Endroid\QrCode\Builder\Builder;

if (!function_exists('generateCustomUsername')) {
    function generateCustomUsername($name)
    {
        $name = explode(' ', trim($name))[0];
        return Str::slug($name) . rand(1000, 9999); // Example: "john-doe198845"
    }
}

if (!function_exists('generateQrCode')) {
    function generateQrCode($jsonData)
    {
        // Convert array to JSON
        $jsonString = json_encode($jsonData);

        // Generate the QR code
        $result = Builder::create()
            ->data($jsonString)
            ->size(300) // Set size in pixels
            ->margin(10) // Set margin in pixels
            ->build();

        // Return the QR code as an image response
        return response($result->getString(), 200)
            ->header('Content-Type', $result->getMimeType());
    }
}

if (!function_exists('fileUpload')) {
    function fileUpload($file, $dir)
    {
        try {
            if (!empty($file)) {
                $fileName = time() . Str::random(4) . "." . $file->getClientOriginalExtension();
                $fullPath = public_path('uploads/' . $dir . '/' . $fileName);

                // Compress image by encoding it as JPG with 75% quality
                $modifiedImage = Image::make($file)->encode('jpg', 75);

                // Ensure the directory exists, if not, create it
                if (!file_exists(public_path('uploads/' . $dir))) {
                    mkdir(public_path('uploads/' . $dir), 0777, true);
                }

                // Save the resized and compressed image
                $modifiedImage->save($fullPath);

                return $fileName;
            }
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($fileUrl)
    {
        // Do not remove user's default image
        $noUserImage = config('constants.NO_USER_IMG');
        $defaultImg  = config('constants.DEFAULT_IMAGE');

        // Check if the file is a default image
        if ($fileUrl === asset($noUserImage) || $fileUrl === asset($defaultImg)) {
            return false;
        }

        // Get the base URL of the application
        $appUrl = rtrim(config('app.url'), '/');

        // Convert the file URL to a relative path
        $relativePath = str_replace($appUrl, '', $fileUrl);

        // Remove any leading `/public` from the relative path if it exists
        $relativePath = preg_replace('/^\/?public\//', '', $relativePath);

        // Get the local filesystem path
        $localPath = public_path($relativePath);

        // Check if the file exists and delete it
        if (file_exists($localPath)) {
            unlink($localPath);
            return true;
        }

        return false;
    }
}

if (!function_exists('callErpApi')) {
    function callErpApi($url)
    {
        return $response = Http::withBasicAuth(ERP_USERNAME, ERP_PASSWORD)
            ->withHeaders([
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($url);

        echo "HTTP Status Code: " . $response->status() . "\n";
        echo "Response: " . $response;
    }
}

if (!function_exists('getIndustry')) {
    function getIndustry($id)
    {
        SalesOrderProduct::where('so_id', $id)->value('so_no');
    }
}

if (!function_exists('addSubProductDetails')) {
    function addSubProductDetails($so_id)
    {
        $product_response = callErpApi(ERP_LINK . '/OH_showSOchild/' . $so_id);
        $itemjson = $product_response->json();

        if (!empty($itemjson)) {
            foreach ($itemjson as $item) {

                $salesOrder = ErpSalesOrder::where('so_id', $so_id)->first();

                $product = ProductMasters::where('erp_product', 'LIKE', $item["item_name"])
                    ->where(['unit' => $salesOrder->industry, 'group' => $salesOrder->so_group])
                    ->first(['id', 'product_flow', 'cycle_flow']);

                $getScrutienyId = callErpApi(ERP_LINK . '/OH_showSOScrutineyWithsoid/' . $so_id);
                $getScrutienyDetails = $getScrutienyId->json();

                $scrutienyId = null;
                if (!empty($getScrutienyDetails)) {
                    foreach ($getScrutienyDetails as $scruDetails) {
                        if (
                            isset($scruDetails['cpoitemid']) &&
                            isset($item['cpoitemid']) &&
                            $scruDetails['cpoitemid'] == $item['cpoitemid']
                        ) {
                            $scrutienyId = $scruDetails['id']; // ✅ Get matching scrutiney ID
                            break;
                        }


                        $kwVal = callErpApi(ERP_LINK . '/showScrutient/' . $scrutienyId);
                        $kwSize1 = $kwVal->json();
                    }
                }

                $kw_depth = 0;

                if ($item["measureunit"] === 'SET') {
                    $kwVal = callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $item['cpoitemid']);
                    $kwSize1 = $kwVal->json();

                    $bs1_blankdia = isset($kwSize1[0]['bs1_blankdia']) && is_numeric($kwSize1[0]['bs1_blankdia'])
                        ? $kwSize1[0]['bs1_blankdia']
                        : 0;

                    $bs1_depthdia = isset($kwSize1[0]['bs1_depthdia']) && is_numeric($kwSize1[0]['bs1_depthdia'])
                        ? $kwSize1[0]['bs1_depthdia']
                        : 0;
                }

                // safely extract kw_size1 and cast to numeric (or null if not available)
                $kw_size1 = isset($kwSize1[0]['kw_size1']) && is_numeric($kwSize1[0]['kw_size1'])
                    ? $kwSize1[0]['kw_size1']
                    : null;

                $kw_depth = isset($kwSize1[0]['kw_depth']) && is_numeric($kwSize1[0]['kw_depth'])
                    ? $kwSize1[0]['kw_depth']
                    : 0;

                SalesOrderProduct::updateOrInsert(
                    [
                        'so_id' => $salesOrder->so_id,
                        "cpoitemid" => $item["cpoitemid"]
                    ],
                    [
                        "so_id"               => $salesOrder->so_id,
                        "cpoitemid"           => $item["cpoitemid"] ?? null,
                        "drawingno"           => $item["drawingno"] ?? null,
                        "item_id"             => $item["item_id"] ?? null,
                        "item_name"           => $item["item_name"] ?? null,
                        "partno"              => $item["partno"] ?? null,
                        "description"         => $item["description"] ?? null,
                        "unit"                => $item["unit"] ?? null,
                        "size1"               => $item["size1"] ?? null,
                        "size2"               => $item["size2"] ?? null,
                        "size3"               => $item["size3"] ?? null,

                        "kw_size1"            => $kw_size1 ?? null,
                        "kw_depth"            => $kw_depth ?? 0,

                        "bs1_blankdia" => $bs1_blankdia ?? 0,
                        "bs1_depthdia" => $bs1_depthdia ?? 0,

                        "material"            => $item["material"] ?? null,
                        "hardness"            => $item["hardness"] ?? null,
                        "measureunit"         => $item["measureunit"] ?? null,
                        "quantity"            => $item["quantity"] ?? 0,
                        "poquantity"          => $item["poquantity"] ?? 0,
                        "remark"              => $item["remark"] ?? null,
                        "pcs"                 => $item["pcs"] ?? 0,
                        "cpoid"               => $item["cpoid"] ?? null,
                        "operation1"          => $item["operation1"] ?? null,
                        "operation2"          => $item["operation2"] ?? null,
                        "operation3"          => $item["operation3"] ?? null,
                        "soquantity"          => $item["soquantity"] ?? 0,
                        "scr_status"          => $item["scr_status"] ?? 0,
                        "product_id"          => ($product) ? $product->id : 0,
                        "product_status" => ($product &&
                            strtolower($product->product_flow) === 'available' &&
                            strtolower($product->cycle_flow) === 'available')
                            ? 'Available'
                            : 'Not Available',

                        "created_at"          => now(),
                        "updated_at"          => now()
                    ]
                );
            }
        }

        // Generate the QR code
        $subProducts = SalesOrderProduct::where('so_id', $so_id)->get();
        if (!empty($subProducts)) {
            foreach ($subProducts as $products) {
                $result = Builder::create()
                    ->data($products->id . ';Product')
                    ->size(300) // Set size in pixels
                    ->margin(10) // Set margin in pixels
                    ->build();

                $qr_code_name = $products->cpoitemid . '-' . time() . '.png';
                $path = 'so-product-qrcodes/' . $qr_code_name; // unique filename

                Storage::disk('public')->put($path, $result->getString());
                SalesOrderProduct::where('id', $products->id)->update(['so_product_qr_code' => $qr_code_name]);
            }
        }
    }
}

if (!function_exists('updateSubProductDetails')) {
    function updateSubProductDetails($so_id)
    {
        $product_response = callErpApi(ERP_LINK . '/OH_showSOchild/' . $so_id);
        $itemjson = $product_response->json();

        if (!empty($itemjson)) {
            foreach ($itemjson as $item) {

                //=============
                $getScrutienyId = callErpApi(ERP_LINK . '/OH_showSOScrutineyWithsoid/' . $so_id);
                $getScrutienyDetails = $getScrutienyId->json();

                $scrutienyId = null;
                if (!empty($getScrutienyDetails)) foreach ($getScrutienyDetails as $scruDetails) {
                    if (
                        isset($scruDetails['cpoitemid']) &&
                        isset($item['cpoitemid']) &&
                        $scruDetails['cpoitemid'] == $item['cpoitemid']
                    ) {
                        $scrutienyId = $scruDetails['id']; // ✅ Get matching scrutiney ID
                        break;
                    }
                }

                // ============= // ============= // =============
                $kwVal = callErpApi(ERP_LINK . '/showScrutient/' . $scrutienyId);
                $kwSize1 = $kwVal->json();

                $kw_depth = $bs1_blankdia = $bs1_depthdia = 0;

                if ($item["measureunit"] === 'SET') {
                    $kwVal = callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $item['cpoitemid']);
                    $kwSize1 = $kwVal->json();

                    $kw_depth = isset($kwSize1[0]['kw_depth']) && is_numeric($kwSize1[0]['kw_depth'])
                        ? $kwSize1[0]['kw_depth']
                        : 0;

                    $bs1_blankdia = isset($kwSize1[0]['bs1_blankdia']) && is_numeric($kwSize1[0]['bs1_blankdia'])
                        ? $kwSize1[0]['bs1_blankdia']
                        : 0;

                    $bs1_depthdia = isset($kwSize1[0]['bs1_depthdia']) && is_numeric($kwSize1[0]['bs1_depthdia'])
                        ? $kwSize1[0]['bs1_depthdia']
                        : 0;
                }

                // ============= // ============= // =============
                SalesOrderProduct::where('so_id', $so_id)
                    ->where('cpoitemid', $item['cpoitemid'] ?? null)
                    ->update([
                        "size1" => $item["size1"] ?? null,
                        "size2" => $item["size2"] ?? null,
                        "size3" => $item["size3"] ?? null,

                        "kw_size1" => $kw_size1 ?? null,
                        "kw_depth" => $kw_depth ?? 0,

                        "bs1_blankdia" => $bs1_blankdia ?? 0,
                        "bs1_depthdia" => $bs1_depthdia ?? 0,

                        "kw_size1" => $item["kw_size1"] ?? null,
                        "soquantity" => $item["soquantity"] ?? 0,
                        "scr_status" => $item["scr_status"] ?? 0,
                        "updated_at" => now()
                    ]);
            }
        }
    }
}

if (!function_exists('splitPassNo')) {
    function splitPassNo($passNo)
    {
        if (strpos($passNo, '/') !== false) {
            // Case: 22DT-L/R
            if (preg_match('/^(.*?)([A-Z])\/([A-Z])$/', $passNo, $matches)) {
                return [$matches[1] . $matches[2], $matches[1] . $matches[3]];
            }

            // Case: 30,31&32TH T/B
            if (preg_match('/^(.*?)[\s]([A-Z])\/([A-Z])$/', $passNo, $matches)) {
                return [trim($matches[1]) . $matches[2], trim($matches[1]) . $matches[3]];
            }
        }

        return [$passNo]; // Return original if no split needed
    }
}

if (!function_exists('addPassSheetDetails')) {
    function addPassSheetDetails($sop_id)
    {
        $erp_response = callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $sop_id);
        $itemjson = $erp_response->json();

        if (!empty($itemjson)) {
            foreach ($itemjson as $item) {

                $passNos = splitPassNo($item['pass_no']);

                foreach ($passNos as $passNo) {

                    PassSheet::insert([
                        'so_id'         => $item['so_id'],
                        'subproduct_id' => $item['subproduct_id'],
                        'cpoitemid'     => $item['cpoitemid'],
                        'sr_no'         => $item['sr_no'],
                        'pass_no'       => $passNo,
                        'mrk_pass_no'   => $item['mrk_pass_no'],
                        'drawing_no'    => $item['drawing_no'],
                        'size1'         => $item['size1'],
                        'size2'         => $item['size2'],
                        'size3'         => $item['size3'],
                        'qty'           => $item['qty'],
                        'material'      => $item['material'],
                        'hardness'      => $item['hardness'],
                        'bs1_dia'       => $item['bs1_dia'], // for calculation
                        'bs1_depth'     => $item['bs1_depth'],  // for calculation
                        'bs1_bore'      => $item['bs1_bore'],
                        'bs2_dia'       => $item['bs2_dia'],
                        'bs2_depth'     => $item['bs2_depth'],
                        'remarks'       => $item['remarks'],
                        'revisioncount' => $item['revisioncount'],
                        'created_at'    => now(),
                        'updated_at'    => now(),
                    ]);
                }
            }
        }

        // Generate the QR code
        $passSheet = PassSheet::whereNull('pass_sheet_qr_code')->get();
        foreach ($passSheet as $sheet) {
            $result = Builder::create()
                ->data($sheet->id . ';PassScan')
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $sheet->id . '-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'so-pass-sheet-qrcodes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            PassSheet::where('id', $sheet->id)->update([ 'pass_sheet_qr_code' => $name, 'so_id' => $item['so_id'], 'subproduct_id' => $item['subproduct_id'] ]);
        }
    }
}

if (!function_exists('generateAndStoreQr')) {
    function generateAndStoreQr(array $data, string $path, $storage)
    {
        $qr = Builder::create()
            ->data(json_encode($data))
            ->size(300)
            ->margin(10)
            ->build();

        $storage->put($path, $qr->getString());
    }
}

if (!function_exists('getParamFirstLabel')) {
    function getParamFirstLabel($op_id)
    {
        if (!empty($op_id)) {
            $getLabel = OperationMaster::find($op_id);
            return $getLabel->parameter1;
        }
        return 'NA';
    }
}

if (!function_exists('getParamSecondLabel')) {
    function getParamSecondLabel($op_id)
    {
        if (!empty($op_id)) {
            $getLabel = OperationMaster::find($op_id);
            return $getLabel->parameter2;
        }
        return 'NA';
    }
}

if (!function_exists('getSubProductname')) {
    function getSubProductname($id)
    {
        return SubProduct::where('id', $id)->value('sub_product_name');
    }
}

if (!function_exists('getCycleTimeValue1')) {
    function getCycleTimeValue1($op_id, $sop_id)
    {
        $getData = SalesOrderProduct::find($sop_id);

        $cycleTime = SOProductOperationDetails::where(['so_id' => $getData->so_id, 'operation_id' => $op_id, 'product_id' => $getData->product_id, 'sub_product_id' => $getData->sub_product_id, 'sales_order_product_id' => $sop_id])->value('cycle_time');

        return $cycleTime;
    }
}

if (!function_exists('getCycleTimeValue2')) {
    function getCycleTimeValue2($op_id, $sop_id)
    {
        $getData = SalesOrderProduct::find($sop_id);
        $cycleTime = SOProductOperationDetails::where(['so_id' => $getData->so_id, 'operation_id' => $op_id, 'product_id' => $getData->product_id, 'sub_product_id' => $getData->sub_product_id, 'sales_order_product_id' => $sop_id])->value('cycle_time_value2');
        return $cycleTime;
    }
}

if (!function_exists('getSizeValue')) {
    function getSizeValue($group)
    {
        $map = [
            'K'     => ['Width', 'Thickness', 'Length'],
            'C-SB'  => ['Width', 'Thickness', 'Length'],
            'C-TCOK' => ['Width', 'Thickness', 'Length'],

            'F'     => ['OD', 'Barrel Length', 'Total Length'],
            'G'     => ['OD', 'Barrel Length', 'Total Length'],
            'H'     => ['OD', 'Barrel Length', 'Total Length'],
            'I'     => ['OD', 'Barrel Length', 'Total Length'],
            'J'     => ['OD', 'Barrel Length', 'Total Length'],

            'E'     => ['OD', 'Barrel Length/ID', 'Total Length/Thickness'],

            'B'     => ['OD', 'ID', 'Thickness'],
            'D'     => ['OD', 'ID', 'Thickness'],
            'M'     => ['OD', 'ID', 'Thickness'],
            'A'     => ['OD', 'ID', 'Thickness'],
        ];

        return $map[$group] ?? [null, null, null];
    }
}

if (!function_exists('getCompletedSOCount')) {
    function getCompletedSOCount($date, $unit = null)
    {
        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $query = SOProductOperationDetails::query()
            ->join('erp_sales_orders as eso', 'sales_order_product_operation_details.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_product_operation_details.operation_id')
            ->where('sales_order_product_operation_details.operation_name', 'NOT LIKE', '%outsource%')
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            })
            ->select('sales_order_product_operation_details.so_id')
            ->groupBy('sales_order_product_operation_details.so_id')
            ->havingRaw("
                COUNT(*) = SUM(CASE WHEN final_status = 'completed' THEN 1 ELSE 0 END)
            ")
            ->havingRaw("MAX(DATE(sales_order_product_operation_details.updated_at)) = ?", [$date])
            ->count();

        return $query;
    }
}

if (!function_exists('getCompletedQtyOverAll')) {
    function getCompletedQtyOverAll($date, $unit = null)
    {
        try {
            $targetDate = Carbon::parse($date)->startOfDay();
        } catch (Exception $e) {
            return 0;
        }

        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $operations = SOProductOperationDetails::query()
            ->select(
                'sales_order_product_operation_details.so_id',
                'sales_order_product_operation_details.sales_order_product_id',
                'sales_order_product_operation_details.product_id',
                'sales_order_product_operation_details.sub_product_id',
                'sales_order_product_operation_details.processed_qty'
            )
            ->join('erp_sales_orders as eso', 'sales_order_product_operation_details.so_id', '=', 'eso.so_id')
            ->when($unitId, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            })
            ->whereNotNull('sales_order_product_operation_details.operation_status')
            ->where('sales_order_product_operation_details.operation_status', '!=', 'Outsourced')
            ->where('sales_order_product_operation_details.operation_status', '!=', '')
            ->orderBy('sales_order_product_operation_details.so_id')
            ->orderBy('sales_order_product_operation_details.sales_order_product_id')
            ->orderBy('sales_order_product_operation_details.sub_product_id')
            ->get();

        if ($operations->isEmpty()) {
            return 0;
        }

        // Group operations by unique product/subproduct set
        $grouped = $operations->groupBy(function ($item) {
            return $item->so_id . '-' . $item->sales_order_product_id . '-' . $item->product_id . '-' . $item->sub_product_id;
        });

        $totalCompletedQty = 0;

        foreach ($grouped as $groupKey => $groupOps) {
            $groupQtySets = [];

            foreach ($groupOps as $op) {
                $decoded = json_decode($op->processed_qty, true);
                if (!is_array($decoded)) {
                    continue;
                }

                $completedInOp = [];
                foreach ($decoded as $entry) {
                    if (
                        isset($entry['status'], $entry['completed_date'], $entry['quantity']) &&
                        $entry['status'] === 'completed' &&
                        !empty($entry['completed_date'])
                    ) {
                        try {
                            $completedDate = Carbon::parse($entry['completed_date'])->startOfDay();

                            if ($completedDate->equalTo($targetDate)) {
                                $completedInOp[] = (int)$entry['quantity'];
                            }
                        } catch (Exception $e) {
                            continue;
                        }
                    }
                }

                // If no quantity was completed in this operation on the target date → intersection will become empty
                $groupQtySets[] = $completedInOp;
            }

            // Now intersect all operations' completed quantity arrays
            if (!empty($groupQtySets)) {
                $fullyCompletedQty = array_shift($groupQtySets);
                foreach ($groupQtySets as $set) {
                    $fullyCompletedQty = array_intersect($fullyCompletedQty, $set);
                    if (empty($fullyCompletedQty)) break; // no need to continue if intersection is already empty
                }

                $totalCompletedQty += count($fullyCompletedQty);
            }
        }

        return $totalCompletedQty;
    }
}

if (!function_exists('getCompletedQty')) {
    function getCompletedQty($date, $product_id, $sub_product_id, $unit = null)
    {
        try {
            $targetDate = Carbon::parse($date)->startOfDay();
        } catch (Exception $e) {
            return 0;
        }

        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $operations = SOProductOperationDetails::query()
            ->select(
                'sales_order_product_operation_details.so_id',
                'sales_order_product_operation_details.sales_order_product_id',
                'sales_order_product_operation_details.sub_product_id',
                'sales_order_product_operation_details.processed_qty'
            )
            ->join('erp_sales_orders as eso', 'sales_order_product_operation_details.so_id', '=', 'eso.so_id')
            ->where('sales_order_product_operation_details.product_id', $product_id)
            ->where('sales_order_product_operation_details.sub_product_id', $sub_product_id)
            ->whereNotNull('sales_order_product_operation_details.operation_status')
            ->where('sales_order_product_operation_details.operation_status', '!=', 'Outsourced')
            ->where('sales_order_product_operation_details.operation_status', '!=', '')
            ->when($unitId, fn($q) => $q->where('eso.so_unitid', $unitId))
            ->get();

        if ($operations->isEmpty()) {
            return 0;
        }

        // Group operations by sales_order_product_id + sub_product_id
        $grouped = $operations->groupBy(function ($item) {
            return $item->so_id . '-' . $item->sales_order_product_id . '-' . $item->sub_product_id;
        });

        $totalCompletedQty = 0;

        foreach ($grouped as $groupOps) {
            $groupQtySets = [];

            foreach ($groupOps as $op) {
                $decoded = json_decode($op->processed_qty, true);
                if (!is_array($decoded)) continue;

                $completedInOp = [];
                foreach ($decoded as $entry) {
                    if (
                        isset($entry['status'], $entry['completed_date'], $entry['quantity']) &&
                        $entry['status'] === 'completed' &&
                        !empty($entry['completed_date'])
                    ) {
                        try {
                            $completedDate = Carbon::parse($entry['completed_date'])->startOfDay();
                            if ($completedDate->equalTo($targetDate)) {
                                $completedInOp[] = (int)$entry['quantity'];
                            }
                        } catch (Exception $e) {
                            continue;
                        }
                    }
                }

                $groupQtySets[] = $completedInOp;
            }

            // Intersect all operations' completed quantities
            if (!empty($groupQtySets)) {
                $fullyCompletedQty = array_shift($groupQtySets);
                foreach ($groupQtySets as $set) {
                    $fullyCompletedQty = array_intersect($fullyCompletedQty, $set);
                    if (empty($fullyCompletedQty)) break;
                }

                $totalCompletedQty += count($fullyCompletedQty);
            }
        }

        return $totalCompletedQty;
    }
}

if (!function_exists('getNotificationCount')) {
    function getNotificationCount()
    {
        return Notification::where('is_read', false)->count();
    }
}

if (!function_exists('getNotificationInDrop')) {
    function getNotificationInDrop()
    {
        return Notification::where('is_read', false)->orderBy('id', 'desc')->limit(5)->get();
    }
}

if (!function_exists('getParamFirstValueNew')) {
    function getParamFirstValueNew($op_id, $p_id, $passId = NULL)
    {

        if (empty($op_id) || empty($p_id)) {
            return 'NA';
        }

        $operation = OperationMaster::find($op_id);
        $soo = SOProductOperationDetails::where(['sales_order_product_id' => $p_id, 'operation_id' => $op_id])->first();
        // $sop = SalesOrderProduct::where(['so_id' => $soo->so_id, 'sub_product_id' => $soo->sub_product_id])->value('measureunit');

        if (!$operation || !$soo) {
            return 'NA';
        }

        if ($soo->operation_status === 'Manual' || $soo->operation_status === 'ERP-Manual') {
            return $soo->cycle_time;
        }

        $data = SalesOrderProduct::where(['so_id' => $soo->so_id, 'sub_product_id' => $soo->sub_product_id])->first();

        if (!$data) {
            return 'NA';
        }

        $getVal = 'NA';

        $op_name = $operation->operation_name;

        if ($operation->unit === 'Tooling') {

            $size1_ops = [
                'Cutting',
                'Fine Grinding-1',
                'Fine Grinding-2',
                'Rubber Vulcanization'
            ];

            $size3_ops = [
                'Axial Parting',
                'U Drilling',
                'Round Keyway Milling',
                'CNC Blanking-OD Grooving',
                'Bevel Grinding'
            ];

            $average_ops = [
                'Radial Parting',
                'Ring Parting',
                'Rough Surface Grinding',
                'Semi Final Thickness Grinding CNC',
                'Final Thickness Grinding-CNC',
                'Final Thickness Grinding',
                'Final Thickness Grinding on CNC-1',
                'Final Thickness Grinding on CNC-2',
                'Final Thickness Grinding on CNC',
                'Fine Grinding'
            ];

            $erpFormula = [
                'CNC Blanking Disc Form-1',
                'CNC Blanking Disc Form-2',
                'CNC Blanking Ring Form-1',
                'CNC Blanking Ring Form-2',
                'CNC Blanking-Deep Undercut',
                'CNC Blanking-Shallow Undercut',
            ];

            $erpManual = ['Keyway Slotting'];

            $fixed  = ['Lapping-1',];
            $fixed1 = ['Lapping-2',];
            $fixed2 = ['Lapping-3',];
            $fixed3 = ['Lapping-4',];
            $fixed4 = ['Dirt Groove Cleaning', 'Junction Grinding'];

            $NA_Opt = ['Bore Grinding', 'Keyway Width', 'Bore Grinding', 'Stack Length'];

            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->material;
            } else if (in_array($op_name, $size3_ops)) {
                $getVal = $data->size3;
            } else if (in_array($op_name, $average_ops)) {
                $getVal = 0;
            } else if (in_array($op_name, $erpFormula)) {
                $getVal = 0;
            } else if (in_array($op_name, $fixed)) {
                $getVal = 60;
            } else if (in_array($op_name, $fixed1)) {
                $getVal = 120;
            } else if (in_array($op_name, $fixed2)) {
                $getVal = 150;
            } else if (in_array($op_name, $fixed3)) {
                $getVal = 180;
            } else if (in_array($op_name, $fixed4)) {
                $getVal = 10;
            } else if (in_array($op_name, $erpManual)) {
                $getVal = $data->size3;
            } else if (in_array($op_name, $NA_Opt)) {
                $getVal = 'NA';
            }
        }

        if ($operation->unit === 'RMR') {

            $size1_ops = ['Raw Material', 'Hard Turning-1', 'Stress Relieve', 'Hard Turning-2', 'Grinding', 'Hard Turning', 'Grinding-1', 'Grinding-2', 'Grinding-3', 'Milling', 'Manual lathe', 'Manual lathe-1', 'Manual lathe-2', 'Milling-2', 'Manual Lathe-3', 'Manual Lathe-4', 'Fitter work'];

            $size3_ops = ['Cutting'];

            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->size1;
            } else if (in_array($op_name, $size3_ops)) {
                $getVal = $data->size3;
            }
        }

        //  TMR
        if ($operation->unit === 'TMR') {

            $size1 = $data->size1;
            $size2 = $data->size2;
            $size3 = $data->size3;
            $bs1_dia = $data->bs1_blankdia;
            $bs1_depth = $data->bs1_depthdia;

            if ($data->measureunit == 'SET') {

                if (!empty($passId)) {
                    $passDetails = PassSheet::find($passId);
                } else {
                    $passDetails = PassSheet::where('cpoitemid', $data->cpoitemid)->first();
                }

                $passDetails = PassSheet::find($passId);

                $size1 = $passDetails->size1;
                $size2 = $passDetails->size2;
                $size3 = $passDetails->size3;
                $bs1_dia = $passDetails->bs1_dia;
                $bs1_depth = $passDetails->bs1_depth;
            }

            $material = ['Cutting'];
            $size1_ops = ['CNC Blanking-4'];
            $size2_ops = [
                'CNC Blanking-1',
                'Plain Bore Turning Both Side',
                'Bore Turning with Keyway-Both Side',
                'Bore Turning with Keyway & Under Cut-Single Side',
                'Plain Bore Turning with Under Cut-Single Side',
            ];

            $material = ['Cutting'];

            if (in_array($op_name, $material)) {
                $getVal = $data->material;
            }

            $size3_ops = ['U Drilling', 'Round Keyway Milling', 'Bore Turning With Under Cut-Single Side', 'Bore Turning Both Side', 'Bore Grinding Both Side', 'Keyway Wirecut', 'Rib Thickness/ Boss OD-Both side', 'Final OD Final Angle', 'Bevel Grinding', 'Final OD, Final Angle', 'Bore Grinding with Under Cut-Single Side'];

            $other = ['Keyway Slotting', ''];
            $bs1 = ['Bearing Seat-Single Side-2', 'Bearing Seat-Single Side', 'Bearing Seat-Single Side-1'];

            $erpFormula = ['CNC Blanking-2', 'CNC Blanking-3', 'Thickness Turning-Single Side',];

            if (in_array($op_name, $material)) {
                $getVal = $data->material;
            }
            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->size1;
            }
            if (in_array($op_name, $size2_ops)) {
                $getVal = $data->size2;
            }
            if (in_array($op_name, $size3_ops)) {
                $getVal = $data->size3;
            }
            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->size1;
            }
            if (in_array($op_name, $other)) {
                $getVal = $data->kw_size1; //Keyway Slotting
            }
            if (in_array($op_name, $erpFormula)) {
                $getVal = 'Non numeric value';
            }
            if (in_array($op_name, $bs1)) {
                $getVal = $data->bs1_blankdia;
            }
        }

        return $getVal;
    }
}

if (!function_exists('getParamSecondValueNew')) {
    function getParamSecondValueNew($op_id, $p_id, $passId = NULL)
    {
        if (empty($op_id) || empty($p_id)) {
            return 'NA';
        }

        $operation = OperationMaster::find($op_id);
        $soo = SOProductOperationDetails::where(['sales_order_product_id' => $p_id, 'operation_id' => $op_id])->first();

        if (!$operation || !$soo) {
            return 'NA';
        }

        $data = SalesOrderProduct::where(['so_id' => $soo->so_id, 'sub_product_id' => $soo->sub_product_id])->first();

        if (!$data) {
            return 'NA';
        }

        $getVal = 'NA';

        $op_name = $operation->operation_name;

        if ($operation->unit === 'Tooling') {

            $size1_ops = ['Cutting'];

            $erpFormula = [
                'CNC Blanking Disc Form-1',
                'CNC Blanking Ring Form-1',
                'CNC Blanking-Deep Undercut',
                'CNC Blanking-Shallow Undercut',
                'Rubber Vulcanization',
            ];

            $erpParam = ['Round Keyway Milling'];

            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->size1;
            } elseif (in_array($op_name, $erpFormula)) {
                $getVal = $data->size3;
            } elseif (in_array($op_name, $erpParam)) {
                $getVal = $data->kw_size1;
            } else if (in_array($op_name, $erpFormula)) {
                $getVal = 0;
            }
        }

        if ($operation->unit === 'RMR') {

            $naParam   = ['Raw Material',];
            $size3_ops = ['Hard Turning-1', 'Stress Relieve', 'Hard Turning-2', 'Grinding', 'Hard Turning', 'Grinding-1', 'Grinding-2', 'Grinding-3', 'Milling', 'Manual lathe', 'Manual lathe-1', 'Manual lathe-2', 'Milling-2', 'Manual Lathe-3', 'Manual Lathe-4', 'Fitter work'];

            if (in_array($op_name, $size3_ops)) {
                $getVal = $data->size3;
            } else if (in_array($op_name, $naParam)) {
                $getVal = 'NA';
            }
        }

        // TMR ==========================
        if ($operation->unit === 'TMR') {

            $size1_ops = ['Cutting'];
            $size2_ops = ['Bore Turning With Under Cut-Single Side', 'Bore Turning Both Side', 'Bore Grinding Both Side', 'Bore Grinding with Under Cut-Single Side'];
            $size3_ops = ['CNC Blanking-1', 'Plain Bore Turning Both Side', 'Plain Bore Turning with Under Cut-Single Side', 'Bore Turning with Keyway-Both Side', 'Bore Turning with Keyway & Under Cut-Single Side'];
            $kw_size1 = ['Round Keyway Milling'];
            $bs1 = ['Bearing Seat-Single Side', 'Bearing Seat-Single Side-2'];
            $NA_ops = ['U Drilling', ''];

            if (in_array($op_name, $size1_ops)) {
                $getVal = $data->size1;
            } else if (in_array($op_name, $size2_ops)) {
                $getVal = $data->size2;
            } else if (in_array($op_name, $size3_ops)) {
                $getVal = $data->size3;
            } else if (in_array($op_name, $NA_ops)) {
                $getVal = 'NA';
            } else if (in_array($op_name, $kw_size1)) {
                $getVal = $data->kw_size1;
            } else if (in_array($op_name, $bs1)) {
                $getVal = $data->bs1_depthdia;
            }
        }
        return $getVal;
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($key)
    {
        $user = auth()->user();

        if (!$user || !$user->roleName) {
            return false;
        }

        $permissions = $user->rolePermission->permissions;

        if (empty($permissions)) return false;

        return !empty($permissions[$key]);
    }
}
