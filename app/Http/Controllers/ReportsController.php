<?php

namespace App\Http\Controllers;

use App\Models\{SalesOrderTracking, SOProductOperationDetails};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{

    public function index(Request $request)
    {

        set_time_limit(300); // seconds
        ini_set('max_execution_time', 300);

        $unit      = $request->input('unit'); // 1-Tooling, 2-RMR, 3-TMR
        $tab       = $request->input('tab');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();

            $defaultFromDate = now()->subDays(6)->startOfDay();
            $defaultToDate = now()->endOfDay();

        // ========= Tab 3 - Maintenance History =========
        $query = DB::table('machine_health_monitorings as m')
            ->join('machine_master as mm', 'm.master_id', '=', 'mm.id')
            ->select(
                'm.master_id',
                'm.start_date_time',
                'm.end_date_time',
                'm.reason',
                'm.monitor_for',
                'mm.machine',
                'mm.unit_name',
                'm.created_at',
                DB::raw('TIMESTAMPDIFF(SECOND, m.start_date_time, COALESCE(m.end_date_time, NOW())) as total_seconds')
            )
            ->when($unit, function ($q) use ($unit) {
                return $q->where('mm.unit_name', $unit);
            })
            ->whereBetween('m.start_date_time', [$fromDate, $toDate])
            ->orderBy('m.start_date_time', 'DESC');

        $maintenanceHistory = $query->take(PAGE_NO)->get();

        // ========= Tab 1 - MIS - Maintenance Overview =========
        $query1 = DB::table('machine_health_monitorings as m')
            ->join('machine_master as mm', 'm.master_id', '=', 'mm.id')
            ->select(
                'm.monitor_for',
                'mm.unit_name',
                DB::raw('DATE(m.created_at) as created_date'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, m.start_date_time, COALESCE(m.end_date_time, NOW()))) as total_seconds')
            )
            ->when($unit, function ($q) use ($unit) {
                return $q->where('mm.unit_name', $unit);
            })
            ->whereBetween(DB::raw('DATE(m.created_at)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy(DB::raw('DATE(m.created_at)'), 'm.monitor_for', 'mm.unit_name')
            ->orderBy(DB::raw('DATE(m.created_at)'), 'DESC');
 
        $maintenanceOverview = $query1->limit(PAGE_NO)->get();

        // ========= Tab 2 - Sale Order Completion Tracking =========
        $unitMap = [
            'Tooling' => 1,
            'RMR' => 2,
            'TMR' => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereDate('sales_order_trackings.end_date_time', '>=', $defaultFromDate)
            ->whereDate('sales_order_trackings.end_date_time', '<=', $defaultToDate)
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            });

        $soRollTracking = (clone $baseQuery)
            ->select(
                'sales_order_trackings.so_id',
                'sales_order_trackings.ideal_cycle_time',
                'operation_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                // Count each completed row as 1
                DB::raw('COUNT(CASE WHEN roll_status = "completed" THEN 1 END) as total_quantity_processed'),
                DB::raw('MAX(machine_id) as machine_id')
            )
            ->with([
                'operation:id,operation_name',
                'soProduct:so_id,so_no,so_group',
                'machine:id,machine',
                'product:id,product_modified_name',
                'subProduct:id,sub_product_name',
                'salesorderProducts:so_id,size1,size2,size3,hardness,material,soquantity'
            ])
            ->whereIn('sales_order_trackings.so_id', function ($query) {
                $query->select('st.so_id')
                    ->from('sales_order_trackings as st')
                    ->where('st.roll_status', 'completed')
                    ->groupBy('st.so_id', 'st.operation_id', 'st.so_product_id', 'st.sub_product_id');
            })
            ->groupBy('so_id', 'ideal_cycle_time', 'operation_id', 'so_product_id', 'sub_product_id', DB::raw('DATE(end_date_time)'), 'pass_id')
            ->orderBy(DB::raw('DATE(end_date_time)'), 'DESC')
            ->limit(PAGE_NO)->get();

        $soCompletionTracking = (clone $baseQuery)
            ->join('product_masters as p', 'sales_order_trackings.so_product_id', '=', 'p.id')
            ->leftJoin('sub_product as sp', 'sales_order_trackings.sub_product_id', '=', 'sp.id')
            ->select(
                'sales_order_trackings.so_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw("COUNT(DISTINCT CASE WHEN roll_status = 'completed' THEN quantity_processed END) as completed_quantity_count"),
                DB::raw('MAX(p.product_modified_name) as product_name'),
                DB::raw('MAX(sp.sub_product_name) as sub_product_name')
            )->with([
                'soProduct:so_id,so_no,so_group,so_date',
                'salesorderProducts:so_id,size1,size2,size3,soquantity,hardness,material'
            ])
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$defaultFromDate->toDateString(), $defaultToDate->toDateString()])
            ->groupBy('so_id', 'so_product_id', 'sub_product_id', 'pass_id')
            ->orderBy(DB::raw('DATE(MAX(end_date_time))'), 'DESC')
            ->limit(PAGE_NO)
            ->get();

        // ========= Tab 4 - Production Overview =========
        $completedSub = DB::table('sales_order_product_operation_details')
            ->select(
                DB::raw('DATE(updated_at) as udate'),
                DB::raw('COUNT(DISTINCT so_id) as completed_so_count')
            )
            ->where('final_status', 'completed')
            ->groupBy(DB::raw('DATE(updated_at)'));

        $production = DB::table('erp_sales_orders as eso')
            ->leftJoinSub($completedSub, 'spd_counts', function ($join) {
                $join->on(DB::raw('DATE(eso.so_date)'), '=', 'spd_counts.udate');
            })
            ->select(
                DB::raw('DATE(eso.so_date) as so_date'),
                DB::raw('COUNT(*) as total_so'),
                DB::raw('SUM(eso.soquantity) as total_quantity'),
                DB::raw('COALESCE(MAX(spd_counts.completed_so_count), 0) as completed_so_count')
            )
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            })
            ->whereBetween(DB::raw('DATE(eso.so_date)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy(DB::raw('DATE(eso.so_date)'))
            ->orderBy(DB::raw('DATE(eso.so_date)'), 'DESC');

        $productionOverview = $production->get();

        // ========= Count handling =========
        $queryCount = 0;
        if ($tab == 'MIS') {
            $queryCount = $query1->count();
        } elseif ($tab == 'SaleOrders') {
            $queryCount = $baseQuery->count();
        } elseif ($tab == 'Maintenance') {
            $queryCount = $query->count();
        }

        return view('reports.index', compact(
            'maintenanceHistory',
            'maintenanceOverview',
            'soRollTracking',
            'soCompletionTracking',
            'productionOverview',
            'queryCount',
            'fromDate',
            'toDate'
        ));
    }

    public function updateCompletedCount()
    {
        SOProductOperationDetails::chunk(200, function ($rows) {
            foreach ($rows as $row) {
                $arr = json_decode($row->processed_qty, true) ?: [];
                $completed = 0;
                foreach ($arr as $it) {
                    if (isset($it['status']) && $it['status'] === 'completed') $completed++;
                }
                $row->completed_qty = $completed;
                $row->save();
            }
        });
    }

    public function index_new(Request $request)
    {
        $unit      = $request->input('unit'); // Tooling / RMR / TMR
        $tab       = $request->input('tab', 'MIS');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();

        // ========= Unit mapping for SaleOrders tab =========
        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];
        $unitId = $unitMap[$unit] ?? null;

        // ========= Default empty (so Blade doesn't break) =========
        $maintenanceHistory   = null;        // paginator or null
        $maintenanceOverview  = collect();
        $soRollTracking       = collect();
        $soCompletionTracking = collect();
        $productionOverview   = collect();
        $queryCount           = 0;

        /**
         * NOTE:
         * We ONLY run queries for the active tab.
         * This keeps functionality same per tab, but stops 5 big queries from running every time.
         */

        // =========================
        // TAB: Maintenance History
        // =========================
        if ($tab === 'Maintenance') {

            $query = DB::table('machine_health_monitorings as m')
                ->join('machine_master as mm', 'm.master_id', '=', 'mm.id')
                ->select(
                    'm.master_id',
                    'm.start_date_time',
                    'm.end_date_time',
                    'm.reason',
                    'm.monitor_for',
                    'mm.machine',
                    'mm.unit_name',
                    'm.created_at',
                    DB::raw('TIMESTAMPDIFF(SECOND, m.start_date_time, COALESCE(m.end_date_time, NOW())) as total_seconds')
                )
                ->when($unit, function ($q) use ($unit) {
                    return $q->where('mm.unit_name', $unit);
                })
                // ✅ index-friendly (no DATE() in WHERE)
                ->whereBetween('m.start_date_time', [$fromDate, $toDate])
                ->orderBy('m.start_date_time', 'DESC');

            $maintenanceHistory = $query->paginate(PAGE_NO)->appends($request->all());

            // count for current tab (same intention as your code)
            $queryCount = (clone $query)->count();
        }

        // =========================
        // TAB: MIS
        // =========================
        if ($tab === 'MIS') {

            // ---- MIS: Maintenance Overview ----
            $query1 = DB::table('machine_health_monitorings as m')
                ->join('machine_master as mm', 'm.master_id', '=', 'mm.id')
                ->select(
                    'm.monitor_for',
                    'mm.unit_name',
                    DB::raw('DATE(m.created_at) as created_date'),
                    DB::raw('SUM(TIMESTAMPDIFF(SECOND, m.start_date_time, COALESCE(m.end_date_time, NOW()))) as total_seconds')
                )
                ->when($unit, function ($q) use ($unit) {
                    return $q->where('mm.unit_name', $unit);
                })
                // ✅ index-friendly WHERE (keeps same result range)
                ->whereBetween('m.created_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('DATE(m.created_at)'), 'm.monitor_for', 'mm.unit_name')
                ->orderBy(DB::raw('DATE(m.created_at)'), 'DESC');

            $maintenanceOverview = (clone $query1)->limit(45)->get();

            // count for current tab
            $queryCount = (clone $query1)->count();

            // ---- MIS: Production Overview ----
            $completedSub = DB::table('sales_order_product_operation_details')
                ->select(
                    DB::raw('DATE(updated_at) as udate'),
                    DB::raw('COUNT(DISTINCT so_id) as completed_so_count')
                )
                ->where('final_status', 'completed')
                // ✅ index-friendly WHERE (replaces DATE(updated_at) filter)
                ->whereBetween('updated_at', [$fromDate, $toDate])
                ->groupBy(DB::raw('DATE(updated_at)'));

            $production = DB::table('erp_sales_orders as eso')
                ->leftJoinSub($completedSub, 'spd_counts', function ($join) {
                    $join->on(DB::raw('DATE(eso.so_date)'), '=', 'spd_counts.udate');
                })
                ->select(
                    DB::raw('DATE(eso.so_date) as so_date'),
                    DB::raw('COUNT(*) as total_so'),
                    DB::raw('SUM(eso.soquantity) as total_quantity'),
                    DB::raw('COALESCE(MAX(spd_counts.completed_so_count), 0) as completed_so_count')
                )
                ->when($unit, function ($q) use ($unitId) {
                    return $q->where('eso.so_unitid', $unitId);
                })
                // ✅ index-friendly WHERE (no DATE() in WHERE)
                ->whereBetween('eso.so_date', [$fromDate, $toDate])
                ->groupBy(DB::raw('DATE(eso.so_date)'))
                ->orderBy(DB::raw('DATE(eso.so_date)'), 'DESC');

            $productionOverview = $production->get();
        }

        // =========================
        // TAB: SaleOrders
        // =========================
        if ($tab === 'SaleOrders') {

            $baseQuery = SalesOrderTracking::query()
                ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
                ->whereNotNull('sales_order_trackings.end_date_time')
                // ✅ index-friendly WHERE (no whereDate)
                ->whereBetween('sales_order_trackings.end_date_time', [$fromDate, $toDate])
                ->when($unit, function ($q) use ($unitId) {
                    return $q->where('eso.so_unitid', $unitId);
                });

            // count for current tab (same as you wanted)
            $queryCount = (clone $baseQuery)->count();

            // ---- SO Roll Tracking ----
            $soRollTracking = (clone $baseQuery)
                ->select(
                    'sales_order_trackings.so_id',
                    'sales_order_trackings.ideal_cycle_time',
                    'operation_id',
                    'so_product_id',
                    'sub_product_id',
                    'pass_id',
                    DB::raw('MIN(start_date_time) as start_date'),
                    DB::raw('MAX(end_date_time) as end_date'),
                    DB::raw('SUM(time_taken) as time_taken_minutes'),
                    DB::raw('COUNT(CASE WHEN roll_status = "completed" THEN 1 END) as total_quantity_processed'),
                    DB::raw('MAX(machine_id) as machine_id')
                )
                ->with([
                    'operation:id,operation_name',
                    'soProduct:so_id,so_no,so_group',
                    'machine:id,machine',
                    'product:id,product_modified_name',
                    'subProduct:id,sub_product_name',
                    'salesorderProducts:so_id,size1,size2,size3,hardness,material,soquantity'
                ])
                ->whereIn('sales_order_trackings.so_id', function ($query) {
                    $query->select('st.so_id')
                        ->from('sales_order_trackings as st')
                        ->where('st.roll_status', 'completed')
                        ->groupBy('st.so_id', 'st.operation_id', 'st.so_product_id', 'st.sub_product_id');
                })
                ->groupBy(
                    'so_id',
                    'ideal_cycle_time',
                    'operation_id',
                    'so_product_id',
                    'sub_product_id',
                    DB::raw('DATE(end_date_time)'),
                    'pass_id'
                )
                ->orderBy(DB::raw('DATE(end_date_time)'), 'DESC')
                ->limit(PAGE_NO)
                ->get();

            // ---- SO Completion Tracking ----
            $soCompletionTracking = (clone $baseQuery)
                ->join('product_masters as p', 'sales_order_trackings.so_product_id', '=', 'p.id')
                ->leftJoin('sub_product as sp', 'sales_order_trackings.sub_product_id', '=', 'sp.id')
                ->select(
                    'sales_order_trackings.so_id',
                    'so_product_id',
                    'sub_product_id',
                    'pass_id',
                    DB::raw('MIN(start_date_time) as start_date'),
                    DB::raw('MAX(end_date_time) as end_date'),
                    DB::raw("COUNT(DISTINCT CASE WHEN roll_status = 'completed' THEN quantity_processed END) as completed_quantity_count"),
                    DB::raw('MAX(p.product_modified_name) as product_name'),
                    DB::raw('MAX(sp.sub_product_name) as sub_product_name')
                )
                // ✅ index-friendly WHERE
                ->whereBetween('sales_order_trackings.end_date_time', [$fromDate, $toDate])
                ->with([
                    'soProduct:so_id,so_no,so_group,so_date',
                    'salesorderProducts:so_id,size1,size2,size3,soquantity,hardness,material'
                ])
                ->groupBy('so_id', 'so_product_id', 'sub_product_id', 'pass_id')
                ->orderBy(DB::raw('DATE(MAX(end_date_time))'), 'DESC')
                ->limit(PAGE_NO)
                ->get();
        }

        return view('reports.index', compact(
            'maintenanceHistory',
            'maintenanceOverview',
            'soRollTracking',
            'soCompletionTracking',
            'productionOverview',
            'queryCount',
            'fromDate',
            'toDate'
        ));
    }

    public function so_completion_tracking(Request $request)
    {

        set_time_limit(300); // seconds
        ini_set('max_execution_time', 300);

        $unit      = $request->input('unit'); // 1-Tooling, 2-RMR, 3-TMR
        $tab       = $request->input('tab');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();

        $unitMap = [
            'Tooling' => 1,
            'RMR' => 2,
            'TMR' => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereDate('sales_order_trackings.end_date_time', '>=', $fromDate)
            ->whereDate('sales_order_trackings.end_date_time', '<=', $toDate)
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            });

        $soCompletionTracking = (clone $baseQuery)
            ->join('product_masters as p', 'sales_order_trackings.so_product_id', '=', 'p.id')
            ->leftJoin('sub_product as sp', 'sales_order_trackings.sub_product_id', '=', 'sp.id')
            ->select(
                'sales_order_trackings.so_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw("COUNT(DISTINCT CASE WHEN roll_status = 'completed' THEN quantity_processed END) as completed_quantity_count"),
                DB::raw('MAX(p.product_modified_name) as product_name'),
                DB::raw('MAX(sp.sub_product_name) as sub_product_name')
            )->with([
                'soProduct:so_id,so_no,so_group,so_date',
                'salesorderProducts:so_id,size1,size2,size3,soquantity,hardness,material'
            ])
            //  ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy('so_id', 'so_product_id', 'sub_product_id', 'pass_id')
            ->orderBy(DB::raw('DATE(MAX(end_date_time))'), 'DESC')
            ->paginate(PAGE_SIZE_LARGE);

        return view('reports.so-completion', compact('soCompletionTracking', 'fromDate', 'toDate'));
    }

    public function so_completion_tracking_page(Request $request)
    {

        set_time_limit(300); // seconds
        ini_set('max_execution_time', 300);

        $unit      = $request->input('unit'); // 1-Tooling, 2-RMR, 3-TMR
        $tab       = $request->input('tab');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();
        $unitMap = [
            'Tooling' => 1,
            'RMR' => 2,
            'TMR' => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereDate('sales_order_trackings.end_date_time', '>=', $fromDate)
            ->whereDate('sales_order_trackings.end_date_time', '<=', $toDate)
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            });

        $soCompletionTracking = (clone $baseQuery)
            ->join('product_masters as p', 'sales_order_trackings.so_product_id', '=', 'p.id')
            ->leftJoin('sub_product as sp', 'sales_order_trackings.sub_product_id', '=', 'sp.id')
            ->select(
                'sales_order_trackings.so_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw("COUNT(DISTINCT CASE WHEN roll_status = 'completed' THEN quantity_processed END) as completed_quantity_count"),
                DB::raw('MAX(p.product_modified_name) as product_name'),
                DB::raw('MAX(sp.sub_product_name) as sub_product_name')
            )->with([
                'soProduct:so_id,so_no,so_group,so_date',
                'salesorderProducts:so_id,size1,size2,size3,soquantity,hardness,material'
            ])
            ->whereBetween(DB::raw('DATE(end_date_time)'), [$fromDate->toDateString(), $toDate->toDateString()])
            ->groupBy('so_id', 'so_product_id', 'sub_product_id', 'pass_id')
            ->orderBy(DB::raw('DATE(MAX(end_date_time))'), 'DESC')
            ->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.so-completion-html', compact('soCompletionTracking', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $soCompletionTracking->links(),
        ]);
    }

    public function so_roll_tracking(Request $request)
    {
        set_time_limit(300); // seconds
        ini_set('max_execution_time', 300);

        $unit      = $request->input('unit'); // 1-Tooling, 2-RMR, 3-TMR
        $tab       = $request->input('tab');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');
 
        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();
        $unitMap = [
            'Tooling' => 1,
            'RMR' => 2,
            'TMR' => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereDate('sales_order_trackings.end_date_time', '>=', $fromDate)
            ->whereDate('sales_order_trackings.end_date_time', '<=', $toDate)
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            });

        $soRollTracking = (clone $baseQuery)
            ->select(
                'sales_order_trackings.so_id',
                'sales_order_trackings.ideal_cycle_time',
                'operation_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                // Count each completed row as 1
                DB::raw('COUNT(CASE WHEN roll_status = "completed" THEN 1 END) as total_quantity_processed'),
                DB::raw('MAX(machine_id) as machine_id')
            )
            ->with([
                'operation:id,operation_name',
                'soProduct:so_id,so_no,so_group',
                'machine:id,machine',
                'product:id,product_modified_name',
                'subProduct:id,sub_product_name',
                'salesorderProducts:so_id,size1,size2,size3,hardness,material,soquantity'
            ])
            ->whereIn('sales_order_trackings.so_id', function ($query) {
                $query->select('st.so_id')
                    ->from('sales_order_trackings as st')
                    ->where('st.roll_status', 'completed')
                    ->groupBy('st.so_id', 'st.operation_id', 'st.so_product_id', 'st.sub_product_id');
            })
            ->groupBy('so_id', 'ideal_cycle_time', 'operation_id', 'so_product_id', 'sub_product_id', DB::raw('DATE(end_date_time)'), 'pass_id')
            ->orderBy(DB::raw('DATE(end_date_time)'), 'DESC')
            ->paginate(PAGE_SIZE_LARGE);
            //->limit(PAGE_NO)->get();

        return view('reports.so-roll-tracking', compact('soRollTracking', 'fromDate', 'toDate'));
    }

    public function so_roll_tracking_page(Request $request)
    {
        set_time_limit(300); // seconds
        ini_set('max_execution_time', 300);

        $unit      = $request->unit; // 1-Tooling, 2-RMR, 3-TMR
        $tab       = $request->tab;
        $startDate = $request->from_date;
        $endDate   = $request->to_date;

        print_r($request->all()); echo "<br>";
        print_r($endDate);
        

        // === Default to last 7 days if no filter provided ===
        $fromDate = $startDate
            ? Carbon::parse($startDate)->startOfDay()
            : now()->subDays(6)->startOfDay();

        $toDate = $endDate
            ? Carbon::parse($endDate)->endOfDay()
            : now()->endOfDay();
        $unitMap = [
            'Tooling' => 1,
            'RMR' => 2,
            'TMR' => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereDate('sales_order_trackings.end_date_time', '>=', $fromDate)
            ->whereDate('sales_order_trackings.end_date_time', '<=', $toDate)
            ->when($unit, function ($q) use ($unitId) {
                return $q->where('eso.so_unitid', $unitId);
            });

        $soRollTracking = (clone $baseQuery)
            ->select(
                'sales_order_trackings.so_id',
                'sales_order_trackings.ideal_cycle_time',
                'operation_id',
                'so_product_id',
                'sub_product_id',
                'pass_id',
                DB::raw('MIN(start_date_time) as start_date'),
                DB::raw('MAX(end_date_time) as end_date'),
                DB::raw('SUM(time_taken) as time_taken_minutes'),
                // Count each completed row as 1
                DB::raw('COUNT(CASE WHEN roll_status = "completed" THEN 1 END) as total_quantity_processed'),
                DB::raw('MAX(machine_id) as machine_id')
            )
            ->with([
                'operation:id,operation_name',
                'soProduct:so_id,so_no,so_group',
                'machine:id,machine',
                'product:id,product_modified_name',
                'subProduct:id,sub_product_name',
                'salesorderProducts:so_id,size1,size2,size3,hardness,material,soquantity'
            ])
            ->whereIn('sales_order_trackings.so_id', function ($query) {
                $query->select('st.so_id')
                    ->from('sales_order_trackings as st')
                    ->where('st.roll_status', 'completed')
                    ->groupBy('st.so_id', 'st.operation_id', 'st.so_product_id', 'st.sub_product_id');
            })
            ->groupBy('so_id', 'ideal_cycle_time', 'operation_id', 'so_product_id', 'sub_product_id', DB::raw('DATE(end_date_time)'), 'pass_id')
            ->orderBy(DB::raw('DATE(end_date_time)'), 'DESC')
            ->paginate(PAGE_SIZE_LARGE); 

        return response()->json([
            'html' => view('reports.so-roll-tracking-html', compact('soRollTracking', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $soRollTracking->links(),
        ]);
    }
}
