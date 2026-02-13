<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\OperationMaster;
use Illuminate\Support\Facades\Cache;

class OperationMasterController extends Controller
{
    /**
     * Display a listing of operations.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $page   = $request->input('page', 1);

        // Cache key per search + page
        $cacheKey = "operations:list:" . md5($search ?? '') . ":page:" . $page;

        // Retrieve from cache or generate
        $data = Cache::remember($cacheKey, now()->addHours(1), function () use ($search) {
            $operations = $this->getOperationsQuery($search)
                ->paginate(PAGE_NO)
                ->withQueryString();

            return [
                'records' => $operations,
                'total'   => $operations->total(),
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

    /**
     * Build the query for operations listing.
     */
    private function getOperationsQuery(?string $search)
    {
        return OperationMaster::query()
            ->with(['machines:id,machine'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('operation_name', 'like', "%{$search}%")
                      ->orWhere('unit', 'like', "%{$search}%")
                      ->orWhere('matrix', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc'); // tie-breaker for same names
    }
}
