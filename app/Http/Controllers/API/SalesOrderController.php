<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ErpSalesOrder;
use App\Models\MachineHealthMonitoring;
use App\Models\MachineMaster;
use App\Models\MachineWiseOperation;
use App\Models\Notification;
use App\Models\PassSheet;
use App\Models\SalesOrderProduct;
use App\Models\SalesOrderTracking;
use App\Models\SOProductOperationDetails;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SalesOrderController extends Controller
{
    public function index()
    {
        $data = ErpSalesOrder::select('id', 'so_no', 'so_cpono', 'so_date', 'so_department', 'so_region', 'so_customername', 'so_contactperson')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'SO list fetched succesfully',
            'data' => $data,
        ]);
    }

    public function so_details($id)
    {
        $data = ErpSalesOrder::find($id);

        return response()->json([
            'status' => true,
            'message' => 'SO details fetched succesfully',
            'data' => $data,
        ]);
    }

    public function sales_order_summary(Request $request)
    {

        $machine_id = $request->input('machine_id');
        $so_primary_id = $request->input('so_id');
        $so_product_id = $request->input('so_product_id');

        $user = $machine = [];
        $userData = Auth::user()->load('roleName'); // eager load role relation

        if ($userData) {
            $user = [
                'id' => $userData->id,
                'name' => $userData->name,
                'profile_image' => $userData->profile_image,
                'department' => $userData->department,
                'role_name' => $userData->roleName->name ?? null, // safely access role name
                'unit' => $userData->unit ?? null, // safely access role name
                'unit_no' => $userData->unit_name ?? null, // safely access role name
                'username' => $userData->username ?? null, // safely access role name
            ];
        }

        $machine = MachineMaster::select('id', 'unit_name', 'machine', 'machine_image', 'machine_type', 'machine_status')->find($machine_id);

        $so_product_details = ErpSalesOrder::select('id', 'so_no', 'so_id')
            ->with(['soProducts' => function ($query) {
                $query->select(
                    'id',
                    'so_id',
                    'sub_product_id',
                    'item_name',
                    'soquantity as quantity',
                    'measureunit',
                    'size1',
                    'size2',
                    'size3',
                    'material',
                    'hardness'
                )->where('soquantity', '!=', 0);
            }])
            ->where('id', $so_primary_id)
            ->first();

        $parts = explode('-', $so_product_details->so_no);
        $group = implode('-', array_slice($parts, 4));
        $sizeVals = getSizeValue($group);

        foreach ($so_product_details->soProducts as $product) {
            $product->size1_label = $sizeVals[0] ?? null;
            $product->size2_label = $sizeVals[1] ?? null;
            $product->size3_label = $sizeVals[2] ?? null;
        }

        $product_details = SalesOrderProduct::select(
            'id',
            'so_id',
            'drawingno',
            'sub_product_id',
            'item_name',
            'description',
            'measureunit',
            'soquantity as quantity',
            'operation1',
            'size1',
            'size2',
            'size3',
            'material',
            'hardness'
        )->where('soquantity', '!=', 0)
            ->where('id', $so_product_id)
            ->first();

        if (! $so_product_details || ! $product_details) {
            return response()->json([
                'status' => false,
                'message' => 'SO details not found',
            ], 404);
        }

        $product_details['size1_label'] = $sizeVals[0];
        $product_details['size2_label'] = $sizeVals[1];
        $product_details['size3_label'] = $sizeVals[2];

        return response()->json([
            'status' => true,
            'message' => 'Product details',
            'data' => ['user' => $user, 'machine' => $machine, 'so_details' => $so_product_details, 'product_details' => $product_details],
        ], 200);

        return response()->json([
            'status' => false,
            'message' => 'Unauthorized',
        ], 401);
    }

    public function scan_process(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'operation_id' => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $so_product_id = $request->input('so_product_id');
        $operation_id = $request->input('operation_id');

        $mdata = SOProductOperationDetails::where([
            'operation_id' => $operation_id,
            'sales_order_product_id' => $so_product_id,
        ])->first();

        if (! $mdata) {
            return response()->json([
                'status' => false,
                'message' => 'No details found',
                'data' => [],
            ], 404);
        }

        $data['so_product_id'] = $mdata->sales_order_product_id;
        $data['so_id'] = $mdata->so_id;
        $data['operation_id'] = $mdata->operation_id;
        $data['operation_name'] = $mdata->operation_name;

        return response()->json([
            'status' => true,
            'message' => 'QR code details',
            'data' => $data,
        ], 200);
    }

    public function OPERATIONSTOP($t_id, $reason)
    {
        $getData = SalesOrderTracking::find($t_id);

        if (! empty($getData)) {
            $start = new DateTime($getData->start_date_time);
            $end = new DateTime; // gets current date & time

            $durationInSeconds = $end->getTimestamp() - $start->getTimestamp();

            $data = [
                'reason' => $reason,
                'roll_status' => 'pending',
                'end_date_time' => $end->format('Y-m-d H:i:s'),
                'time_taken' => $durationInSeconds,
                'updated_at' => date('Y-m-d h:i:s'),
            ];

            SalesOrderTracking::where('id', $t_id)->update($data);
        }
    }

    public function update_machine_status(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'machine_id' => 'required|integer|exists:machine_master,id',
            'status' => 'required|in:maintenance,breakdown,active',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get inputs
        $machine_id = $request->input('machine_id');
        $status = $request->input('status');

        // Get machine
        $machine = MachineMaster::find($machine_id);

        if (! $machine) {
            return response()->json([
                'status' => false,
                'message' => 'Machine not found',
            ], 404);
        }

        // 🚫 Check if machine is already in the same status
        if ($machine->machine_status === $status && in_array($status, ['maintenance', 'breakdown'])) {
            return response()->json([
                'status' => false,
                'message' => "Machine is already in $status mode.",
            ], 409);
        }

        // 🚫 Check if machine is running in any operation
        $checkMachineStatus = SalesOrderTracking::where('machine_id', $machine_id)
            ->whereNull('end_date_time')
            ->whereNotNull('start_date_time')
            ->latest('id')
            ->first();

        if ($checkMachineStatus) {
            return response()->json([
                'status' => false,
                'message' => 'Machine is working on Roll, please stop the roll first!',
            ], 409);
        }

        // ✅ Update end_time for previous maintenance/breakdown when status becomes active
        if (
            in_array($machine->machine_status, ['maintenance', 'breakdown']) &&
            $status === 'active'
        ) {
            $lastEntry = MachineHealthMonitoring::where('master_id', $machine_id)
                ->whereNull('end_date_time')
                ->latest('id')
                ->first();

            if ($lastEntry) {
                $lastEntry->end_date_time = date('Y-m-d H:i:s');
                $lastEntry->save();
            }
        }

        // ✅ Insert new monitoring entry if entering maintenance or breakdown
        if (in_array($status, ['maintenance', 'breakdown'])) {
            MachineHealthMonitoring::create([
                'master_id' => $machine_id,
                'start_date_time' => date('Y-m-d H:i:s'),
                'monitor_for' => $status,
            ]);
        }

        // ✅ Update machine status
        $machine->machine_status = $status;

        $machineName = \App\Helpers\MyHelper::getMachineName($machine_id);
        $machineUnit = \App\Helpers\MyHelper::getMachineUnit($machine_id);

        // Convert unit number to Roman numeral
        $unitRoman = $this->convertToRoman($machineUnit['unit_number']);

        switch ($status) {
            case 'maintenance':
                $message = $machineName . ' machine in ' . $machineUnit['unit_name'] . ' Unit (' . $unitRoman . ') is Under Electrical Maintenance';
                break;

            case 'breakdown':
                $message = $machineName . ' machine in ' . $machineUnit['unit_name'] . ' Unit (' . $unitRoman . ') has broken down';
                break;

            case 'active': // or 'repaired', depending on your status naming
                $message = $machineName . ' machine in ' . $machineUnit['unit_name'] . ' Unit (' . $unitRoman . ') is now operational!';
                break;

            default:
                $message = $machineName . ' machine in ' . $machineUnit['unit_name'] . ' Unit (' . $unitRoman . ') status updated';
                break;
        }

        // add in notification
        Notification::create([
            'machine_id' => $machine_id,
            'title' => 'Machine ' . $status,
            'message' => $message,
            'type' => 'Machine',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if ($machine->save()) {
            return response()->json([
                'status' => true,
                'message' => "Machine put on $status mode",
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Machine status not updated',
            ], 200);
        }
    }

    public function convertToRoman($number)
    {
        return match ((int) $number) {
            1 => 'I',
            2 => 'II',
            3 => 'III',
            default => 'I' // fallback
        };
    }

    public function task_submit1(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'operation_id' => 'required|integer|exists:operation_masters,id',
            'current_roll_no' => 'nullable|integer',
            'so_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Inputs
        $so_id = $request->input('so_id');
        $so_product_id = $request->input('so_product_id');
        $pass_id = $request->input('pass_id');
        $operation_id = $request->input('operation_id');
        $current_roll_no = $request->input('current_roll_no');
        $reason = 'completed';

        $so_product_details = SalesOrderProduct::select('id', 'so_id', 'sub_product_id', 'product_id', 'item_name')
            ->find($so_product_id);

        $getDetails = SOProductOperationDetails::where([
            'so_id' => $so_product_details->so_id,
            'sales_order_product_id' => $so_product_id,
            'product_id' => $so_product_details->product_id,
            'sub_product_id' => $so_product_details->sub_product_id,
            'operation_id' => $operation_id,
        ])->first();

        // Fetch tracking row
        $query = SalesOrderTracking::where('so_pid_primary', $so_product_id)
            ->where('operation_id', $operation_id)
            ->where('quantity_processed', $current_roll_no);

        if (is_numeric($pass_id)) {
            $query->where('pass_id', (int) $pass_id);
        } else {
            $query->whereNull('pass_id');
        }

        $getData = $query->orderBy('id', 'desc')->first();

        // ✅ If no tracking found
        if (! $getData) {
            return response()->json([
                'status' => false,
                'message' => 'No details found',
                'data' => [],
            ], 404);
        }

        // ✅ Prevent double submission
        if ($getData->roll_status === 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Already completed',
                'data' => [],
            ], 404);
        }   

        // ✅ Get ideal cycle & total actual time
        $getActualIdeal = SalesOrderTracking::where([
            'so_id' => $so_product_details->so_id,
            'so_pid_primary' => $so_product_id,
            'operation_id' => $operation_id,
        ])->selectRaw('ideal_cycle_time, id, SUM(time_taken) as total_time_taken')
            ->groupBy('id', 'ideal_cycle_time')
            ->first();

        // ✅ Only if tracking row exists AND ideal cycle is available
        if ($getActualIdeal && $getActualIdeal->ideal_cycle_time !== 'NA') {

            $ideal = (float) $getActualIdeal->ideal_cycle_time;

            // =========== Send Notification ===========
            if ($ideal > 0) {

                // ==================
                $query = SalesOrderTracking::where([
                    'operation_id'   => $operation_id,
                    'so_id'          => $so_product_details->so_id,
                    'so_pid_primary' => $so_product_id,
                ])->when($pass_id !== null, function ($q) use ($pass_id) {
                    $q->where('pass_id', $pass_id);
                });

                $getOperationData = $query
                    ->orderBy('quantity_processed')
                    ->get();

                if (!$getOperationData) {
                    return;
                }

                if (!empty($getOperationData) && count($getOperationData) > 0)
                    foreach ($getOperationData as $operation)

                        $actualCT = 0;
                $actualCT += $operation->time_taken;


                // Calculate total time taken for this Roll (quantity_processed)
                $totalTimeForRoll = $operation
                    ->where('quantity_processed', $operation->quantity_processed)
                    ->sum('time_taken');

                // Format the total time (assuming time_taken is in seconds)
                $hours = floor($totalTimeForRoll / 3600);
                $minutes = floor(($totalTimeForRoll % 3600) / 60);
                $seconds = $totalTimeForRoll % 60;
                $formattedTotal = sprintf(
                    '%02dh %02dm %02ds',
                    $hours,
                    $minutes,
                    $seconds,
                );

                $idealCycleTimeMinutes = is_numeric($operation->ideal_cycle_time)
                    ? (float) $operation->ideal_cycle_time
                    : 0;
                // in minutes
                $actualCycleTimeMinutes = $totalTimeForRoll / 60; // in minutes

                $ctEfficiency = 0;

                if ($operation->ideal_cycle_time === 'NA') {
                    $ctEfficiency = 'NA';
                } else {
                    $idealCycleTimeMinutes = (float) $operation->ideal_cycle_time;
                    $actualCycleTimeMinutes = (float) ($totalTimeForRoll / 60);

                    $ctEfficiency =
                        $actualCycleTimeMinutes > 0
                        ? round(
                            $idealCycleTimeMinutes / $actualCycleTimeMinutes,
                            2,
                        )
                        : 0;
                }

                // Convert ideal cycle time (minutes) to seconds
                $idealTimeInSeconds = (float) $idealCycleTimeMinutes * 60;

                // Difference: + = exceeded, - = faster
                $gapInSeconds = (int) $totalTimeForRoll - (int) $idealTimeInSeconds;

                $absGap = abs($gapInSeconds);
                $hours = intdiv($absGap, 3600);
                $minutes = intdiv($absGap % 3600, 60);
                $seconds = $absGap % 60;

                $formattedGap = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                if ($gapInSeconds > 0) {
                    $differenceText = "Exceeded by $formattedGap";
                } elseif ($gapInSeconds < 0) {
                    $differenceText = "Faster by $formattedGap";
                } else {
                    $differenceText = "On target";
                }

                /* ===========================
                    FINAL MESSAGE
                    =========================== */

                $so_no        = $getDetails->so_no;
                $operationName = $getDetails->operation_name;
                $product      = $so_product_details->item_name;

                $message = "Ideal Cycle Time {$differenceText}, SO No: {$so_no}, Product: {$product}, Operation: {$operationName}.";

                Notification::create([
                    'machine_id'   => $getActualIdeal->id ?? 0,
                    'title'        => 'Ideal Cycle Time Alert',
                    'message'      => $message,
                    'type'         => 'ICT',
                    'gapInSeconds' => $gapInSeconds,
                    'created_at'   => now(),
                ]);
            }
        }

        // ✅ Calculate time taken
        $start = Carbon::parse($getData->start_date_time);
        $end = Carbon::now();
        $durationInSeconds = $end->diffInSeconds($start);

        // ✅ Update tracking entry
        $getData->update([
            'reason' => $reason,
            'roll_status' => 'completed',
            'end_date_time' => $end,
            'time_taken' => $durationInSeconds,
            'updated_at' => now(),
        ]);

        // ✅ Update processed qty
        $processed_qty = min(((int) $getDetails->processed_qty) + 1, (int) $getDetails->qty);
        $final_status = ($processed_qty >= $getDetails->qty) ? 'completed' : 'pending';

        $getDetails->update([
            'completed_qty' => $processed_qty,
            'final_status' => $final_status,
            'process_status' => 'completed',
            'updated_at' => now(),
        ]);

        // ✅ Update nested JSON processed_qty
        if ($getDetails && $getDetails->processed_qty) {

            $processedQty = json_decode($getDetails->processed_qty, true);
            $updated = false;

            foreach ($processedQty as &$item) {
                $itemPassId = (int) ($item['pass_sheet_id'] ?? 0);
                $itemQty = (int) ($item['quantity'] ?? 0);
                $itemStatus = strtolower($item['status'] ?? '');

                if ($itemStatus === 'partial') {
                    if ($pass_id > 0) {
                        if ($itemPassId === (int) $pass_id && $itemQty === (int) $current_roll_no) {
                            $item['status'] = 'completed';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $item['completed_date'] = now()->format('Y-m-d H:i:s');
                            $updated = true;
                            break;
                        }
                    } else {
                        if ($itemQty === (int) $current_roll_no) {
                            $item['status'] = 'completed';
                            $item['updated_by'] = auth()->id() ?? 0;
                            $item['completed_date'] = now()->format('Y-m-d H:i:s');
                            $updated = true;
                            break;
                        }
                    }
                }
            }

            if ($updated) {
                $allCompleted = collect($processedQty)->every(fn($item) => strtolower($item['status']) === 'completed');

                if ($allCompleted) {
                    $getDetails->final_status = 'completed';
                }

                $getDetails->completed_qty += 1;
                $getDetails->processed_qty = json_encode($processedQty);
                $getDetails->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Operation Status Updated',
        ], 200);
    }

    public function operation_start(Request $request)
    {
        try {
            // -------------------------------
            // 1️⃣ Validate input
            // -------------------------------
            $validator = Validator::make($request->all(), [
                'so_id' => 'required|integer|exists:erp_sales_orders,id',
                'machine_id' => 'required|integer|exists:machine_master,id',
                'so_product_id' => 'required|integer|exists:sales_order_products,id',
                'pass_id' => 'nullable|integer',
                'operation_id' => 'required|integer|exists:operation_masters,id',
                'current_roll_no' => 'required|integer',
            ]);

            if ($validator->fails()) {
                Log::warning('operation_start - validation failed', ['errors' => $validator->errors()]);

                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // -------------------------------
            // 2️⃣ Auth Check
            // -------------------------------
            $userData = Auth::user();
            if (! $userData) {
                Log::error('operation_start - unauthenticated');

                return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
            }

            $userData->load('roleName');
            $operator_id = $userData->id;

            // -------------------------------
            // 3️⃣ Fetch Sales Order
            // -------------------------------
            $so_id = $request->input('so_id');
            $so_details = ErpSalesOrder::find($so_id);
            if (! $so_details) {
                Log::error('operation_start - sales order not found', ['so_id' => $so_id]);

                return response()->json(['status' => false, 'message' => 'Sales order not found.'], 404);
            }

            // -------------------------------
            // 4️⃣ Fetch Sales Order Product
            // -------------------------------
            $so_product_id = $request->input('so_product_id');
            $so_product_details = SalesOrderProduct::select('id', 'so_id', 'poquantity', 'sub_product_id', 'product_id', 'measureunit')
                ->find($so_product_id);

            if (! $so_product_details) {
                Log::error('operation_start - so_product not found', ['so_product_id' => $so_product_id]);

                return response()->json(['status' => false, 'message' => 'Sales order product not found.'], 404);
            }

            // -------------------------------
            // 5️⃣ Operation Mapping
            // -------------------------------
            $operation_id = $request->input('operation_id');
            $pass_id = $request->input('pass_id');
            $current_roll = $request->input('current_roll_no');

            $getDetails = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id,
            ])->first();

            if (! $getDetails) {
                Log::error('operation_start - operation not mapped', ['operation_id' => $operation_id]);

                return response()->json(['status' => false, 'message' => 'This operation detail is not mapped with SO.'], 404);
            }

            // -------------------------------
            // 6️⃣ Check existing tracking
            // -------------------------------
            $existingTracking = SalesOrderTracking::where([
                'sub_product_id' => $so_product_details->sub_product_id,
                'so_product_id' => $so_product_details->product_id,
                'pass_id' => is_numeric($pass_id) ? (int) $pass_id : null,
                'operation_id' => $operation_id,
                'quantity_processed' => $current_roll,
                'so_pid_primary' => $so_product_id,
            ])->orderBy('id', 'desc')->first();

            if (! empty($existingTracking)) {
                if ($existingTracking->roll_status == 'completed') {
                    Log::warning('operation_start - already completed', ['tracking_id' => $existingTracking->id]);

                    return response()->json(['status' => false, 'message' => 'Operation is already completed.'], 409);
                }

                if (! empty($existingTracking->start_date_time) && empty($existingTracking->end_date_time)) {
                    Log::warning('operation_start - already in-progress', ['tracking_id' => $existingTracking->id]);

                    return response()->json(['status' => false, 'message' => 'Operation is already in-progress.'], 409);
                }
            }

            // -------------------------------
            // 7️⃣ Machine availability
            // -------------------------------
            $machine_id = $request->input('machine_id');
            $machine = MachineMaster::select('id', 'unit_name', 'machine', 'machine_image', 'machine_type', 'machine_status')
                ->find($machine_id);

            if (! $machine) {
                Log::error('operation_start - machine not found', ['machine_id' => $machine_id]);

                return response()->json(['status' => false, 'message' => 'Invalid machine QR code scanned.'], 404);
            }

            $message = match ($machine->machine_status) {
                'in-working' => 'Machine is already assigned and currently in use.',
                'maintenance' => 'Machine is in maintenance mode.',
                'breakdown' => 'Machine is currently not operational.',
                default => null
            };

            if ($message) {
                Log::warning('operation_start - machine unavailable', ['message' => $message]);

                return response()->json(['status' => false, 'message' => $message], 423);
            }

            // -------------------------------
            // 8️⃣ Machine-operation check
            // -------------------------------
            $operationIds = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id,
            ])->pluck('operation_id')->toArray();

            $getOperationsIds = MachineWiseOperation::where('machine_id', $machine->id)
                ->whereIn('operation_id', $operationIds)
                ->pluck('operation_id');

            if ($getOperationsIds->isEmpty()) {
                Log::error('operation_start - operation not allowed on machine', ['machine_id' => $machine_id, 'operation_id' => $operation_id]);

                return response()->json(['status' => false, 'message' => 'This operation cannot be performed.'], 400);
            }

            // ---------------------------------
            // 9️⃣ Get ideal cycle time (helper)
            // ---------------------------------
            $ict = 'NA';

            try {
                if ($so_details->industry === 'Tooling') {
                    $ict = \App\Helpers\MyHelper::getCycleTimeForTooling($operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                } elseif ($so_details->industry === 'RMR') {
                    $ict = \App\Helpers\MyHelper::getCycleTimeForRMR($operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                } elseif ($so_details->industry === 'TMR') {
                    $ict = \App\Helpers\MyHelper::getCycleTimeForTMR($operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id, $pass_id);
                }
            } catch (\Exception $e) {
                Log::error('operation_start - helper failed', ['message' => $e->getMessage()]);
            }

            // -------------------------------
            // 🔟 Create Tracking Record
            // -------------------------------
            $data = [
                'so_id' => $so_product_details->so_id,
                'operator_id' => $operator_id,
                'so_product_id' => $so_product_details->product_id,
                'pass_id' => is_numeric($pass_id) ? (int) $pass_id : null,
                'machine_id' => $machine_id,
                'operation_id' => $operation_id,
                'total_quantity' => $so_product_details->poquantity,
                'sub_product_id' => $so_product_details->sub_product_id,
                'start_date_time' => now(),
                'quantity_processed' => $current_roll,
                'ideal_cycle_time' => $ict,
                'created_at' => now(),
                'so_pid_primary' => $so_product_details->id,
                'roll_status' => 'pending',
            ];

            try {
                $tracking = SalesOrderTracking::create($data);
            } catch (\Exception $e) {
                Log::error('operation_start - tracking insert failed', ['error' => $e->getMessage()]);
                return response()->json(['status' => false, 'message' => 'Failed to create tracking record.'], 500);
            }

            $lastInsertedId = $tracking->id;

            // -------------------------------
            // 11️⃣ Update machine status
            // -------------------------------
            MachineMaster::where('id', $machine_id)->update([
                'machine_status' => 'in-working',
                'operator_id' => $operator_id,
                'tracking_id' => $lastInsertedId,
            ]);

            // -------------------------------
            // 12️⃣ Update operation detail JSON
            // -------------------------------
            $operationDetails = SOProductOperationDetails::where([
                'so_id' => $so_product_details->so_id,
                'sales_order_product_id' => $so_product_id,
                'product_id' => $so_product_details->product_id,
                'sub_product_id' => $so_product_details->sub_product_id,
                'operation_id' => $operation_id,
            ])->first(['id', 'processed_qty', 'qty']);

            if ($operationDetails && $operationDetails->processed_qty) {
                $processedQty = json_decode($operationDetails->processed_qty, true);
                if (! is_array($processedQty)) {
                    $processedQty = [];
                }

                $updated = false;

                foreach ($processedQty as &$item) {
                    $item['pass_sheet_id'] = $item['pass_sheet_id'] ?? 0;
                    $item['quantity'] = $item['quantity'] ?? 0;
                    $item['status'] = $item['status'] ?? 'not-started';

                    $itemPassId = (int) $item['pass_sheet_id'];
                    $itemQty = (int) $item['quantity'];

                    if ($pass_id > 0) {
                        if (($item['status'] === 'not-started' || $item['status'] === 'partial') && $itemPassId === (int) $pass_id && $itemQty === (int) $current_roll) {
                            $item['status'] = 'in-progress';
                            $item['updated_by'] = $operator_id;
                            $updated = true;
                            break;
                        }
                    } else {
                        if (($item['status'] === 'not-started' || $item['status'] === 'partial') && $itemQty === (int) $current_roll) {
                            $item['status'] = 'in-progress';
                            $item['updated_by'] = $operator_id;
                            $updated = true;
                            break;
                        }
                    }
                }

                if ($updated) {
                    $operationDetails->processed_qty = json_encode($processedQty);
                    $operationDetails->save();
                }
            }

            // -------------------------------
            // ✅ Final Response
            // -------------------------------
            return response()->json([
                'status' => true,
                'message' => 'Operation started successfully',
                'tracking_id' => $lastInsertedId,
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Internal Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function operation_stop(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:sales_order_trackings,id',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userData = Auth::user();
        $userData->load('roleName');
        $operator_id = $userData->id;

        $id = $request->input('id');
        $reason = $request->input('reason');
        $getData = SalesOrderTracking::find($id);

        if (! empty($getData)) {

            // check operator
            if ($getData->operator_id != $operator_id) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can’t stop an operation started by another operator.',
                    'data' => null,
                ], 404);
            }

            if ($getData->reason == 'completed' || $getData->roll_status == 'completed') {
                return response()->json([
                    'status' => false,
                    'message' => 'Operation is already completed.',
                    'data' => null,
                ], 404);
            }

            if (! empty($getData->start_date_time) && ! empty($getData->end_date_time)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Operation is completed.',
                    'data' => null,
                ], 404);
            }
        }

        $start = new DateTime($getData->start_date_time);
        $end = new DateTime; // gets current date & time

        $durationInSeconds = $end->getTimestamp() - $start->getTimestamp();

        $data = [
            'reason' => $reason,
            'roll_status' => 'pending',
            'end_date_time' => $end->format('Y-m-d H:i:s'),
            'time_taken' => $durationInSeconds,
            'updated_at' => date('Y-m-d h:i:s'),
        ];

        SalesOrderTracking::where('id', $id)->update($data);

        // machine free update
        MachineMaster::where('id', $getData->machine_id)->update(['machine_status' => 'active']);

        // -------------------------------
        // Update operation detail JSON
        // -------------------------------
        $operationDetails = SOProductOperationDetails::where([
            'so_id' => $getData->so_id,
            'sales_order_product_id' => $getData->so_pid_primary,
            'sub_product_id' => $getData->sub_product_id,
            'operation_id' => $getData->operation_id,
        ])->first(['id', 'processed_qty', 'qty']);

        $pass_id = $getData->pass_id ?? 0;
        $current_roll = $getData->quantity_processed;

        if ($operationDetails && $operationDetails->processed_qty) {
            $processedQty = json_decode($operationDetails->processed_qty, true);
            if (! is_array($processedQty)) {
                $processedQty = [];
            }

            $updated = false;

            foreach ($processedQty as &$item) {
                $item['pass_sheet_id'] = $item['pass_sheet_id'] ?? 0;
                $item['quantity'] = $item['quantity'] ?? 0;
                $item['status'] = $item['status'] ?? 'in-progress';

                $itemPassId = (int) $item['pass_sheet_id'];
                $itemQty = (int) $item['quantity'];

                if ($pass_id > 0) {
                    if ($item['status'] === 'in-progress' && $itemPassId === (int) $pass_id && $itemQty === (int) $current_roll) {
                        $item['status'] = 'partial';
                        $item['updated_by'] = $operator_id;
                        $updated = true;
                        break;
                    }
                } else {
                    if ($item['status'] === 'in-progress' && $itemQty === (int) $current_roll) {
                        $item['status'] = 'partial';
                        $item['updated_by'] = $operator_id;
                        $updated = true;
                        break;
                    }
                }
            }

            if ($updated) {
                $operationDetails->processed_qty = json_encode($processedQty);
                $operationDetails->save();
            }
        }

        // ================
        return response()->json([
            'status' => true,
            'message' => 'Operation stopped successfully',
        ], 200);
    }

    public function sales_order_details(Request $request)
    {
        /** Expected input: so_id:86, machine_id:1, so_product_id:349 **/
        $machine_id = $request->input('machine_id');
        $so_product_id = $request->input('so_product_id');
        $pass_id = $request->input('pass_id');

        // ==== Fetch SO Product details ====
        $so_product_details = SalesOrderProduct::select(
            'id',
            'so_id',
            'drawingno',
            'sub_product_id',
            'item_name',
            'description',
            'measureunit',
            'soquantity as quantity',
            'operation1',
            'operation2',
            'operation3',
            'cpoitemid',
            'size1',
            'size2',
            'size3',
            'material',
            'hardness',
            'product_id',
            'sub_product_id'
        )->where('soquantity', '!=', '0')->find($so_product_id);

        if (! $so_product_details) {
            return response()->json([
                'status' => false,
                'message' => 'SO Product not found',
            ], 404);
        }

        if ($so_product_details->sub_product_id == null) {
            return response()->json([
                'status' => false,
                'message' => 'SO Sub Product not found',
            ], 404);
        }

        if ($so_product_details->measureunit === 'SET') {

            $pass_sheet = PassSheet::select('id', 'pass_no', 'pass_sheet_qr_code', 'size1', 'size2', 'size3', 'material', 'hardness', 'qty')
                ->where('cpoitemid', $so_product_details->cpoitemid)->where('so_id', $so_product_details->so_id)->get();
            $so_product_details->pass_sheet = $pass_sheet;
        } else {
            $so_product_details->pass_sheet = null;
        }

        // Get allowed operation IDs for the product
        $operationIds = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id])
            ->pluck('operation_id')->toArray();

        $getOperationsIds = MachineWiseOperation::where('machine_id', $machine_id)->whereIn('operation_id', $operationIds)->pluck('operation_id')->toArray();

        $getDetails = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id, 'sales_order_product_id' => $so_product_id])->whereIn('operation_id', $getOperationsIds)->get();

        $operations = [];

        if (! empty($getDetails)) {
            foreach ($getDetails as $details) {

                $completedCount = 0;

                if ($getDetails) {

                    $processedQty = json_decode($details->processed_qty, true);

                    if ($so_product_details->$so_product_details == 'NOS') {
                        foreach ($processedQty as $item) {
                            if (isset($item['status']) && $item['status'] === 'completed') {
                                $completedCount++;
                            }
                        }
                    } else {
                        foreach ($processedQty as $item) {
                            if (($item['status'] ?? null) === 'completed'
                                && (int) ($item['pass_sheet_id'] ?? 0) === (int) $pass_id
                            ) {
                                $completedCount++;
                            }
                        }
                    }
                }

                // ---------------------------------
                // 9️⃣ Get ideal cycle time (helper)
                // ---------------------------------
                $so_id = $request->so_id;
                $so_details = ErpSalesOrder::find($so_id);

                $parts = explode('-', $so_details->so_no);
                $group = implode('-', array_slice($parts, 4));
                $sizeVals = getSizeValue($group);
                $so_product_details['size1_label'] = $sizeVals[0];
                $so_product_details['size2_label'] = $sizeVals[1];
                $so_product_details['size3_label'] = $sizeVals[2];

                $ict = 'NA';

                try {
                    if ($so_details->industry === 'Tooling') {
                        $ict = \App\Helpers\MyHelper::getCycleTimeForTooling($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                    } elseif ($so_details->industry === 'RMR') {
                        $ict = \App\Helpers\MyHelper::getCycleTimeForRMR($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                    } elseif ($so_details->industry === 'TMR') {

                        $ict = \App\Helpers\MyHelper::getCycleTimeForTMR($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id, $pass_id ?? '');
                    }
                } catch (\Exception $e) {
                    Log::error('operation_start - helper failed', ['message' => $e->getMessage()]);
                }

                $operations[] = [
                    'id' => $details->id,
                    'operation_id' => $details->operation_id,
                    'operation_name' => $details->operation_name ?? null,
                    'completed_roll' => $completedCount,
                    'ideal_cycle_time' => $ict,
                ];
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Product details',
            'data' => [
                'so_details' => $so_product_details,
                'operations' => $operations,
            ],
        ], 200);
    }

    public function scan_pass_qr(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id' => 'required|integer|exists:pass_sheets,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $so_product_id = $request->input('so_product_id');
        $pass_id = $request->input('pass_id');
        $so_id = $request->input('so_id');
        $machine_id = $request->input('machine_id');
        $sopdata = SalesOrderProduct::find($so_product_id);
        $mdata = PassSheet::where(['id' => $pass_id, 'cpoitemid' => $sopdata->cpoitemid])->first();

        $data = [];

        if (! $mdata || ! $sopdata) {
            return response()->json([
                'status' => false,
                'message' => 'No details found',
                'data' => [],
            ], 404);
        }

        // adding operations=

        // Get allowed operation IDs for the product
        $so_id = ErpSalesOrder::where('id', $so_id)->value('so_id');
        $operationIds = SOProductOperationDetails::where(['so_id' => $so_id])
            ->pluck('operation_id')->toArray();

        $getOperationsIds = MachineWiseOperation::where('machine_id', $machine_id)->whereIn('operation_id', $operationIds)->pluck('operation_id')->toArray();

        $getDetails = SOProductOperationDetails::where(['so_id' => $so_id, 'sales_order_product_id' => $so_product_id])->whereIn('operation_id', $getOperationsIds)->get();

        $operations = [];

        if (! empty($getDetails)) {
            foreach ($getDetails as $details) {

                $completedCount = 0;

                if ($getDetails) {

                    $processedQty = json_decode($details->processed_qty, true);

                    foreach ($processedQty as $item) {
                        if (($item['status'] ?? null) === 'completed'
                            && (int) ($item['pass_sheet_id'] ?? 0) === (int) $pass_id
                        ) {
                            $completedCount++;
                        }
                    }
                }

                // ---------------------------------
                // 9️⃣ Get ideal cycle time (helper)
                // ---------------------------------
                $so_id = $request->so_id;
                $so_details = ErpSalesOrder::find($so_id);

                $parts = explode('-', $so_details->so_no);
                $group = implode('-', array_slice($parts, 4));
                $sizeVals = getSizeValue($group);
                $so_product_details['size1_label'] = $sizeVals[0];
                $so_product_details['size2_label'] = $sizeVals[1];
                $so_product_details['size3_label'] = $sizeVals[2];

                $ict = 'NA';

                /* try {
                    if ($so_details->industry === 'Tooling') {
                        $ict = \App\Helpers\MyHelper::getCycleTimeForTooling($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                    } elseif ($so_details->industry === 'RMR') {
                        $ict = \App\Helpers\MyHelper::getCycleTimeForRMR($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id);
                    } elseif ($so_details->industry === 'TMR') {

                        $ict = \App\Helpers\MyHelper::getCycleTimeForTMR($details->operation_id, $so_details->id, $so_product_details->product_id, $so_product_details->sub_product_id, $machine_id, $pass_id ?? '');
                    }
                } catch (\Exception $e) {
                    Log::error('operation_start - helper failed', ['message' => $e->getMessage()]);
                } */

                $operations[] = [
                    'id' => $details->id,
                    'operation_id' => $details->operation_id,
                    'operation_name' => $details->operation_name ?? null,
                    'completed_roll' => $completedCount,
                    'ideal_cycle_time' => $ict,
                ];
            }
        }

        // ==================
        $data['so_product_id'] = $sopdata->id;
        $data['so_id'] = $sopdata->so_id;
        $data['pass_id'] = $mdata->id;
        $data['pass_no'] = $mdata->pass_no;
        $data['operations'] = $operations;

        return response()->json([
            'status' => true,
            'message' => 'QR code details',
            'data' => $data,
        ], 200);
    }

    public function fetch_operation_details(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id' => 'nullable|integer',
            'current_roll_no' => 'required|integer',
            'operation_id' => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get inputs
        $so_product_id = $request->input('so_product_id');
        $pass_id = $request->input('pass_id');
        $operation_id = $request->input('operation_id');
        $roll_no = $request->input('current_roll_no');

        $so_product_details = SalesOrderProduct::select('id', 'so_id', 'sub_product_id', 'product_id')->find($so_product_id);
        $mdata = SOProductOperationDetails::where(['so_id' => $so_product_details->so_id, 'sales_order_product_id' => $so_product_id, 'product_id' => $so_product_details->product_id, 'sub_product_id' => $so_product_details->sub_product_id, 'operation_id' => $operation_id])->first();

        if (empty($mdata)) {
            return response()->json([
                'status' => false,
                'message' => 'operation details not found',
                'data' => ['details' => []],
            ], 404);
        }

        $userData = Auth::user()->load('roleName');

        // Fetch operation tracking data
        $getData = $query = SalesOrderTracking::where([
            'so_product_id' => $mdata->product_id,
            'sub_product_id' => $mdata->sub_product_id,
            'pass_id' => is_numeric($pass_id) ? (int) $pass_id : null,
            'operation_id' => $operation_id,
            'quantity_processed' => $roll_no,
            'so_id' => $mdata->so_id,
            'so_pid_primary' => $mdata->sales_order_product_id,
        ])->orderBy('id', 'desc')->get();

        if ($getData->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No details found',
                'data' => ['details' => []],
            ], 404);
        }

        $getTrackingData = SalesOrderTracking::where([
            'so_product_id' => $mdata->product_id,
            'sub_product_id' => $mdata->sub_product_id,
            'pass_id' => is_numeric($pass_id) ? (int) $pass_id : null,
            'operation_id' => $operation_id,
            'quantity_processed' => $roll_no,
            'so_id' => $mdata->so_id,
            'so_pid_primary' => $mdata->sales_order_product_id,
        ])->orderBy('id', 'desc')->first();

        if (! empty($getTrackingData->start_date_time) && empty($getTrackingData->end_date_time)) {
            if ($getTrackingData->operator_id != $userData->id) {
                return response()->json(['status' => false, 'message' => 'Already in progress by another operator.'], 409);
            }
        }

        // Modify time_taken to formatted H M S
        foreach ($getData as $datum) {
            if (is_numeric($datum->time_taken)) {
                $seconds = (int) $datum->time_taken;

                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $remainingSeconds = $seconds % 60;

                $datum->time_taken_formatted = "{$hours}h {$minutes}m {$remainingSeconds}s";
            } else {
                $datum->time_taken_formatted = '0h 0m 0s';
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Operation details',
            'data' => ['details' => $getData],
        ], 200);
    }

    public function get_processed_rolls(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_product_id' => 'required|integer|exists:sales_order_products,id',
            'pass_id' => 'nullable|integer',
            'operation_id' => 'required|integer|exists:operation_masters,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $so_product_id = $request->so_product_id; // primary id of so products
        $pass_id = $request->pass_id;
        $operation_id = $request->operation_id;

        // Get one instance with required fields and processed_qty
        $operationDetail = SOProductOperationDetails::select(
            'id',
            'so_id',
            'sales_order_product_id',
            'sub_product_id',
            'operation_id',
            'operation_name',
            'processed_qty'
        )
            ->where([
                'sales_order_product_id' => $so_product_id,
                'operation_id' => $operation_id,
            ])
            ->orderByDesc('id')
            ->first();

        if (! $operationDetail) {
            return response()->json([
                'status' => false,
                'message' => 'No details found',
                'data' => [
                    'total_roll' => 0,
                    'details' => [],
                    'roll_details' => [],
                ],
            ], 404);
        }

        $so_product_details = SalesOrderProduct::select('measureunit')->find($so_product_id);

        // Decode processed_qty JSON
        $processedQty = json_decode($operationDetail->processed_qty, true) ?? [];

        // If pass_id is provided, return the matching roll directly
        if (! empty($pass_id) && $so_product_details->measureunit == 'SET') {

            $get_pass_id = PassSheet::find($pass_id);

            $processedDetails = collect($processedQty)
                ->filter(fn($item) => $item['pass_sheet_id'] == $get_pass_id->id)
                ->map(function ($item) {
                    return [
                        'pass_sheet_id' => $item['pass_sheet_id'],
                        'roll_no' => $item['quantity'] ?? null,
                        'roll_status' => $item['status'] ?? null,
                    ];
                })->values()->toArray();
        } else {
            // Build roll details array
            $processedDetails = collect($processedQty)->map(function ($item) {
                return [
                    'roll_no' => $item['quantity'] ?? null,
                    'roll_status' => $item['status'] ?? null,
                ];
            })->toArray();
        }

        return response()->json([
            'status' => true,
            'message' => 'Operation details',
            'data' => [
                'total_roll' => count($processedDetails),
                'roll_details' => $processedDetails,
            ],
        ]);
    }

    public function task_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'so_product_id'   => 'required|integer|exists:sales_order_products,id',
            'operation_id'    => 'required|integer|exists:operation_masters,id',
            'current_roll_no' => 'nullable|integer',
            'so_id'           => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation Error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $so_id          = $request->so_id;
        $so_product_id  = $request->so_product_id;
        $operation_id   = $request->operation_id;
        $current_roll_no = $request->current_roll_no;
        $pass_id        = $request->pass_id;

        $so_product_details = SalesOrderProduct::find($so_product_id);

        if (!$so_product_details) {
            return response()->json(['status' => false, 'message' => 'Invalid Product'], 404);
        }

        $getDetails = SOProductOperationDetails::where([
            'so_id'                  => $so_product_details->so_id,
            'sales_order_product_id' => $so_product_id,
            'product_id'             => $so_product_details->product_id,
            'sub_product_id'         => $so_product_details->sub_product_id,
            'operation_id'           => $operation_id,
        ])->first();

        if (!$getDetails) {
            return response()->json(['status' => false, 'message' => 'Operation not found'], 404);
        }

        // ================= FETCH CURRENT TRACKING ROW =================

        $trackingQuery = SalesOrderTracking::where([
            'so_pid_primary'     => $so_product_id,
            'operation_id'       => $operation_id,
            'quantity_processed' => $current_roll_no,
        ]);

        if (is_numeric($pass_id)) {
            $trackingQuery->where('pass_id', (int) $pass_id);
        } else {
            $trackingQuery->whereNull('pass_id');
        }

        $tracking = $trackingQuery->orderByDesc('id')->first();

        if (!$tracking) {
            return response()->json([
                'status'  => false,
                'message' => 'Tracking record not found',
            ], 404);
        }
 
        // ✅ Prevent double submission
        if ($tracking->roll_status === 'completed') {
            return response()->json([
                'status' => false,
                'message' => 'Already completed',
                'data' => [],
            ], 404);
        } 
 
        // ================= CALCULATE DURATION =================

        $start = Carbon::parse($tracking->start_date_time);
        $end   = Carbon::now();
        $durationInSeconds = $end->diffInSeconds($start);

        $tracking->update([
            'reason'        => 'completed',
            'roll_status'   => 'completed',
            'end_date_time' => $end,
            'time_taken'    => $durationInSeconds,
            'updated_at'    => now(),
        ]);

        // ================= CALCULATE TOTAL TIME FOR THIS ROLL =================

        $totalTimeForRoll = SalesOrderTracking::where([
            'so_id'              => $so_product_details->so_id,
            'so_pid_primary'     => $so_product_id,
            'operation_id'       => $operation_id,
            'quantity_processed' => $current_roll_no,
        ])
            ->when($pass_id !== null, function ($q) use ($pass_id) {
                $q->where('pass_id', $pass_id);
            })
            ->sum('time_taken');

        // ================= CYCLE TIME CALCULATION =================

        if (
            $tracking->ideal_cycle_time !== 'NA' &&
            is_numeric($tracking->ideal_cycle_time)
        ) {

            $idealSeconds  = (float) $tracking->ideal_cycle_time * 60;
            $actualSeconds = (int) $totalTimeForRoll;

            $gapInSeconds = $actualSeconds - $idealSeconds;

            $absGap  = abs($gapInSeconds);
            $hours   = intdiv($absGap, 3600);
            $minutes = intdiv($absGap % 3600, 60);
            $seconds = $absGap % 60;

            $formattedGap = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

            if ($gapInSeconds > 0) {
                $differenceText = "Exceeded by $formattedGap";
            } elseif ($gapInSeconds < 0) {
                $differenceText = "Faster by $formattedGap";
            } else {
                $differenceText = "On target";
            }

            $message = "Ideal Cycle Time {$differenceText}, SO No: {$getDetails->so_no}, Product: {$so_product_details->item_name}, Operation: {$getDetails->operation_name}.";

            Notification::create([
                'machine_id'   => $tracking->machine_id ?? 0,
                'title'        => 'Ideal Cycle Time Alert',
                'message'      => $message,
                'type'         => 'ICT',
                'gapInSeconds' => $gapInSeconds,
                'created_at'   => now(),
            ]);
        }

        // ================= UPDATE QTY STATUS =================

        $processed_qty = min(
            ((int) $getDetails->completed_qty) + 1,
            (int) $getDetails->qty
        );

        $final_status = ($processed_qty >= $getDetails->qty)
            ? 'completed'
            : 'pending';

        $getDetails->update([
            'completed_qty' => $processed_qty,
            'final_status'  => $final_status,
            'process_status' => 'completed',
            'updated_at'    => now(),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Operation Status Updated Successfully',
        ], 200);
    }
}
