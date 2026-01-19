<?php

namespace App\Http\Controllers;

use App\Models\{PassSheet, Role, SalesOrderProduct, SalesOrderTracking, User};
use Illuminate\Http\Request;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\{Hash, Storage, DB, Http, Validator};
use Carbon\{Carbon, CarbonPeriod};

class UserController extends Controller
{

    public function index()
    {
        // ======= 'operator','admin','supervisor','HOD','unit_head','CEO' =======
        $data['operator'] = User::operator()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);
        $data['supervisor'] = User::supervisor()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);
        $data['unit_head'] = User::UnitHead()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);
        $data['admin'] = User::admin()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);
        $data['hod'] = User::HOD()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);
        $data['ceo'] = User::CEO()->with('roleName')->orderBy('id','DESC')->paginate(PAGE_NO);

        return view('users.index', compact('data'));
    }

    public function operators()
    {
        $data = User::operator()->with('roleName')->paginate(PAGE_NO);
        return view('operator.index', compact('data'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search'); // Get the search term from the request

        // Fetch machines based on the search term with pagination
        $data = User::operator()->with('roleName')
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->paginate(PAGE_NO);

        // Return HTML for table rows and pagination links
        return response()->json([
            'html'       => view('operator.search-table', compact('data'))->render(),
            'pagination' => (string) $data->links(),
        ]);
    }

    public function userSearch(Request $request)
    {
        $search = $request->input('search');
        $activeTab = $request->input('activeTab');
        $query = User::with('roleName');

        if ($activeTab == 'administrators') {
            $query->admin();
        } else if ($activeTab == 'executive') {
            $query->HOD();
        } else if ($activeTab == 'unitHead') {
            $query->UnitHead();
        } else if ($activeTab == 'planning') {
            $query->CEO();
        } else if ($activeTab == 'supervisors') {
            $query->supervisor();
        } else if ($activeTab == 'operator') {
            $query->operator();
        }

        $query->when($search, function ($query, $search) {
            $query->where('name', 'LIKE', "%{$search}%");
            $query->orWhere('email', 'LIKE', "%{$search}%");
            $query->orWhere('phone', 'LIKE', "%{$search}%");
        });

        $data = $query->paginate(PAGE_NO);
        return response()->json([
            'html'       => view('users.search-table', compact('data'))->render(),
            'pagination' => (string) $data->links(),
        ]);
    }

    public function add()
    {
        $role = Role::all();
        $department = User::select('department')->whereNotNull('department')->groupBy('department')->get();
        $designation = User::select('designation')->whereNotNull('designation')->groupBy('designation')->get();
        $employee_group = User::select('employee_group')->whereNotNull('employee_group')->groupBy('employee_group')->get();
        return view('users.add', compact('role', 'department', 'designation', 'employee_group'));
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'role' => 'required',
            'designation' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->first())->withInput();
        }

        $name = $request->name;

        $userData['role'] = $request->role;
        $userData['name'] = $name;
        $userData['email'] = $request->email;
        $userData['phone'] = $request->phone;
        $userData['password'] = Hash::make($request->password);
        $userData['unit'] = $unit = $request->unit;
        $userData['designation'] = $request->designation;
        $userData['department'] = $request->department;
        $userData['employee_group'] = $request->employee_group;

        $unit_map = [1 => 'Tooling', 2 => 'RMR', 4 => 'TMR'];

        $userData['unit_name'] = $unit_map[$unit] ?? '';
        $userData['username'] = $username = generateCustomUsername($name);

        if ($request->hasFile('image')) {
            $attachment = fileUpload($request->file('image'), 'profile_images'); // === image upload ===
            $userData['profile_image'] = $attachment;
        }

        $user = User::create($userData);

        // Generate the QR code
        $result = Builder::create()
            ->data($username)
            ->size(300) // Set size in pixels
            ->margin(10) // Set margin in pixels
            ->build();

        $name = $user->id . '-' . time() . '.png';
        $path = 'user-qrcodes/' . $name; // Path      
        Storage::disk('public')->put($path, $result->getString());  // Save the QR code image to storage (public disk)

        // Update the machine record with the QR code path
        DB::table('users')
            ->where('id', $user->id)
            ->update(['user_qr_code' => $name]);

        // =================
        if ($user)
            return redirect()->route('admin.user.add')->with('success', 'User added successfully!');
        else
            return back()->withErrors(['admin.user.add' => 'Invalid credentials or not authorized.'])->withInput();
    }

    public function edit($id)
    {
        $role = Role::all();
        $user = User::find($id);
        $department = User::select('department')->whereNotNull('department')->groupBy('department')->get();
        $designation = User::select('designation')->whereNotNull('designation')->groupBy('designation')->get();
        $employee_group = User::select('employee_group')->whereNotNull('employee_group')->groupBy('employee_group')->get();
        return view('users.edit', compact('user', 'role', 'department', 'designation', 'employee_group'));
    }

    public function update(Request $request)
    {
        $id = $request->id;

        // Fetch the user by ID
        $user = User::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable',
            'phone' => 'nullable',
            'role' => 'required',
            'shift' => 'nullable',
            'unit' => 'required',
            'department' => 'required',
            'employee_group' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ✅ add validation for image
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $attachment = fileUpload($request->file('image'), 'profile_images'); // Custom image upload function
            $validated['profile_image'] = $attachment;
        }
        $validated['designation'] = Role::where('id',$request->role)->value('name');

        if($request->password != ''){
            $validated['password'] = Hash::make($request->password);
        }
        // Update user details
        $user->update($validated);

        // Redirect with success message
        return redirect()
            ->route('admin.user.edit', $user->id)
            ->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

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

    public function generateQR()
    {
        $passSheet = PassSheet::whereBetween('id', ['122', '135'])->get();

        foreach ($passSheet as $sheet) {
            // Generate the QR code
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
            PassSheet::where('id', $sheet->id)->update(['pass_sheet_qr_code' => $name]);
        }

        exit;

        $operations = DB::table('operation_masters')->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($operations as $operation) {
            // Insert the machine record and get its IDph
            $jsonData = ['id' => $operation->id, 'type' => 'operation', 'operation_name' => $operation->operation_name];

            $jsonString = json_encode($jsonData);

            // Generate the QR code
            $result = Builder::create()
                ->data($operation->id)
                ->size(300) // Set size in pixels
                ->margin(10) // Set margin in pixels
                ->build();

            $name = $operation->id . '-' . time() . '.png';

            // Path where you want to save the QR code image
            $path = 'operation-qr-codes/' . $name; // unique filename

            // Save the QR code image to storage (public disk)
            Storage::disk('public')->put($path, $result->getString());

            // Update the machine record with the QR code path
            DB::table('operation_masters')->where('id', $operation->id)->update(['operation_qr_code' => $name]);
        }
        exit;

        $passSheet = PassSheet::all();

        foreach ($passSheet as $sheet) {
            // Generate the QR code
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
            PassSheet::where('id', $sheet->id)->update(['pass_sheet_qr_code' => $name]);
        }

        exit;
        
        $salesOrderProduct = SalesOrderProduct::select('cpoitemid')->where(['scr_status' => 'Scrutinized', 'measureunit' => 'SET'])->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($salesOrderProduct as $so_products) {

            $product_response = $this->callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $so_products->cpoitemid);

            // $itemjson = json_decode($product_response, true);
            $itemjson = $product_response->json();

            if (!empty($itemjson)) {

                foreach ($itemjson as $item) {

                    DB::table('pass_sheets')->insert([
                        'cpoitemid'     => $item['cpoitemid'],
                        'sr_no'         => $item['sr_no'],
                        'pass_no'       => $item['pass_no'],
                        'mrk_pass_no'   => $item['mrk_pass_no'],
                        'drawing_no'    => $item['drawing_no'],
                        'size1'         => $item['size1'],
                        'size2'         => $item['size2'],
                        'size3'         => $item['size3'],
                        'qty'           => $item['qty'],
                        'material'      => $item['material'],
                        'hardness'      => $item['hardness'],
                        'fin_wt'        => $item['fin_wt'],
                        'bs1_dia'       => $item['bs1_dia'],
                        'bs1_depth'     => $item['bs1_depth'],
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

        exit;
        
    }

    public function generateQRForPass()
    {

        $salesOrderProduct = SalesOrderProduct::select('cpoitemid')->where(['scr_status' => 'Scrutinized', 'measureunit' => 'SET'])->limit(15)->get();

        // Insert each machine, generate its QR code, and update the machine_qr_code field
        foreach ($salesOrderProduct as $so_products) {

            $product_response = $this->callErpApi(ERP_LINK . '/OH_showCPOItemPass/' . $so_products->cpoitemid);

            // $itemjson = json_decode($product_response, true);
            $itemjson = $product_response->json();

            if (!empty($itemjson)) {

                foreach ($itemjson as $item) {

                    // $passNos = $this->splitPassNo($item['pass_no']);

                    $passNos = $this->splitPassNo($item['pass_no']);

                    foreach ($passNos as $passNo) {

                        DB::table('pass_sheets')->insert([
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
                            'fin_wt'        => $item['fin_wt'],
                            'bs1_dia'       => $item['bs1_dia'],
                            'bs1_depth'     => $item['bs1_depth'],
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
        }
    }
 
    public function details(Request $request, $id)
    {
        // === Date Filters ===
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');
  
        /* if ($startDate && $endDate) {
            $fromDate = $startOfDay = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($endDate)->endOfDay();
        } else if ($startDate) {
            $fromDate = $startOfDay = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($startDate)->endOfDay();
        } else { echo 'elseeeeeeeeeeeee';
            // default last 7 days
            $startOfDay = now()->subDays(6)->startOfDay();

            // default → today
            $fromDate = today()->startOfDay();
            $toDate = today()->endOfDay();
        } */

        // === Date Filters ===
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        if ($startDate && $endDate) {
            $fromDate = $startOfDay = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($endDate)->endOfDay();

        } elseif ($startDate) {
            $fromDate = $startOfDay = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($startDate)->endOfDay();

        } else {
            // default last 7 days (including today)
            $fromDate = $startOfDay = now()->subDays(6)->startOfDay();
            $toDate   = now()->endOfDay();
        }
 
        // echo "<pre>"; 
        // print_r($fromDate);  echo "<br>"; 
        // print_r($toDate); 
        
        // exit;

        // Days count
        $dayCount = $fromDate->diffInDays($toDate) + 1;
        $plannedRuntime = 1350 * $dayCount; // in minutes
        $idealRuntime = 1440 * $dayCount; // in minutes

        // ========= Base Query =========
        $baseQuery = SalesOrderTracking::query()
            ->where('operator_id', $id)
            ->whereNotNull('end_date_time')
            ->where('roll_status', 'completed');

        // ========= SO History (detail view) ========= 
        $soHistory = (clone $baseQuery)
            ->select(
                'so_id',
                'operation_id',
                'machine_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                DB::raw('COUNT(quantity_processed) as total_quantity_processed')
            )
            ->with([
                'operation:id,operation_name',
                'soProduct:so_id,so_no',
                'machine:id,machine',
                'product:id,product_modified_name',
                'subProduct:id,sub_product_name'
            ])
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy('so_id', 'operation_id', 'machine_id', 'so_product_id', 'sub_product_id', 'pass_id')  // ✅ added missing groupBys
            ->orderBy(DB::raw('DATE(end_date_time)'),'DESC')->get();

        // Print it BEFORE ->get()
        // dd([
        //     'sql' => $soHistory->toSql(),
        //     'bindings' => $soHistory->getBindings(),
        // ]);
 
        // === Production Graph (last 7 days) ===
        // Step 1: build date range
        $period = CarbonPeriod::create($startOfDay, $toDate);
        $labels = [];
        $dates = [];
        foreach ($period as $date) {
            $key = $date->format('d M');
            $labels[] = $key;
            $dates[$key] = 0;
        }

        // Step 2: fetch data 
        $data = (clone $baseQuery)
            ->select(
                'so_id',
                'operation_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as raw_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                DB::raw('COUNT(quantity_processed) as total')
            )
            ->with(['operation:id,operation_name', 'soProduct:so_id,so_no'])
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy('so_id', 'operation_id')
            ->pluck('total', 'raw_date')
            ->toArray();

        // Step 3: re-map
        $mappedData = [];
        foreach ($data as $rawDate => $total) {
            $key = Carbon::parse($rawDate)->format('d M');
            $mappedData[$key] = (int) $total;
        }

        // Step 4: merge
        $count7Days = array_replace($dates, $mappedData);
        $count7Days = array_values($count7Days);

        $user_data = User::find($id);

        return view('operator.details', compact(
            'user_data',
            'soHistory',
            'count7Days',
            'dayCount',
            'labels',
            'fromDate',
            'toDate'
        ));
    }

}
