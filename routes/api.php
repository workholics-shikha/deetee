<?php

use App\Http\Controllers\API\{ OperatorAuthController, ErpApiController, SalesOrderController, ScanController};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Route};
 
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/operator-register',   [OperatorAuthController::class, 'register']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// =============== operator login ===============

  Route::post('/operator-login', [OperatorAuthController::class, 'login']);
    
  Route::get('scan-machine/{id}', [ScanController::class, 'scan_machine']);
  
  Route::post('scan-sales-order', [ScanController::class, 'scan_so']);
  
  Route::post('scan-product', [ScanController::class, 'scan_product']);

  // With Auth Token
  Route::middleware('auth:sanctum')->group(function () {

      Route::get('operator-logout', [OperatorAuthController::class, 'logout']);

      Route::post('sales-order-summary', [SalesOrderController::class, 'sales_order_summary']);
      
      Route::post('sales-order-details', [SalesOrderController::class, 'sales_order_details']);

      Route::post('operation-start', [SalesOrderController::class, 'operation_start']);
       
      Route::post('operation-stop', [SalesOrderController::class, 'operation_stop']);

      Route::post('operation-details-fetch', [SalesOrderController::class, 'fetch_operation_details']);

      Route::post('submit-roll', [SalesOrderController::class, 'task_submit']);

      Route::post('scan-pass-qr-code', [SalesOrderController::class, 'scan_pass_qr']);

      Route::post('scan-process', [SalesOrderController::class, 'scan_process']);

      Route::post('update-machine-status', [SalesOrderController::class, 'update_machine_status']);

      Route::post('get-processed-rolls', [SalesOrderController::class, 'get_processed_rolls']);

      Route::post('operation-start-new', [ScanController::class, 'operation_start_new']); // for testing cycle time ====
  });
 
// ================================= ERP APIs ==================================

  Route::get('so_type',                              [ErpApiController::class, 'so_type']);
  
  Route::get('so_type_by_id/{id}',                   [ErpApiController::class, 'so_type_by_id']);

  Route::get('material',                             [ErpApiController::class, 'material']);

  Route::get('material_by_id/{id}',                  [ErpApiController::class, 'material_by_id']);

  Route::get('special_operation',                    [ErpApiController::class, 'special_operation']);

  Route::get('special_operation_by_id/{id}',         [ErpApiController::class, 'special_operation_by_id']);

  Route::get('items',                                [ErpApiController::class, 'items']);

  Route::get('items_by_id/{id}',                     [ErpApiController::class, 'items_by_id']);

  Route::get('cpo_items',                            [ErpApiController::class, 'cpo_items']);

  Route::get('cpo_items_by_id/{id}',                 [ErpApiController::class, 'cpo_items_by_id']);
  
  Route::get('so_list',                              [ErpApiController::class, 'so_list']);

  Route::get('so_child_list/{id}',                   [ErpApiController::class, 'so_child_list']);

  Route::get('show_scrunity/{id}',                   [ErpApiController::class, 'show_scrunity']);

  Route::get('show_scrunity_with_so_id/{id}',        [ErpApiController::class, 'show_scrunity_with_so_id']);

  Route::get('show_scrunity_with_cpo_item_id/{id}',  [ErpApiController::class, 'show_scrunity_with_cpo_item_id']);

  Route::get('show_item_pass/{id}',                  [ErpApiController::class, 'show_item_pass']);

// =======================  ERP APIs  ====================