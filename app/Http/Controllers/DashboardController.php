<?php

namespace App\Http\Controllers;

use App\Models\{MachineMaster, ErpSalesOrder, User, MachineHealthMonitoring, SalesOrderTracking, SOProductOperationDetails};
use Illuminate\Support\Facades\{Auth, DB};
use Illuminate\Http\Request;
use Carbon\{Carbon, CarbonPeriod};

class DashboardController extends Controller
{
    public function index1(Request $request)
    {
        $total_machines  = MachineMaster::count();
        $sales_order = ErpSalesOrder::count();
        $operators = User::where('role', 'operator')->count();
        $unit   = $request->input('unit'); // e.g., 'TMR', 'RMR'
        $unitId = null;

        // Map unit name to unit_id for SalesOrderTracking (if needed)
        if ($unit) {
            $unitMap = [
                'Tooling' => 1,
                'RMR'     => 2,
                'TMR'     => 3,
            ];
            $unitId = $unitMap[$unit] ?? null;
        }

        // ==== Date Filters ===
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        if ($startDate && $endDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($endDate)->endOfDay();
        } elseif ($startDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($startDate)->endOfDay();
        } else {
            // Default last 7 days
            $fromDate = now()->subDays(9)->startOfDay();
            $toDate   = now()->endOfDay();
        }

        // ==== Machine Downtime (total) with unit filter ===
        $machineDowntimeQuery = MachineHealthMonitoring::query()
            ->join('machine_master as mm', 'machine_health_monitorings.master_id', '=', 'mm.id')
            ->whereBetween('start_date_time', [$fromDate, $toDate]);

        if ($unit) {
            $machineDowntimeQuery->where('mm.unit_name', $unit);
        }

        $machineDowntime = $machineDowntimeQuery
            ->select(DB::raw("SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW())))) as total_time"))
            ->value('total_time');

        $machineDowntime = $machineDowntime ?: '00:00:00';

        // === Base Query for Production ===
        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->where('sales_order_trackings.roll_status', 'completed');

        if ($unitId) {
            // Apply filter on the joined table
            $baseQuery->where('eso.so_unitid', $unitId);
        }

        // Step 1: Build date range
        $period = CarbonPeriod::create($fromDate, $toDate);
        $labels = [];
        $defaultMap = [];
        foreach ($period as $date) {
            $key = $date->format('d M');
            $labels[] = $key;
            $defaultMap[$key] = 0;
        }

        // Step 2: Production Data
        $productionData = (clone $baseQuery)
            ->select(
                DB::raw('DATE(sales_order_trackings.end_date_time) as date'),
                DB::raw('COUNT(quantity_processed) as total')
            )
            ->whereBetween('sales_order_trackings.end_date_time', [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(sales_order_trackings.end_date_time)'))
            ->pluck('total', 'date')
            ->toArray();

        // Remap production into date map
        $mappedProduction = [];
        foreach ($productionData as $rawDate => $total) {
            $key = Carbon::parse($rawDate)->format('d M');
            $mappedProduction[$key] = (int) $total;
        }

        $production = array_values(array_replace($defaultMap, $mappedProduction));

        // Step 3: Downtime Data
        $downtimeDataQuery = MachineHealthMonitoring::query()
            ->join('machine_master as mm', 'machine_health_monitorings.master_id', '=', 'mm.id')
            ->whereBetween('start_date_time', [$fromDate, $toDate]);

        if ($unit) {
            $downtimeDataQuery->where('mm.unit_name', $unit);
        }

        $downtimeData = $downtimeDataQuery
            ->select(
                DB::raw('DATE(start_date_time) as date'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) as total_seconds')
            )
            ->groupBy(DB::raw('DATE(start_date_time)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = Carbon::parse($row->date)->format('d M');
                return [$key => round($row->total_seconds / 60, 2)];
            })
            ->toArray();

        // Remap downtime into date map
        $values = array_values(array_replace($defaultMap, $downtimeData));

        // === FIX: Calculate machineDowntime as sum of values ===
        $machineDowntime = array_sum($values);

        $soOperations = SOProductOperationDetails::get()
            ->groupBy('so_id');

        $completedSO = $soOperations->filter(function ($operations) {
            return $operations->every(function ($operation) {
                return strtolower(trim($operation->final_status)) === 'completed';
            });
        })->count();

        return view('admin.dashboard', compact(
            'total_machines',
            'sales_order',
            'operators',
            'completedSO',
            'machineDowntime',
            'production',
            'labels',
            'values',
            'fromDate',
            'toDate'
        ));
    }

    public function profile()
    {
        $data = Auth::user();
        return view('admin.profile', compact('data'));
    }

    public function index(Request $request)
    {
        $total_machines  = MachineMaster::count();
        $sales_order = ErpSalesOrder::count();
        $operators = User::where('role', 'operator')->count();
        $unit   = $request->input('unit'); // e.g., 'TMR', 'RMR'
        $unitId = null;

        // Map unit name to unit_id for SalesOrderTracking (if needed)
        if ($unit) {
            $unitMap = [
                'Tooling' => 1,
                'RMR'     => 2,
                'TMR'     => 3,
            ];
            $unitId = $unitMap[$unit] ?? null;
        }

        // ==== Date Filters ===
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        if ($startDate && $endDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($endDate)->endOfDay();
        } elseif ($startDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate   = Carbon::parse($startDate)->endOfDay();
        } else {
            // Default last 7 days
            $fromDate = now()->subDays(9)->startOfDay();
            $toDate   = now()->endOfDay();
        }

        // ==== Machine Downtime (total) with unit filter ===
        $machineDowntimeQuery = MachineHealthMonitoring::query()
            ->join('machine_master as mm', 'machine_health_monitorings.master_id', '=', 'mm.id')
            ->whereBetween('start_date_time', [$fromDate, $toDate]);

        if ($unit) {
            $machineDowntimeQuery->where('mm.unit_name', $unit);
        }

        $machineDowntime = $machineDowntimeQuery
            ->select(DB::raw("SEC_TO_TIME(SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW())))) as total_time"))
            ->value('total_time');

        $machineDowntime = $machineDowntime ?: '00:00:00';

        // Step 1: Build date range
        $period = CarbonPeriod::create($fromDate, $toDate);
        $labels = [];
        $defaultMap = [];
        foreach ($period as $date) {
            $key = $date->format('d M');
            $labels[] = $key;
            $defaultMap[$key] = 0;
        }
  
        // SIMPLE COUNT APPROACH
        $productionData = SOProductOperationDetails::query()
            ->join('erp_sales_orders as eso', 'sales_order_product_operation_details.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_product_operation_details.operation_id')
            ->where('sales_order_product_operation_details.operation_status', 'NOT LIKE', '%outsource%')
            ->when($unitId, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            })
            ->whereBetween('sales_order_product_operation_details.updated_at', [$fromDate, $toDate])
            ->select(DB::raw('DATE(MAX(sales_order_product_operation_details.updated_at)) as completion_date'))
            ->groupBy('sales_order_product_operation_details.so_id')
            ->havingRaw('COUNT(*) = SUM(CASE WHEN final_status = "completed" THEN 1 ELSE 0 END)')
            ->get()
            ->groupBy('completion_date')
            ->map(function ($items) {
                return $items->count();
            });
 
        $mappedProduction = [];
        foreach ($productionData as $rawDate => $count) { // Changed $total to $count
            $key = Carbon::parse($rawDate)->format('d M');
            $mappedProduction[$key] = (int) $count; // Use $count here

            \Log::info("Date: $rawDate, Key: $key, Count: $count"); // Debug logging
        }

        $production = array_values(array_replace($defaultMap, $mappedProduction));

        // Step 3: Downtime Data
        $downtimeDataQuery = MachineHealthMonitoring::query()
            ->join('machine_master as mm', 'machine_health_monitorings.master_id', '=', 'mm.id')
            ->whereBetween('start_date_time', [$fromDate, $toDate]);

        if ($unit) {
            $downtimeDataQuery->where('mm.unit_name', $unit);
        }

        $downtimeData = $downtimeDataQuery
            ->select(
                DB::raw('DATE(start_date_time) as date'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) as total_seconds')
            )
            ->groupBy(DB::raw('DATE(start_date_time)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = Carbon::parse($row->date)->format('d M');
                return [$key => round($row->total_seconds / 60, 2)];
            })
            ->toArray();

        // Remap downtime into date map
        $values = array_values(array_replace($defaultMap, $downtimeData));

        // === FIX: Calculate machineDowntime as sum of values ===
        $machineDowntime = array_sum($values);

        $soOperations = SOProductOperationDetails::get()
            ->groupBy('so_id');

        $completedSO = $soOperations->filter(function ($operations) {
            return $operations->every(function ($operation) {
                return strtolower(trim($operation->final_status)) === 'completed';
            });
        })->count();

        return view('admin.dashboard', compact(
            'total_machines',
            'sales_order',
            'operators',
            'completedSO',
            'machineDowntime',
            'production',
            'labels',
            'values',
            'fromDate',
            'toDate'
        ));
    }
}
