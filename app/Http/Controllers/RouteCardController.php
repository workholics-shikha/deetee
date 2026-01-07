<?php

namespace App\Http\Controllers;

use App\Models\{SubProduct, SubproductWiseOperation};
use Illuminate\Http\Request;

class RouteCardController extends Controller
{
    public function index()
    {
        $data = SubProduct::with(['product'])->paginate(PAGE_NO);
        return view('route-cards.index', compact('data'));
    }

    public function process($id)
    {
        $product = SubproductWiseOperation::with(['product', 'sub_product'])->where('subproduct_id', $id)->first();
        $subProductName = $product->sub_product->sub_product_name ?? 'N/A';
       
        $operations = SubproductWiseOperation::with('operations')
            ->where('subproduct_id', $id)
            ->orderByRaw("CASE WHEN s_no IS NULL OR s_no = '' THEN 1 ELSE 0 END")
            ->orderBy('s_no')
            ->get();

        return view('route-cards.process', compact('product', 'operations', 'subProductName'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search'); // Get the search term from the request
        $data = SubProduct::with(['product'])
            ->when($search, function ($query, $search) {
                $query->where('sub_product_name', 'LIKE', "%{$search}%")
                    ->orWhereHas('product', function ($subQuery) use ($search) {
                        $subQuery->where('unit', 'LIKE', "%{$search}%");
                        $subQuery->orWhere('erp_product', 'LIKE', "%{$search}%");
                        $subQuery->orWhere('erp_nomenclature', 'LIKE', "%{$search}%");
                        $subQuery->orWhere('group', 'LIKE', "%{$search}%");
                    });
            })
            ->paginate(PAGE_NO);

        // Return HTML for table rows and pagination links
        return response()->json([
            'html' => view('route-cards.search-table', compact('data'))->render(),
            'pagination' => (string) $data->links(),
        ]);
    }
}
