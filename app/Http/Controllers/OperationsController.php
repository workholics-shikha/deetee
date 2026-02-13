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
    public function index(Request $request)
    {
        $search = trim($request->input('search', ''));
        $page = (int) $request->input('page', 1);

        $cacheKey = 'operations_tmr:'
            .md5($search) // normalize search
            .":page:{$page}";

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
            'records' => $operations,
            'totalrecords' => $operations->total(),
        ];

        if ($request->ajax()) {
            $container['html'] = view('admin/snippets/operations', $container)->render();
            $container['pagination'] = (string) $operations->links();

            return response()->json($container);
        }

        return view('operations.list', $container);
    }
}
