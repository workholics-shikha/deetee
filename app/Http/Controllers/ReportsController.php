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

    private function buildSoRollTrackingQuery(Request $request)
    {
        $unit      = $request->input('unit');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        $fromDate = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
        $toDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : now()->endOfDay();

        $unitMap = ['Tooling' => 1, 'RMR' => 2, 'TMR' => 3];
        $unitId  = $unitMap[$unit] ?? null;

        \DB::enableQueryLog();

        // run your query + render view here...

        $baseQuery = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            ->whereBetween('sales_order_trackings.end_date_time', [$fromDate, $toDate])
            ->when($unitId, fn($q) => $q->where('eso.so_unitid', $unitId))
            ->where('sales_order_trackings.roll_status', 'completed'); // recommended

        $query = (clone $baseQuery)
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
                DB::raw('COUNT(*) as total_quantity_processed'),
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
            ->groupBy(
                'sales_order_trackings.so_id',
                'sales_order_trackings.ideal_cycle_time',
                'operation_id',
                'so_product_id',
                'sub_product_id',
                DB::raw('DATE(end_date_time)'),
                'pass_id'
            )
            ->orderBy(DB::raw('DATE(end_date_time)'), 'DESC');

        \Log::info('query_count', ['count' => count(\DB::getQueryLog())]);

        return [$query, $fromDate, $toDate];
    }

    public function so_roll_tracking(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildSoRollTrackingQuery($request);

        $soRollTracking = $query->paginate(PAGE_SIZE_LARGE);

        return view('reports.so-roll-tracking', compact('soRollTracking', 'fromDate', 'toDate'));
    }

    public function so_roll_tracking_page(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildSoRollTrackingQuery($request);

        $soRollTracking = $query->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.so-roll-tracking-html', compact('soRollTracking', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $soRollTracking->links(),
        ]);
    }


    private function buildSoCompletionTrackingQuery(Request $request)
    {
        $unit      = $request->input('unit');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // Default last 7 days
        $fromDate = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
        $toDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : now()->endOfDay();

        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];

        $unitId = $unitMap[$unit] ?? null;

        $query = SalesOrderTracking::query()
            ->join('erp_sales_orders as eso', 'sales_order_trackings.so_id', '=', 'eso.so_id')
            ->join('product_masters as p', 'sales_order_trackings.so_product_id', '=', 'p.id')
            ->leftJoin('sub_product as sp', 'sales_order_trackings.sub_product_id', '=', 'sp.id')
            ->whereNotNull('sales_order_trackings.end_date_time')
            // IMPORTANT: use datetime range (index friendly)
            ->whereBetween('sales_order_trackings.end_date_time', [$fromDate, $toDate])
            // IMPORTANT: apply unit filter only if mapped id exists
            ->when($unitId, fn($q) => $q->where('eso.so_unitid', $unitId))
            ->select(
                'sales_order_trackings.so_id',
                'sales_order_trackings.so_product_id',
                'sales_order_trackings.sub_product_id',
                'sales_order_trackings.pass_id',
                DB::raw('MIN(sales_order_trackings.start_date_time) as start_date'),
                DB::raw('MAX(sales_order_trackings.end_date_time) as end_date'),
                // If quantity_processed repeats, DISTINCT will undercount. Keep only if you KNOW it should be distinct.
                DB::raw("COUNT(CASE WHEN sales_order_trackings.roll_status = 'completed' THEN 1 END) as completed_quantity_count"),
                DB::raw('MAX(p.product_modified_name) as product_name'),
                DB::raw('MAX(sp.sub_product_name) as sub_product_name')
            )
            ->with([
                'soProduct:so_id,so_no,so_group,so_date',
                'salesorderProducts:so_id,size1,size2,size3,soquantity,hardness,material'
            ])
            ->groupBy(
                'sales_order_trackings.so_id',
                'sales_order_trackings.so_product_id',
                'sales_order_trackings.sub_product_id',
                'sales_order_trackings.pass_id'
            )
            // Faster order (no DATE() wrapper)
            ->orderByDesc(DB::raw('MAX(sales_order_trackings.end_date_time)'));

        return [$query, $fromDate, $toDate];
    }

    public function so_completion_tracking(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildSoCompletionTrackingQuery($request);

        $soCompletionTracking = $query->paginate(PAGE_SIZE_LARGE);

        return view('reports.so-completion', compact('soCompletionTracking', 'fromDate', 'toDate'));
    }

    public function so_completion_tracking_page(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildSoCompletionTrackingQuery($request);

        $soCompletionTracking = $query->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.so-completion-html', compact('soCompletionTracking', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $soCompletionTracking->links(),
        ]);
    }

    private function buildMaintenanceHistoryQuery(Request $request): array
    {
        $unit      = $request->input('unit');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // Default last 7 days (change 6 to 9 if you want 10 days)
        $fromDate = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
        $toDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : now()->endOfDay();

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
            ->when($unit, fn($q) => $q->where('mm.unit_name', $unit))
            ->whereBetween('m.start_date_time', [$fromDate, $toDate])
            ->orderByDesc('m.start_date_time');

        return [$query, $fromDate, $toDate];
    }

    public function maintenance_history(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildMaintenanceHistoryQuery($request);

        $maintenanceHistory = $query->paginate(PAGE_SIZE_LARGE);

        return view('reports.maintenance-history', compact('maintenanceHistory', 'fromDate', 'toDate'));
    }

    public function maintenance_history_page(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildMaintenanceHistoryQuery($request);

        $maintenanceHistory = $query->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.maintenance-history-html', compact('maintenanceHistory', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $maintenanceHistory->links(),
        ]);
    }

    private function buildMaintenanceOverviewQuery(Request $request): array
    {
        $unit      = $request->input('unit');       // Tooling/RMR/TMR (string)
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // Default last 7 days (change 6 to 9 for 10 days)
        $fromDate = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
        $toDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : now()->endOfDay();

        $query = DB::table('machine_health_monitorings as m')
            ->join('machine_master as mm', 'm.master_id', '=', 'mm.id')
            ->select(
                'm.monitor_for',
                'mm.unit_name',
                DB::raw('DATE(m.created_at) as created_date'),
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, m.start_date_time, COALESCE(m.end_date_time, NOW()))) as total_seconds')
            )
            ->when($unit, fn($q) => $q->where('mm.unit_name', $unit))
            // ✅ index friendly (don’t wrap created_at in DATE() in WHERE)
            ->whereBetween('m.created_at', [$fromDate, $toDate])
            ->groupBy(DB::raw('DATE(m.created_at)'), 'm.monitor_for', 'mm.unit_name')
            ->orderByDesc(DB::raw('DATE(m.created_at)'));

        return [$query, $fromDate, $toDate];
    }

    public function maintenance_overview(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildMaintenanceOverviewQuery($request);

        $maintenanceOverview = $query->paginate(PAGE_SIZE_LARGE);

        return view('reports.maintenance-overview', compact('maintenanceOverview', 'fromDate', 'toDate'));
    }

    public function maintenance_overview_page(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildMaintenanceOverviewQuery($request);

        $maintenanceOverview = $query->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.maintenance-overview-html', compact('maintenanceOverview', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $maintenanceOverview->links(),
        ]);
    }
    
    
    private function buildProductionOverviewQuery(Request $request): array
    {
        $unit      = $request->input('unit');
        $startDate = $request->input('from_date');
        $endDate   = $request->input('to_date');

        // Default last 7 days (change 6 to 9 for 10 days)
        $fromDate = $startDate ? Carbon::parse($startDate)->startOfDay() : now()->subDays(6)->startOfDay();
        $toDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()     : now()->endOfDay();

        $unitMap = [
            'Tooling' => 1,
            'RMR'     => 2,
            'TMR'     => 3,
        ];
        $unitId = $unitMap[$unit] ?? null;

        $completedSub = DB::table('sales_order_product_operation_details')
            ->selectRaw('DATE(updated_at) as udate, COUNT(DISTINCT so_id) as completed_so_count')
            ->where('final_status', 'completed')
            ->whereBetween('updated_at', [$fromDate, $toDate]) // ✅ limit by date
            ->groupBy(DB::raw('DATE(updated_at)'));

        $query = DB::table('erp_sales_orders as eso')
            ->leftJoinSub($completedSub, 'spd_counts', function ($join) {
                $join->on(DB::raw('DATE(eso.so_date)'), '=', 'spd_counts.udate');
            })
            ->selectRaw('
            DATE(eso.so_date) as so_date,
            COUNT(*) as total_so,
            SUM(eso.soquantity) as total_quantity,
            COALESCE(MAX(spd_counts.completed_so_count), 0) as completed_so_count')
            ->when($unitId, fn($q) => $q->where('eso.so_unitid', $unitId))
            ->whereBetween('eso.so_date', [$fromDate, $toDate]) // ✅ no DATE() in WHERE
            ->groupBy(DB::raw('DATE(eso.so_date)'))
            ->orderByDesc(DB::raw('DATE(eso.so_date)'));
  
        return [$query, $fromDate, $toDate];
    }

    public function production_overview(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildProductionOverviewQuery($request);

        $productionOverview = $query->paginate(PAGE_SIZE_LARGE);

        return view('reports.production-overview', compact('productionOverview', 'fromDate', 'toDate'));
    }

    public function production_overview_page(Request $request)
    {
        [$query, $fromDate, $toDate] = $this->buildProductionOverviewQuery($request);

        $productionOverview = $query->paginate(PAGE_SIZE_LARGE);

        return response()->json([
            'html' => view('reports.production-overview-html', compact('productionOverview', 'fromDate', 'toDate'))->render(),
            'pagination' => (string) $productionOverview->links(),
        ]);
    }

}
