<?php

namespace App\Http\Controllers;

use App\Models\{ErpSalesOrder, MachineMaster, OperationMaster, ProductMasters, SubProduct, User};
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\{Storage, DB, File};
use Barryvdh\DomPDF\Facade\Pdf;

class QRCodeController extends Controller
{   
    public function index()
    {

        $data['operator']     =  User::operator()->with('roleName')->paginate(PAGE_NO);
        $data['machines']     =  MachineMaster::paginate(PAGE_NO);
        $data['products']     =  SubProduct::with('product')->paginate(PAGE_NO);
        $data['operations']   =  OperationMaster::with('machines')->paginate(PAGE_NO);
        $data['genericQr']    =  DB::table('generic_qrcodes')->paginate(PAGE_NO);

        return view('qr-codes.index', compact('data'));
    }

    public function fetchModal(Request $request)
    {

        $id     =   $request->id;
        $type   =   $request->type; // Operator, SO, Machine,  

        $operations   = OperationMaster::find($id);
        $operator     = User::find($id);
        $machines     = MachineMaster::find($id);
        $products     = SubProduct::with('product')->find($id);

        if ($type   ==   'Operations') {
            $qrCode = $operations->operation_qr_code;
            $id     = $operations->id;
        }
        if ($type   ==   'Operator') {
            $qrCode = $operator->user_qr_code;
            $id     = $operator->id;
        }
        if ($type   ==   'Machine') {
            $qrCode = $machines->machine_qr_code;
            $id     = $machines->id;
        }
        if ($type   ==   'Product') {
            $qrCode = $products->product_qr_code;
            $id     = $products->id;
        }

        return view('qr-codes.qr-code-modal', compact('qrCode', 'id', 'type'));
    }

    // Regenerate QR Codes
    public function regenerateQrCard(Request $request)
    {

        $id     =   $request->id;
        $type   =   $request->type;

        $operations   = OperationMaster::find($id);
        $operator     = User::find($id);
        $machines     = MachineMaster::find($id);
        $products     = SubProduct::with('product')->find($id);

        if ($type    ==   'Operations') {
            $qrCode  =  $operations->operation_qr_code;
            $id      =  $operations->id;

            deleteImage($qrCode);

            // Generate the QR code
            $result = Builder::create()
                ->data($operations->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $operations->id . '-' . time() . '.png';

            $path = 'operation-qr-codes/' . $name; // unique filename

            Storage::disk('public')->put($path, $result->getString());

            OperationMaster::where('id', $operations->id)->update(['operation_qr_code' => $name]);
        }

        if ($type   ==   'Operator') {
            $qrCode = $operator->user_qr_code;
            $id     = $operator->id;

            deleteImage($qrCode);

            // Generate the QR code
            $result = Builder::create()
                ->data($operator->username)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $operator->id . '-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'user-qrcodes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('users')
                ->where('id', $operator->id)
                ->update(['user_qr_code' => $name]);
        }

        if ($type   ==   'Machine') {
            $qrCode = $machines->machine_qr_code;
            $id     = $machines->id;

            deleteImage($qrCode);

            // Generate the QR code
            $result = Builder::create()
                ->data($machines->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $machines->machine . '-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'machine-qrcodes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('machine_master')
                ->where('id', $machines->id)
                ->update(['machine_qr_code' => $name]);
        }

        if ($type   ==   'Product') {
            $qrCode = $products->product_qr_code;
            $id     = $products->id;

            deleteImage($qrCode);

            // == Generate the QR code 
            $result = Builder::create()
                ->data($products->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $products->unit . '-' . $products->erp_nomenclature . '-' . $products->group . '-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'product-qrcodes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            DB::table('product_masters')->where('id', $products->id)->update(['product_qr_code' => $name]);
        }

        // Return success response with token and user information
        return response()->json([
            'status'  => true,
            'message' => 'QR regerated successfully',
        ]);
    }

    public function deactivateQrCard(Request $request)
    {

        $id     =   $request->id;
        $type   =   $request->type; // Operator, SO, Machine, Products

        $salesOrder   = ErpSalesOrder::find($id);
        $operator     = User::where('role', 'operator')->find($id);
        $machines     = MachineMaster::find($id);
        $products     = SubProduct::with('product')->find($id);

        if ($type    ==   'SO') {
            $qrCode  =  $salesOrder->so_qr_code;
            $id      =  $salesOrder->id;

            ErpSalesOrder::where('id', $salesOrder->id)->update(['so_qr_code' => null]);
        }

        if ($type   ==   'Operator') {
            $qrCode = $operator->user_qr_code;
            $id     = $operator->id;

            User::where('id', $operator->id)->update(['user_qr_code' => null]);
        }

        if ($type   ==   'Machine') {
            $qrCode = $machines->machine_qr_code;
            $id     = $machines->id;

            MachineMaster::where('id', $machines->id)->update(['machine_qr_code' => null]);
        }

        if ($type   ==   'Product') {
            $qrCode = $products->product_qr_code;
            $id     = $products->id;

            ProductMasters::where('id', $products->id)->update(['product_qr_code' => null]);
        }

        deleteImage($qrCode);

        // Return success response with token and user information
        return response()->json([
            'status'  => true,
            'message' => 'QR deactived successfully',
        ]);
    }

    // delete QR Codes
    public function deleteQrCard(Request $request)
    {

        $id     =   $request->id;
        $type   =   $request->type; // Operator, SO, Machine, Products

        $operations   = OperationMaster::find($id);
        $operator     = User::where('role', 'operator')->find($id);
        $machines     = MachineMaster::find($id);
        $products     = SubProduct::with('product')->find($id);

        if ($type    ==   'Operations') {
            $qrCode  =  $operations->operation_qr_code;
            OperationMaster::where('id', $operations->id)->update(['operation_qr_code' => null]);
        }

        if ($type   ==   'Operator') {
            $qrCode = $operator->user_qr_code;
            User::where('id', $operator->id)->update(['user_qr_code' => null]);
        }

        if ($type   ==   'Machine') {
            $qrCode = $machines->machine_qr_code;
            MachineMaster::where('id', $machines->id)->update(['machine_qr_code' => null]);
        }

        if ($type   ==   'Product') {
            $qrCode = $products->product_qr_code;
            ProductMasters::where('id', $products->id)->update(['product_qr_code' => null]);
        }

        deleteImage($qrCode);

        // Return success response with token and user information
        return response()->json([
            'status'  => true,
            'message' => 'QR deleted successfully',
        ]);
    }

    public function search(Request $request)
    {
        $search    = $request->input('search'); // Get the search term from the request
        $activeTab = $request->input('activeTab'); // Get the current tab from the request
        $data      = [];
        $datav     = null;

        if ($activeTab == 'operator') {
            $query = User::operator()->with('roleName');
            $query->when($search, function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
            $data = $data['operator'] = $query->paginate(PAGE_NO);
        } elseif ($activeTab == 'operations') {

            $query =  OperationMaster::with('machines')

                ->when($search, function ($query, $search) {
                    $query->where('operation_name', 'like', "%$search%");
                    $query->orWhere('unit', 'like', "%$search%");
                    $query->orWhere('parameter_input', 'like', "%$search%");
                    $query->orWhere('matrix', 'like', "%$search%");
                    $query->orWhere('parameters', 'like', "%$search%");
                });

            $data = $data['operations'] = $query->paginate(PAGE_NO);
        } elseif ($activeTab == 'machines') {

            $query = MachineMaster::query();
            $query->when($search, function ($q) use ($search) {
                $q->where('machine_qr_code', 'LIKE', "%{$search}%")
                    ->orWhere('machine_type', 'LIKE', "%{$search}%")
                    ->orWhere('machine', 'LIKE', "%{$search}%")
                    ->orWhere('machine', 'LIKE', "%{$search}%")
                    ->orWhere('unit_name', 'LIKE', "%{$search}%")
                    ->orWhere('section', 'LIKE', "%{$search}%")
                    ->orWhere('sub_section', 'LIKE', "%{$search}%");
            });
            $data = $data['machines'] = $query->paginate(PAGE_NO);
        } elseif ($activeTab == 'products') {
            $query = SubProduct::with('product');
            $query->when($search, function ($q) use ($search) {
                $q->where('sub_product_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('product', function ($subQuery) use ($search) {
                        $subQuery->where('unit', 'LIKE', "%{$search}%");
                    });
            });
            $data =  $data['products'] = $query->paginate(PAGE_NO);
        }

        // Return HTML for table rows and pagination links
        return response()->json([
            'html'       => view('qr-codes.search-table', compact('data', 'activeTab'))->render(),
            'pagination' =>  (string) $data->links()
        ]);
    }

    public function generateQrCard(Request $request)
    {
        $qr_value   = $request->qr_code;
        $qr_use_for = $request->qr_use_for;

        // Generate the QR code
        $result = Builder::create()
            ->data($qr_value)
            ->size(300)
            ->margin(10)
            ->build();

        // Clean file name to avoid special characters
        $safeName = preg_replace('/[^A-Za-z0-9\-]/', '_', $qr_value);
        $fileName = $safeName . '-' . time() . '.png';
        $relativePath = 'storage/generic-qrcodes/' . $fileName;
        $fullPath = public_path($relativePath);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Save file to public directory
        file_put_contents($fullPath, $result->getString());


        $path = 'generic-qrcodes/' . $fileName;

        // Save to storage (public disk)
        Storage::disk('public')->put($path, $result->getString());

        // Insert DB record
        $qrGenerate = DB::table('generic_qrcodes')->insert([
            'code'       => $qr_value,
            'qr_code'    => $fileName,
            'qr_use_for' => $qr_use_for,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($qrGenerate) {
            return back()->with('success', 'QR generated successfully!');
        } else {
            return back()->withErrors(['qr' => 'Failed to generate QR.'])->withInput();
        }
    }



    public function genericQRs()
    {
        $data =  DB::table('generic_qrcodes')->get();
        return view('admin/genericQRs', compact('data'));
    }

    //deleteGenericQR
    public function deleteGenericQR($id)
    {
        // Fetch the record
        $generic_qr = DB::table('generic_qrcodes')->where('id', $id)->first();

        // Check if record exists
        if (!$generic_qr) {
            return redirect()->back()->with('error', 'QR not found!');
        }

        // Delete the record
        DB::table('generic_qrcodes')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'QR deleted successfully!');
    }

    public function qrPdfPreview(Request $request)
    {
        $data =  DB::table('generic_qrcodes')->get();
        if (isset($_GET['pdf']) && $_GET['pdf'] == 'true') {
            $pdf =  Pdf::setOptions([
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
            ])->loadView('qr-codes.pdf.qr-pdf', compact('data'));
            return $pdf->stream('document.pdf');
        }
        return view('qr-codes.qr-pdf-preview', compact('data'));
    }
     
    public function machinePdfPreview($id)
    {
        $data =  MachineMaster::with('operations')->find($id);
 
        if(isset($_GET['pdf']) && $_GET['pdf'] == 'true') {
                $pdf =  Pdf::setOptions([
                    'isPhpEnabled' => true,
                    'isRemoteEnabled' => true,
                ])->loadView('qr-codes.pdf.machine-pdf', compact('data'));
                return $pdf->stream('document.pdf');    
        }
        return view('qr-codes.machine-pdf-preview', compact('data'));
    }
}
