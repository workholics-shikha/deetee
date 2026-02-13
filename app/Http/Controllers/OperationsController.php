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
        $search = $request->input('search');
        $page   = $request->input('page', 1);

        $cacheKey = "operations:list:" . md5($search) . ":page:" . $page;

        $data = Cache::remember($cacheKey, now()->addHours(1), function () use ($search) {

            $operations = OperationMaster::query()
                ->with(['machines:id,machine'])
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('operation_name', 'like', "%{$search}%")
                            ->orWhere('unit', 'like', "%{$search}%")
                            ->orWhere('matrix', 'like', "%{$search}%");
                    });
                })
                ->orderBy('id', 'asc') // ✅ ASC ORDER
                ->paginate(PAGE_NO)
                ->withQueryString();

            return [
                'records' => $operations,
                'total'   => $operations->total()
            ];
        });

        $container['records'] = $data['records'];
        $container['totalrecords'] = $data['total'];

        if ($request->ajax()) {
            $container['html'] = view('admin.snippets.operations', $container)->render();
            $container['pagination'] = (string) $data['records']->links();
            return response()->json($container);
        }

        return view('operations.list', $container);
    }
}
