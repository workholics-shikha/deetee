<?php

namespace App\Http\Controllers;

use App\Exports\SalesOrderTrackingExport;
use App\Models\ErpSalesOrder;
use App\Models\MachineMaster;
use App\Models\OperationMaster;
use App\Models\PassSheet;
use App\Models\ProductMasters;
use App\Models\Role;
use App\Models\SalesOrderProduct;
use App\Models\SOProductOperationDetails;
use App\Models\SubProduct;
use App\Models\User;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    public function importMachineCSV(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // Optional: skip header if needed
        foreach (array_slice($data, 1) as $row) {
            MachineMaster::create([
                'unit_number' => $row[0] ?? null,
                'unit_name' => $row[1] ?? null,
                'machine' => $row[2] ?? null,
                'machine_type' => $row[3] ?? null,
                'section' => $row[4] ?? null,
                'sub_section' => $row[5] ?? null,
            ]);
        }

        return back()->with('success', 'Machines imported successfully!');
    }

    public function importProductCSV(Request $request)
    {
        $request->validate([
            'product_csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('product_csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // Optional: skip header if needed
        foreach (array_slice($data, 1) as $row) {
            ProductMasters::create([
                'unit_number' => $row[0] ?? null,
                'unit' => $row[1] ?? null,
                'group' => $row[2] ?? null,
                'erp_product' => $row[3] ?? null,
                'erp_nomenclature' => $row[4] ?? null,
                'product_modified_name' => $row[5] ?? null,
            ]);
        }

        return back()->with('success', 'Products imported successfully!');
    }

    public function importSubProductCSV(Request $request)
    {
        $request->validate([
            'sub_product_csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('sub_product_csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // Optional: skip header if needed
        foreach (array_slice($data, 1) as $row) {

            $product_master_id = ProductMasters::where('erp_product', 'like', "%$row[0]%")->first();
            $product_name = $product_master_id->id;

            SubProduct::create([
                'product_master_id' => $product_name ?? null,
                'sub_product_name' => $row[1] ?? null,
            ]);
        }

        return back()->with('success', 'Products imported successfully!');
    }

    public function importOperationCSV(Request $request)
    {
        $request->validate([
            'operation_csv_file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('operation_csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // == Optional: skip header if needed ==//
        foreach (array_slice($data, 1) as $row) {

            $operationName = $row[0];

            $operationName = str_replace(' - ', '-', $operationName);
            $operationName = str_replace('- ', '-', $operationName);
            $operationName = str_replace(' -', '-', $operationName);
            $operationName = str_replace(' + ', '+', $operationName);
            $operationName = str_replace('+ ', '+', $operationName);
            $operationName = str_replace(' +', '+', $operationName);

            OperationMaster::create([
                'operation_name' => $operationName ?? null,
                'unit' => $row[1] ?? null,
                'parameter_input' => $row[2] ?? null,
                'matrix' => $row[3] ?? null,
                'parameter1' => $row[4] ?? null,
                'parameter2' => $row[5] ?? null,
                'parameter3' => $row[6] ?? null,
                'parameter4' => $row[7] ?? null,
            ]);
        }

        return back()->with('success', 'Operations imported successfully!');
    }

    public function generateEmail($name, $domain = 'example.com')
    {
        // Convert name to lowercase and remove special characters
        $cleanName = strtolower(preg_replace('/[^a-z0-9]/i', '', str_replace(' ', '.', $name)));

        return $cleanName.'@'.$domain;
    }

    public function generatePhoneNumber()
    {
        return '9'.str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT);
    }

    public function importUsersCSV(Request $request)
    {
        $request->validate(['user_csv_file' => 'required|mimes:csv,txt']);

        $file = $request->file('user_csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        // == Optional: skip header if needed ==//
        foreach (array_slice($data, 1) as $row) {

            $getRoleId = Role::where('name', 'like', "%$row[2]%")->value('id');

            User::create([
                'name' => $row[0] ?? null,
                'username' => $row[1] ?? null,
                'designation' => $row[2] ?? null,
                'department' => $row[3] ?? null,
                'unit' => $row[4] ?? null,
                'unit_name' => $row[5] ?? null,
                'employee_group' => $row[6] ?? null,
                'email' => $row[7] ?? $this->generateEmail($row[0]),
                'role' => $getRoleId ?? null,
            ]);
        }

        // == generate QR ==
        $users = User::where('id', '!=', '1')->get();

        foreach ($users as $user) {
            // Generate the QR code
            $result = Builder::create()
                ->data($user->username)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $nameQR = $user->id.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'user-qrcodes/'.$nameQR; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('users')->where('id', $user->id)->update(['user_qr_code' => $nameQR]);
        }
        // =================

        return back()->with('success', 'Users imported successfully!');
    }

    public function subProductOperation(Request $request)
    {
        $request->validate(['subproductoperation_csv_file' => 'required|mimes:csv,txt']);

        $file = $request->file('subproductoperation_csv_file');
        $data = array_map('str_getcsv', file($file->getRealPath()));

        foreach (array_slice($data, 1) as $row) {
            if (! empty($row[0]) && ! empty($row[3])) {
                $operationName = $row[3];

                $operationName = str_replace(' - ', '-', $operationName);
                $operationName = str_replace('- ', '-', $operationName);
                $operationName = str_replace(' -', '-', $operationName);
                $operationName = str_replace(' + ', '+', $operationName);
                $operationName = str_replace('+ ', '+', $operationName);
                $operationName = str_replace(' +', '+', $operationName);

                $operation = OperationMaster::select('operation_name', 'id', 'unit')->where('operation_name', 'like', "%$operationName%")->where('unit', 'like', 'RMR')->first();

                $unit = $operationid = '';

                if ($operation) {
                    $operationid = $operation->id;
                    if (is_object($operation) && isset($operation->unit)) {
                        $unit = $operation->unit;
                    }
                }

                DB::table('subproduct_wise_operation')->insert([
                    'product_master_id' => $row[0] ?? null,
                    'subproduct_id' => $row[1] ?? null,
                    'operation_id' => $operationid,
                    'operation_name' => $row[3] ?? null,
                    'unit' => $unit,
                    'sub_operations' => $row[4] ?? null,
                ]);
            }
        }

        return back()->with('success', 'Subproduct Operation imported successfully!');
    }

    public function importIdealCycleData(Request $request)
    {
        $request->validate(['cycle_csv_file' => 'required|mimes:csv,txt']);

        $file = $request->file('cycle_csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        $header = fgetcsv($handle); // skip header

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {

            DB::table('ict_bore_thickness_values')->insert([
                'bore_min' => $row[0] ?? null,
                'bore_max' => $row[1] ?? null,
                'cycle_time_value' => $row[2] ?? null,
                'machine_id' => trim($row[3]), // remove extra quotes
                'operation_id' => $row[4] ?? null,
                'product_id' => $row[5] ?? null,
                'sub_product_id' => $row[6] ?? null,
                'table_count' => $row[7] ?? null,
                'thickness_min' => $row[0] ?? null,
                'thickness_max' => $row[1] ?? null,
            ]);
        }
        fclose($handle);

        return back()->with('success', 'Data imported successfully!');
    }

    public function export()
    {
        return Excel::download(new SalesOrderTrackingExport, 'sales_order_trackings.xlsx');
    }

    public function qrGenerate()
    {
        $machines = DB::table('machine_master')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($machines as $machine) {
            // Generate the QR code
            $result = Builder::create()
                ->data($machine->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            // Clean and lowercase the filename
            $machine_name = $machine->machine ?? 'machine-'.$machine->id;
            $safeName = Str::slug(strtolower($machine_name), '-');
            $fileName = $safeName.'-'.time().'.png';
            $path = 'machine-qrcodes/'.$fileName; // relative to 'storage/app/public'

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());
            // Update the machine record with the QR code path
            DB::table('machine_master')
                ->where('id', $machine->id)
                ->update(['machine_qr_code' => $fileName]);
        }

        $products = DB::table('product_masters')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($products as $product) {

            // == Generate the QR code
            $result = Builder::create()
                ->data($product->id.';Product')
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $product->unit.'-'.$product->erp_nomenclature.'-'.$product->group.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'product-qrcodes/'.$name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('product_masters')
                ->where('id', $product->id)
                ->update(['product_qr_code' => $name]);
        }
    }

    public function qrGenerateOperations()
    {
        $operations = DB::table('operation_masters')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($operations as $operation) {
            // Insert the machine record and get its IDph
            $jsonData = ['id' => $operation->id, 'type' => 'operation', 'operation_name' => $operation->operation_name];

            $jsonString = json_encode($jsonData);

            // Generate the QR code
            $result = Builder::create()
                ->data($operation->id.';Operation')
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $operation->id.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'operation-qr-codes/'.$name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('operation_masters')->where('id', $operation->id)->update(['operation_qr_code' => $name]);
        }
    }

    public function qrGenerateSO()
    {
        $erpSalesOrder = ErpSalesOrder::get();
        foreach ($erpSalesOrder as $salesOrder) {
            // Generate the QR code
            $result = Builder::create()
                ->data($salesOrder->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $salesOrder->so_no.'-'.time().'.png';

            $path = 'so-qrcodes/'.$name; // unique filename

            Storage::disk('public')->put($path, $result->getString());
            ErpSalesOrder::where('id', $salesOrder->id)->update(['so_qr_code' => $name]);
        }
    }

    public function qrGenerateUsers()
    {
        $users = User::where('id', '!=', '1')->get();

        foreach ($users as $user) {
            // Generate the QR code
            $result = Builder::create()
                ->data($user->username)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $nameQR = $user->id.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'user-qrcodes/'.$nameQR; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('users')->where('id', $user->id)->update(['user_qr_code' => $nameQR]);
        }
    }

    public function qrGenerateGeneric()
    {
        $users = DB::table('generic_qrcodes')->get();

        foreach ($users as $user) {

            $qr_value = $user->code;

            // Generate the QR code
            $result = Builder::create()
                ->data($qr_value)
                ->size(300)
                ->margin(10)
                ->build();

            // Clean file name to avoid special characters
            $safeName = preg_replace('/[^A-Za-z0-9\-]/', '_', $qr_value);
            $fileName = $safeName.'-'.time().'.png';
            $relativePath = 'storage/generic-qrcodes/'.$fileName;
            $fullPath = public_path($relativePath);

            // Ensure directory exists
            $directory = dirname($fullPath);
            if (! File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            // Save file to public directory
            file_put_contents($fullPath, $result->getString());

            $path = 'generic-qrcodes/'.$fileName;

            // Save to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());
            // Update the machine record with the QR code path
            DB::table('generic_qrcodes')->where('id', $user->id)->update(['qr_code' => $fileName]);
        }
    }

    public function qrGenerateSoProduct()
    {
        $subProducts = SalesOrderProduct::get();
        if (! empty($subProducts)) {
            foreach ($subProducts as $products) {

                $product = ProductMasters::where(['id' => $products->product_id])
                    ->first(['id', 'product_flow', 'cycle_flow']);

                $product_status = ($product &&
                    strtolower($product->product_flow) === 'available' &&
                    strtolower($product->cycle_flow) === 'available')
                    ? 'Available'
                    : 'Not Available';

                SalesOrderProduct::where('id', $products->id)->update(['product_status' => $product_status]);
            }
        }
    }

    public function copyOperationPngInSOPDetails()
    {
        $all_details = SOProductOperationDetails::whereNotNull('operation_id')->get();

        foreach ($all_details as $details) {

            $operation = OperationMaster::find($details->operation_id);

            if ($operation) {
                $originalQr = $operation->getRawOriginal('operation_qr_code'); // ← real value

                if (! empty($originalQr)) {
                    SOProductOperationDetails::where('id', $details->id)
                        ->update(['operation_qr_code' => $originalQr]);
                }
            }
        }
    }

    public function generateNewQrCodes()
    {

        // Operations
        $operations = DB::table('operation_masters')->get();
        foreach ($operations as $operation) {

            // Generate the QR code
            $result = Builder::create()
                ->data('OPN'.$operation->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();
            $name = $operation->id.'-'.time().'.png';
            // Path where you want to save the QR code image
            $path = 'operation-qr-codes/'.$name; // unique filename
            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());
            // Update the machine record with the QR code path
            DB::table('operation_masters')->where('id', $operation->id)->update(['operation_qr_code' => $name]);
        }
        exit;
        // ========== end - Operations

        // for Machines
        $machines = DB::table('machine_master')->get();
        foreach ($machines as $machine) {
            // Generate the QR code
            $result = Builder::create()
                ->data('MAC'.$machine->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            // Clean and lowercase the filename
            $machine_name = $machine->machine ?? 'machine-'.$machines->id;
            $safeName = Str::slug(strtolower($machine_name), '-');
            $fileName = $safeName.'-'.time().'.png';
            $path = 'machine-qrcodes/'.$fileName; // relative to 'storage/app/public'

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());
            // Update the machine record with the QR code path
            DB::table('machine_master')
                ->where('id', $machine->id)
                ->update(['machine_qr_code' => $fileName]);
        }
        // ========== end - Machines

        // for Sales Order
        $erpSalesOrder = ErpSalesOrder::get();
        foreach ($erpSalesOrder as $salesOrder) {
            // Generate the QR code
            $result = Builder::create()
                ->data('SO'.$salesOrder->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $salesOrder->so_no.'-'.time().'.png';
            $path = 'so-qrcodes/'.$name; // unique-filename
            Storage::disk('public')->put($path, $result->getString());
            ErpSalesOrder::where('id', $salesOrder->id)->update(['so_qr_code' => $name]);
        }
        // ========== end - Sales Order
        // for Products
        $products = DB::table('product_masters')->get();
        foreach ($products as $product) {

            // == Generate the QR code
            $result = Builder::create()
                ->data('PRO'.$product->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = strtolower($product->unit).'-'.strtolower($product->erp_nomenclature).'-'.strtolower($product->group).'-'.time().'.png';
            // Path where you want to save the QR code image
            $path = 'product-qrcodes/'.$name; // unique filename
            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('product_masters')
                ->where('id', $product->id)
                ->update(['product_qr_code' => $name]);
        }
        // ========== end - Products
        // for Users/Operators
        $users = User::where('id', '!=', '1')->get();
        foreach ($users as $user) {
            if ($user->role != 2) {
                $user_prefix = 'USR';
            } else {
                $user_prefix = 'OPR';
            }
            // Generate the QR code
            $result = Builder::create()
                ->data($user_prefix.$user->username)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();
            $nameQR = $user->id.'-'.time().'.png';

            // Path where you want to save the QR code image
            $path = 'user-qrcodes/'.$nameQR; // unique filename
            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());
            // Update the machine record with the QR code path
            DB::table('users')->where('id', $user->id)->update(['user_qr_code' => $nameQR]);
        }
        // ========== end - Users/Operators

    }

    public function generateNewQrCodes2()
    {
        // Generate the QR code
        $passSheet = PassSheet::whereNull('pass_sheet_qr_code')->get();

        if (! empty($passSheet)) {
            foreach ($passSheet as $sheet) {
                $result = Builder::create()
                    ->data($sheet->id.';PassScan')
                    ->size(300) // Set size in pixels
                    ->margin(10) // Set margin in pixels
                    ->build();

                $name = $sheet->id.'-'.time().'.png';

                // Path where you want to save the QR code image
                $path = 'so-pass-sheet-qrcodes/'.$name; // unique filename

                // Save the QR code image to storage (public disk)
                Storage::disk('public')->put($path, $result->getString());

                // Update the machine record with the QR code path
                PassSheet::where('id', $sheet->id)->update(['pass_sheet_qr_code' => $name]);
            }
        }

        $subProducts = SalesOrderProduct::get();
        if (! empty($subProducts)) {
            foreach ($subProducts as $products) {
                $result = Builder::create()
                    ->data('PRO'.$products->id)
                    ->size(300) // Set size in pixels
                    ->margin(10) // Set margin in pixels
                    ->build();

                $qr_code_name = $products->cpoitemid.'-'.time().'.png';
                $path = 'so-product-qrcodes/'.$qr_code_name; // unique filename

                Storage::disk('public')->put($path, $result->getString());
                SalesOrderProduct::where('id', $products->id)->update(['so_product_qr_code' => $qr_code_name]);
            }
        }
    }

    public function addPassSheetDetails()
    {

        $subProducts = SalesOrderProduct::where('measureunit', 'SET')
            ->whereNotNull('sub_product_id')
            ->get();

        foreach ($subProducts as $details) {

            $erp_response = callErpApi(ERP_LINK.'/OH_showCPOItemPass/'.$details->cpoitemid);
            $itemjson = $erp_response->json();

            if (! empty($itemjson)) {
                foreach ($itemjson as $item) {

                    $passNos = splitPassNo($item['pass_no']);

                    foreach ($passNos as $passNo) {

                        PassSheet::insert([
                            'so_id' => $details->so_id,
                            'subproduct_pid' => $details->id,
                            'subproduct_id' => $details->sub_product_id,
                            'cpoitemid' => $item['cpoitemid'],
                            'sr_no' => $item['sr_no'],
                            'pass_no' => $passNo,
                            'mrk_pass_no' => $item['mrk_pass_no'],
                            'drawing_no' => $item['drawing_no'],
                            'size1' => $item['size1'],
                            'size2' => $item['size2'],
                            'size3' => $item['size3'],
                            'qty' => $item['qty'],
                            'material' => $item['material'],
                            'hardness' => $item['hardness'],
                            'bs1_dia' => $item['bs1_dia'], // for calculation
                            'bs1_depth' => $item['bs1_depth'], // for calculation
                            'bs1_bore' => $item['bs1_bore'],
                            'bs2_dia' => $item['bs2_dia'],
                            'bs2_depth' => $item['bs2_depth'],
                            'remarks' => $item['remarks'],
                            'revisioncount' => $item['revisioncount'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // Generate the QR code
        $passSheet = PassSheet::whereNull('pass_sheet_qr_code')->get();

        if (! empty($passSheet)) {
            foreach ($passSheet as $sheet) {
                $result = Builder::create()
                    ->data($sheet->id.';PassScan')
                    ->size(300) // Set size in pixels
                    ->margin(10) // Set margin in pixels
                    ->build();

                $name = $sheet->id.'-'.time().'.png';

                // Path where you want to save the QR code image
                $path = 'so-pass-sheet-qrcodes/'.$name; // unique filename

                // Save the QR code image to storage (public disk)
                Storage::disk('public')->put($path, $result->getString());

                // Update the machine record with the QR code path
                PassSheet::where('id', $sheet->id)->update(['pass_sheet_qr_code' => $name]);
            }
        }
    }
}
