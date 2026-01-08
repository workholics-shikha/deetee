<?php

namespace App\Http\Controllers;

use App\Models\OperationMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OperationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index1(Request $request)
    {
        $search = $request->input('search');
        $page   = $request->input('page', 1); // default page 1

        // Create unique cache key
        $cacheKey = "operations_{$search}_page_{$page}";

        // Cache for 5 minutes (300 sec)
        $operations = Cache::remember($cacheKey, 300, function () use ($search) {
            return OperationMaster::with('machines')
                //->where('unit', 'like', "%TMR%")
                ->when($search, function ($query, $search) {
                    $query->where('operation_name', 'like', "%$search%")
                        ->orWhere('unit', 'like', "%$search%")
                        ->orWhere('matrix', 'like', "%$search%");
                })
                ->paginate(PAGE_NO);
        });

        $container['records'] = $operations;
        $container['totalrecords'] = $operations->total();

        if ($request->get('page')) {
            $html = view('admin/snippets/operations', $container)->render();
            $container['html'] = $html;
            $container['pagination'] = (string) $operations->links();
            return response()->json($container);
        }

        return view('operations.list', $container);
    }

    public function index2(Request $request)
    {
        $search = $request->input('search');
        $page   = $request->input('page', 1);

        $cacheKey = "operations_TMR_{$search}_page_{$page}";

        $operations = Cache::remember($cacheKey, 300, function () use ($search) {
            return OperationMaster::with('machines')
                // ->where('unit', 'TMR') // be strict if possible
                ->when($search, function ($query, $search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('operation_name', 'like', "%{$search}%")
                            ->orWhere('matrix', 'like', "%{$search}%");
                    });
                })
                ->paginate(PAGE_NO);
        });

        $container['records'] = $operations;
        $container['totalrecords'] = $operations->total();

        if ($request->get('page')) {
            $container['html'] = view('admin/snippets/operations', $container)->render();
            $container['pagination'] = (string) $operations->links();
            return response()->json($container);
        }

        return view('operations.list', $container);
    }


    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $page   = (int) $request->input('page', 1);

        $cacheKey = "operations_tmr:"
            . md5($search) // normalize search
            . ":page:{$page}";

        $operations = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search) {

            return OperationMaster::with('machines')
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('operation_name', 'like', "%{$search}%")
                            ->orWhere('matrix', 'like', "%{$search}%");
                    });
                })
                ->paginate(PAGE_NO);
        });

        $container = [
            'records'       => $operations,
            'totalrecords'  => $operations->total(),
        ];

        if ($request->ajax()) {
            $container['html'] = view('admin/snippets/operations', $container)->render();
            $container['pagination'] = (string) $operations->links();

            return response()->json($container);
        }

        return view('operations.list', $container);
    }
}
