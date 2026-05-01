<?php

namespace App\Http\Controllers;

use App\Models\ProductMasters;
use App\Models\SubProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $data = ProductMasters::with('subProducts')  // eager-load all sub-products
            ->paginate(PAGE_NO);              // paginate products

        $count['totalproduct'] = ProductMasters::count();
        $count['totalsubProduct'] = SubProduct::count();
        $count['unavailableProduct'] = ProductMasters::whereNot('status', 'Available')->count();
        $count['totalGroup'] = ProductMasters::groupBy('group')->count();
        $rowCount = $data->total();

        return view('product.index', compact('data', 'count', 'rowCount'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        // Fetch products with their sub-products and apply search filters
        $data = ProductMasters::with('subProducts')
            ->when($search, function ($query, $search) {
                $query->where('product_modified_name', 'LIKE', "%{$search}%")
                    ->orWhere('unit', 'LIKE', "%{$search}%")
                    ->orWhereHas('subProducts', function ($subQuery) use ($search) {
                        $subQuery->where('sub_product_name', 'LIKE', "%{$search}%");
                    });
            })
            ->paginate(PAGE_NO);

        $rowCount = $data->total();

        return response()->json([
            'html' => view('product.search-table', compact('data', 'rowCount'))->render(),
            'pagination' => (string) $data->links(),
            'rowCount' => $rowCount,
        ]);
    }
}
