<?php

namespace App\Http\Controllers;

use App\Models\OperationMaster;
use Illuminate\Http\Request;

class OperationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
         $search      = $request->input('search'); 
         $operations  = OperationMaster::with('machines')
 
         ->when($search, function ($query, $search) {
             $query->where('operation_name', 'like', "%$search%");
             $query->orWhere('unit', 'like', "%$search%"); 
             $query->orWhere('matrix', 'like', "%$search%"); 
         }) 
         ->paginate(PAGE_NO); 
 
         $container['records'] = $operations;
         $container['totalrecords'] = $operations->total();
         if($request->get('page'))
         {
            $html = view('admin/snippets/operations',$container)->render();
            $container['html'] = $html;
            $container['pagination'] = (string) $operations->links();
            return response()->json($container);
         }
         return view('operations.list',$container);
    }

    public function cycles()
    {
          return view('admin.cycles');
    }
     
   
}