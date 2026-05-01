<?php

namespace App\Http\Controllers;

use App\Models\ErpSalesOrder;
use App\Models\MachineHealthMonitoring;
use App\Models\MachineMaster;
use App\Models\SOProductOperationDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function profile()
    {
        $data = Auth::user();
        return view('admin.profile', compact('data'));
    }
  
    public function index(Request $request)
    {
        // ================= BASIC COUNTS =================
        $total_machines = MachineMaster::count();
        $sales_order = ErpSalesOrder::count();
        $operators = User::where('role', 'operator')->count();

        // ================= UNIT FILTER =================
        $unit = $request->input('unit');
        $unitId = null;

        if ($unit) {
            $unitMap = [
                'Tooling' => 1,
                'RMR' => 2,
                'TMR' => 3,
            ];
            $unitId = $unitMap[$unit] ?? null;
        }

        // ================= DATE FILTER =================
        $startDate = $request->input('from_date');
        $endDate = $request->input('to_date');

        if ($startDate && $endDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate = Carbon::parse($endDate)->endOfDay();
        } elseif ($startDate) {
            $fromDate = Carbon::parse($startDate)->startOfDay();
            $toDate = Carbon::parse($startDate)->endOfDay();
        } else {
            $fromDate = now()->subDays(9)->startOfDay();
            $toDate = now()->endOfDay();
        }

        // ================= DATE RANGE MAP =================
        $period = CarbonPeriod::create($fromDate, $toDate);
        $labels = [];
        $defaultMap = [];

        foreach ($period as $date) {
            $key = $date->format('d M');
            $labels[] = $key;
            $defaultMap[$key] = 0;
        }

        // ================= PRODUCTION (COMPLETED SO PER DAY) =================
        /* $productionData = SOProductOperationDetails::query()
            ->join('erp_sales_orders as eso', 'sales_order_product_operation_details.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_product_operation_details.operation_id')
            ->where('sales_order_product_operation_details.operation_status', 'NOT LIKE', '%outsource%')
            ->when($unitId, fn($q) => $q->where('eso.so_unitid', $unitId))
            ->whereBetween('sales_order_product_operation_details.updated_at', [$fromDate, $toDate])
            ->selectRaw('
            DATE(MAX(sales_order_product_operation_details.updated_at)) as completion_date,
            COUNT(DISTINCT sales_order_product_operation_details.so_id) as total
        ')
            ->groupBy(DB::raw('DATE(sales_order_product_operation_details.updated_at)'))
            ->pluck('total', 'completion_date');

        $mappedProduction = [];
        foreach ($productionData as $rawDate => $count) {
            $key = Carbon::parse($rawDate)->format('d M');
            $mappedProduction[$key] = (int) $count;
        }

        $production = array_values(array_replace($defaultMap, $mappedProduction)); */

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
        }

        $production = array_values(array_replace($defaultMap, $mappedProduction));

        // ================= DOWNTIME PER DAY (MINUTES) =================
        $downtimeQuery = MachineHealthMonitoring::query()
            ->join('machine_master as mm', 'machine_health_monitorings.master_id', '=', 'mm.id')
            ->whereBetween('start_date_time', [$fromDate, $toDate]);

        if ($unit) {
            $downtimeQuery->where('mm.unit_name', $unit);
        }

        $downtimeData = $downtimeQuery
            ->select(
                DB::raw('DATE(start_date_time) as date'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_date_time, COALESCE(end_date_time, NOW()))) as total_seconds')
            )
            ->groupBy(DB::raw('DATE(start_date_time)'))
            ->pluck('total_seconds', 'date')
            ->mapWithKeys(function ($seconds, $date) {
                return [
                    Carbon::parse($date)->format('d M') => round($seconds / 60, 2),
                ];
            })
            ->toArray();

        $values = array_values(array_replace($defaultMap, $downtimeData));

        // TOTAL MACHINE DOWNTIME (minutes)
        $machineDowntime = array_sum($values);

        // ================= COMPLETED SALES ORDERS (GLOBAL) =================
        $completedSO = SOProductOperationDetails::query()
            ->select('so_id')
            ->groupBy('so_id')
            ->havingRaw('COUNT(*) = SUM(CASE WHEN LOWER(TRIM(final_status)) = "completed" THEN 1 ELSE 0 END)')
            ->count();

        
        // ================= VIEW =================
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
