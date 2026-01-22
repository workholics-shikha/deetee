<?php

namespace App\Http\Controllers;

use App\Models\{ErpSalesOrder, MachineHealthMonitoring, MachineMaster, MachineWiseOperation, User, ProductMasters, SalesOrderTracking};
use Illuminate\Http\Request;
use Carbon\{Carbon, CarbonPeriod};
use DB;

class MachineController extends Controller
{

    public function index(Request $request)
    {

        $count['totalMachines'] = MachineMaster::count();
        $count['machinesInWorking'] = MachineMaster::where('machine_status', 'in-working')->count();
        $count['unavailableMachines'] = MachineMaster::whereIn('machine_status', ['maintenance', 'breakdown'])->count();

        $search = $request->input('search');
        $data = MachineMaster::with('operations')
            ->when($search, function ($query, $search) {
                $query->where('machine', 'like', "%$search%");
                $query->orWhere('section', 'like', "%$search%");
                $query->orWhere('machine_type', 'like', "%$search%");
                $query->orWhere('unit_name', 'like', "%$search%");
                $query->orWhere('sub_section', 'like', "%$search%");
            })
            ->paginate(PAGE_NO);

        return view('machine.index', compact('data', 'count'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search'); // Get the search term from the request
        // Fetch machines based on the search term with pagination
        $machines = MachineMaster::with('operations')
            ->when($search, function ($query, $search) {
                $query->where('machine_qr_code', 'LIKE', "%{$search}%")
                    ->orWhere('machine_type', 'LIKE', "%{$search}%")
                    ->orWhere('machine', 'LIKE', "%{$search}%")
                    ->orWhere('machine', 'LIKE', "%{$search}%")
                    ->orWhere('unit_name', 'LIKE', "%{$search}%")
                    ->orWhere('section', 'LIKE', "%{$search}%")
                    ->orWhere('sub_section', 'LIKE', "%{$search}%");
            })
            ->paginate(PAGE_NO); // Paginate results with 10 items per page

        // Return HTML for table rows and pagination links
        return response()->json([
            'html'       => view('machine.search-table', compact('machines'))->render(),
            'pagination' => (string) $machines->links(),
        ]);
    }

    public function machineQrList()
    {
        $data = MachineMaster::all();
        return view('machine-qr-codes', compact('data'));
    }

    public function qrList()
    {
        $data['operator'] = User::where('role', 'operator')->paginate();
        $data['machines'] = MachineMaster::paginate();
        $data['products'] = ProductMasters::paginate();
        $data['sales_order'] = ErpSalesOrder::paginate();

        return view('admin/qr-codes-list', compact('data'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|exists:machine_master,id',
            'status' => 'required|in:active,maintenance,in-working,breakdown',
        ]);

        $machine = MachineMaster::find($request->machine_id);
        $machine->machine_status = $request->status;

        if ($machine->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Machine status updated successfully.',
                'data' => [
                    'id' => $machine->id,
                    'new_status' => $machine->machine_status,
                ]
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Failed to update status.'
        ], 500);
    }

    public function details_old(Request $request, $id)
    {
        // Static values
        // $startDate = '2025-09-01';
        // $endDate = '2025-09-16';

        // Filter dates (default to today if not provided)
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');

        if ($startDate && $endDate) {
            $startOfDay = $fromDate = Carbon::parse($startDate)->startOfDay();
            $endOfDay   = $toDate = Carbon::parse($endDate)->endOfDay();
            // ✅ Calculate number of days between
            $daysBetween = $dayCount = $fromDate->diffInDays($toDate) + 1; // +1 to include both start & end

        } elseif ($startDate) {
            // only start date provided → single day filter
            $startOfDay = $fromDate = Carbon::parse($startDate)->startOfDay();
            $endOfDay   = $toDate = Carbon::parse($startDate)->endOfDay();
            // ✅ Calculate number of days between
            $daysBetween = $dayCount = $fromDate->diffInDays($toDate) + 1; // +1 to include both start & end

        } else {
            // default → today
            $startOfDay = today()->startOfDay();
            $endOfDay = today()->endOfDay();

            $fromDate = now()->subDays(6)->toDateString();
            $toDate = now()->toDateString();

            // ✅ Calculate number of days between
            $daysBetween = 1; // +1 to include both start & end
            $dayCount = 7;
        }

        $plannedRuntime = 1350 * $daysBetween; // in minutes
        $idealRuntime = 1440 * $daysBetween; // in minutes

        // Top section data
        $machine = MachineMaster::find($id);
        $operations = MachineWiseOperation::with('operation')->where('machine_id', $id)->get();

        // === List === 
        $breakdowntime = MachineHealthMonitoring::where('master_id', $id)
            ->whereBetween('start_date_time', [$startOfDay, $endOfDay])
            ->limit(10)
            ->get();
  
        $soHistory = SalesOrderTracking::select(
            'so_id',
            'operation_id',
            DB::raw('MIN(start_date_time) as start_date'),
            DB::raw('MAX(end_date_time) as end_date'),
            DB::raw('SUM(time_taken)  as time_taken_minutes'),
            DB::raw('COUNT(DISTINCT quantity_processed) as total_quantity_processed')
        )
            ->where('machine_id', $id)
            ->whereBetween(DB::raw('DATE(start_date_time)'), [$fromDate, $toDate])
            ->whereNotNull('end_date_time')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('sales_order_trackings as s2')
                    ->whereColumn('s2.so_id', 'sales_order_trackings.so_id')
                    ->whereColumn('s2.operation_id', 'sales_order_trackings.operation_id')
                    ->where('s2.roll_status', 'completed');
            })
            ->with(['operation:id,operation_name', 'soProduct:so_id,so_no'])
            ->groupBy('so_id', 'operation_id')
            ->get();

        // List End ===   

        // 1. Machine Actual Run Time
        $machineActualRunTime = SalesOrderTracking::where('machine_id', $id)
            ->whereBetween('start_date_time', [$startOfDay, $endOfDay])
            ->select(DB::raw("SUM(time_taken) as total_actual_time"))
            ->first();
        $machineActualRunTime = $machineActualRunTime ? $machineActualRunTime->total_actual_time : '00:00:00';

        // Machine Downtime (HH:MM:SS format)
        $machineDowntime = MachineHealthMonitoring::where('master_id', $id)
            ->whereBetween('start_date_time', [$startOfDay, $endOfDay])
            ->select(DB::raw("SEC_TO_TIME( SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) ) as total_time"))
            ->first();
        $machineDowntime = $machineDowntime ? $machineDowntime->total_time : '00:00:00';

        // Step 1: generate full date range
        $dates = [];
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);

        while ($start->lte($end)) {
            $dates[$start->toDateString()] = 0; // default 0
            $start->addDay();
        }
 
        // Daily totals by completion date
        $data = SalesOrderTracking::selectRaw('
        DATE(end_date_time) as date,
        SUM(quantity_processed) as total,
        MAX(end_date_time) as max_date ')
            ->where('machine_id', $id)
            ->whereNotNull('end_date_time')
            ->where('roll_status', 'completed')
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(end_date_time)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->date => [
                    'total'    => (int) $row->total,   // quantity sum
                    'max_date' => $row->max_date
                ]];
            })
            ->toArray();

        // Step 3: merge results into full date range
        $count7Days = array_replace($dates, array_column($data, 'total', 'date'));
        $count7Days = $data ? array_values($count7Days) : 0;


        // == Machine Utilization in(%)
        $machineRunTime = SalesOrderTracking::where('machine_id', $id)
            ->select(DB::raw("SUM(time_taken) as total_seconds"))
            ->whereBetween(DB::raw('DATE(start_date_time)'), [$fromDate, $toDate])
            ->whereNotNull('end_date_time')
            ->first();

        $totalSeconds = $machineRunTime ? $machineRunTime->total_seconds : 0;
        $totalMinutes = round(($totalSeconds / 60), 2); // convert to minutes
        $utilization = $totalMinutes > 0 ? round(($totalMinutes / $idealRuntime), 2) : 0;

        // Runtime (OEE)
        $oeeRuntime = $totalMinutes > 0 ? round(($totalMinutes / $plannedRuntime), 2) : 0;

        // Downtime in seconds
        $machineAllDowntime = MachineHealthMonitoring::where('master_id', $id)
            ->whereBetween('start_date_time', [$fromDate, $toDate])
            ->select(DB::raw("SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) as total_seconds"))
            ->first();

        $totalDowntimeSeconds = $machineAllDowntime ? $machineAllDowntime->total_seconds : 0;
        $totalDowntimeMinutes = round($totalDowntimeSeconds / 60, 2);
        $downtime = $totalDowntimeMinutes > 0 ? round(($totalDowntimeMinutes / $plannedRuntime), 2) : 0;

        //================= 
        $period = \Carbon\CarbonPeriod::create($fromDate, $toDate);

        $labels = [];
        foreach ($period as $date) {
            $labels[] = $date->format('d M'); // e.g. 17 Sep
        }

        return view('machine.details', compact('machine', 'operations', 'soHistory', 'breakdowntime', 'machineActualRunTime', 'machineDowntime', 'count7Days', 'utilization', 'oeeRuntime', 'downtime', 'dayCount', 'labels'));
    }

    public function details(Request $request, $id)
    {
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
            // default last 7 days
            $startOfDay = now()->subDays(6)->startOfDay();

            // default → today
            $fromDate = today()->startOfDay();
            $toDate = today()->endOfDay();
        }

        // Days count
        $dayCount = $fromDate->diffInDays($toDate) + 1;
        $plannedRuntime = 1350 * $dayCount; // in minutes
        $idealRuntime = 1440 * $dayCount; // in minutes

        // === Top section data ===
        $machine = MachineMaster::find($id);
        $operations = MachineWiseOperation::with('operation')->where('machine_id', $id)->get();
        $breakdowntime = MachineHealthMonitoring::where('master_id', $id)->whereBetween('start_date_time', [$fromDate, $toDate])->get();

        // === Base Query ===
        $baseQuery = SalesOrderTracking::query()
            ->where('machine_id', $id)
            ->whereNotNull('end_date_time')
            ->where('roll_status', 'completed');

        // === SO History (detail view) ===
        $query = (clone $baseQuery)
            ->select(
                'so_id',
                'operation_id', 
                'so_product_id', 'sub_product_id', 'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                DB::raw('COUNT(quantity_processed) as total_quantity_processed')
            )
            ->whereBetween(DB::raw('DATE(end_date_time)'), [
                $fromDate->toDateString(),
                $toDate->toDateString()
            ])
            ->groupBy('so_id', 'operation_id', 'so_product_id', 'sub_product_id', 'pass_id')
            ->orderBy('end_date', 'DESC');

        // 🔍 PRINT SQL
        // dd($query->toSql(), $query->getBindings());

        // ▶ Execute AFTER debugging
        $soHistory = $query
            ->with(['operation:id,operation_name', 'soProduct:so_id,so_no'])
            ->get();


        // === Machine Actual Run Time ===
        $machineActualRunTime = SalesOrderTracking::where('machine_id', $id)
            ->whereBetween('start_date_time', [$fromDate, $toDate])
            ->sum('time_taken');

        $machineActualRunTime = $machineActualRunTime ?: '00:00:00';

        // === Machine Downtime (HH:MM:SS) ===
        $machineDowntime = MachineHealthMonitoring::where('master_id', $id)
            ->whereBetween('start_date_time', [$fromDate, $toDate])
            ->select(DB::raw("SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW())))) as total_time"))
            ->value('total_time');

        $machineDowntime = $machineDowntime ?: '00:00:00';

        // === Production Graph ===
        // Step 1: build date range
        $period = CarbonPeriod::create($fromDate, $toDate);
        $labels = [];
        $dates  = [];
        foreach ($period as $date) {
            $key = $date->format('d M'); // same format for both
            $labels[] = $key;
            $dates[$key] = 0;
        }

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

        // Step 3: re-map into same format
        $mappedData = [];
        foreach ($data as $rawDate => $total) {
            $key = Carbon::parse($rawDate)->format('d M');
            $mappedData[$key] = (int) $total;
        }

        // Step 4: merge
        $count7Days = array_replace($dates, $mappedData);
        $count7Days = array_values($count7Days);

        // === Utilization, OEE, Downtime ===
        $totalSeconds = SalesOrderTracking::where('machine_id', $id)
            ->whereNotNull('end_date_time')
            ->whereBetween(DB::raw('DATE(start_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->sum('time_taken');

        $totalMinutes = round(($totalSeconds / 60), 2);

        $utilization = $totalMinutes > 0 ? round(($totalMinutes / $idealRuntime), 2) : 0;
        $oeeRuntime  = $totalMinutes > 0 ? round(($totalMinutes / $plannedRuntime), 2) : 0;

        $totalDowntimeSeconds = MachineHealthMonitoring::where('master_id', $id)
            ->whereBetween('start_date_time', [$fromDate, $toDate])
            ->select(DB::raw("SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) as total_seconds"))
            ->value('total_seconds');

        $totalDowntimeMinutes = round($totalDowntimeSeconds / 60, 2);
        $downtime = $totalDowntimeMinutes > 0 ? round(($totalDowntimeMinutes / $plannedRuntime), 2) : 0;

        // === Return View ===
        return view('machine.details', compact(
            'machine',
            'operations',
            'soHistory',
            'breakdowntime',
            'machineActualRunTime',
            'machineDowntime',
            'count7Days',
            'utilization',
            'oeeRuntime',
            'downtime',
            'dayCount',
            'labels',
            'fromDate',
            'toDate'
        ));
    }
}
