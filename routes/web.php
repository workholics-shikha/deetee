<?php

use App\Http\Controllers\{AdminLoginController, DashboardController, MachineController, UserController, ProductController, QRCodeController, ReportsController, RouteCardController, SalesOrderController, SubProductOperationController, ImportController, NotificationController, OperationsController};
use App\Http\Controllers\API\{ErpApiController};

use Illuminate\Support\Facades\{Artisan, Route, DB};

Route::get('/clear-all-cache', function () {
    Artisan::call('optimize:clear');
    return '✅ All Laravel caches cleared successfully!';
});

Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    return 'Storage link created (if not already).';
});

Route::get('/truncate-data', function () {

    DB::statement('TRUNCATE TABLE personal_access_tokens');
    DB::statement('TRUNCATE TABLE operator_attendances');
    DB::statement('TRUNCATE TABLE pass_sheets');
    DB::statement('TRUNCATE TABLE sales_order_products');
    DB::statement('TRUNCATE TABLE sales_order_product_operation_details');
    DB::statement('TRUNCATE TABLE sales_order_trackings');
    DB::statement("UPDATE `machine_master` SET `machine_status` = 'active'");

    return 'Tables truncated successfully.';
});

Route::get('so-list', [ErpApiController::class, 'so_list'])->name('so-list');

Route::get('/',              [AdminLoginController::class, 'showLoginForm'])->name('login');

Route::get('login',          [AdminLoginController::class, 'showLoginForm'])->name('login');

Route::get('admin/login',    [AdminLoginController::class, 'showLoginForm'])->name('admin.login');

Route::post('admin/login',   [AdminLoginController::class, 'login'])->name('admin.login.submit');

Route::get('admin/logout',   [AdminLoginController::class, 'logout'])->name('admin.logout');

Route::get('forgot-password', [AdminLoginController::class, 'showForgotPassForm'])->name('forgot-password');

Route::post('admin/forgot',   [AdminLoginController::class, 'forgotPassword'])->name('admin.forgot.submit');

Route::get('reset-password/{code}', [AdminLoginController::class, 'resetPassForm'])->name('reset-password');

Route::post('admin/reset-password', [AdminLoginController::class, 'resetPassword'])->name('admin.reset.submit');

Route::post('admin/check/email',    [AdminLoginController::class, 'checkEmail'])->name('admin.check.email');

Route::get('thankyou',              [AdminLoginController::class, 'thankyou'])->name('thankyou');

Route::get('admin/printProductList/{id}', [SalesOrderController::class, 'printProductList'])->name('printProductList');

Route::get('admin/pdf-document/{id}', [SalesOrderController::class, 'printPreview'])->name('printPdf');

Route::get('admin/printPassSheet/{id}', [SalesOrderController::class, 'printPassSheet'])->name('printPassSheet');

Route::post('admin/resend/submit',  [AdminLoginController::class, 'resendRequest'])->name('admin.resend.submit');

// Route::middleware(['auth'])->group(function () {
Route::middleware(['auth', 'no_cache'])->group(function () {

    Route::get('admin/profile',   [DashboardController::class, 'profile'])->name('admin.profile');

    Route::get('admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Sales order routes ====
    Route::group(['middleware' => ['auth', 'permission:sale_orders']], function () {

        Route::get('admin/sales-order',              [SalesOrderController::class, 'index'])->name('admin.sales-order');

        Route::get('admin/searchInSo',               [SalesOrderController::class, 'searchInSo'])->name('admin.searchInSo');

        Route::get('admin/sales-order-details/{id}', [SalesOrderController::class, 'so_details'])->name('admin.sales-order-details');

        Route::get('admin/route-card-details/{id}/{any?}', [SubProductOperationController::class, 'route_card_details'])->name('admin.route-card-details');

        Route::get('admin/route-card-operation-details/{id}', [SubProductOperationController::class, 'route_card_operation_details'])->name('admin.route-card-operation-details');

        Route::post('admin/updateRouteCardOperationCycleTime', [SubProductOperationController::class, 'route_card_operation_cycletime'])->name('admin.route-card-operation-cycletime');

        Route::post('admin/lockRouteCard', [SubProductOperationController::class, 'lockRouteCard'])->name('admin.lockRouteCard');

        Route::get('admin/pass-sheet/{id}',          [SalesOrderController::class, 'pass_sheet'])->name('admin.pass-sheet');

        Route::get('admin/route-card-preview',       [SalesOrderController::class, 'route_card_preview'])->name('admin.route-card-preview');

        Route::get('admin/product-list-sheet-preview', [SalesOrderController::class, 'product_list_sheet_preview'])->name('admin.product-list-sheet-preview');

        Route::get('admin/pass-sheet-preview',       [SalesOrderController::class, 'pass_sheet_preview'])->name('admin.pass-sheet-preview');

        Route::get('admin/fetch-sub-product',        [SalesOrderController::class, 'fetch_sub_product'])->name('admin.fetch-sub-product');

        Route::post('admin/update-sub-product',      [SalesOrderController::class, 'update_sub_product'])->name('admin.update-sub-product');

        Route::post('admin/operation-review',        [SalesOrderController::class, 'operationReview'])->name('admin.operation-review');

        Route::get('admin/get-reviews-list/{id}',        [SalesOrderController::class, 'getOperationReviewList'])->name('admin.get-reviews-list');

        Route::get('admin/getOperationDetails/{id}/{any?}', [SalesOrderController::class, 'getOperationDetails'])->name('admin.getOperationDetails');
    });

    // Machines routes ====

    Route::group(['middleware' => ['auth', 'permission:machines']], function () {

        Route::get('admin/machines', [MachineController::class, 'index'])->name('admin.machines');
        Route::get('/admin/machine-search', [MachineController::class, 'search'])->name('admin.machine-search');
        Route::match(['get', 'post'], 'admin/machine-details/{id}', [MachineController::class, 'details'])->name('admin.machine-details');
    });

    Route::group(['middleware' => ['auth', 'permission:reports']], function () {
        Route::match(['get', 'post'], 'admin/reports', [ReportsController::class, 'index'])->name('admin.reports');
    });

    Route::get('admin/updateCompletedCount', [ReportsController::class, 'updateCompletedCount']);

    Route::group(['middleware' => ['auth', 'permission:products']], function () {

        Route::get('admin/products', [ProductController::class, 'index'])->name('admin.products');
        Route::get('/admin/product-search', [ProductController::class, 'search'])->name('admin.product-search');
    });

    Route::group(['middleware' => ['auth', 'permission:route_cards']], function () {
        Route::get('admin/route-cards', [RouteCardController::class, 'index'])->name('admin.route-cards');
        Route::get('admin/process/{id}', [RouteCardController::class, 'process'])->name('admin.process');
        Route::get('admin/route-card-search', [RouteCardController::class, 'search'])->name('admin.route-card-search');
    });

    Route::group(['middleware' => ['auth', 'permission:users']], function () {
        Route::get('admin/users', [UserController::class, 'index'])->name('admin.users');
        Route::get('admin/user/add', [UserController::class, 'add'])->name('admin.user.add');
        Route::post('admin/user/save', [UserController::class, 'save'])->name('admin.user.save');
        Route::get('admin/user/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
        Route::post('admin/user/update', [UserController::class, 'update'])->name('admin.user.update');
        Route::get('admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');
    });

    Route::group(['middleware' => ['auth', 'permission:operators']], function () {
        Route::get('admin/operators',  [UserController::class, 'operators'])->name('admin.operators');
    });

    Route::group(['middleware' => ['auth', 'permission:operations']], function () {
        Route::get('admin/operations', [OperationsController::class, 'index'])->name('admin.operations'); 
    });

    Route::get('admin/operator-details/{id}', [UserController::class, 'details'])->name('admin.operator-details');
    Route::get('/admin/operator-search', [UserController::class, 'search'])->name('admin.operator-search');
    Route::get('/admin/user-search', [UserController::class, 'userSearch'])->name('admin.user-search');

    Route::group(['middleware' => ['auth', 'permission:qr_code']], function () {

        Route::get('admin/qr-codes-list', [QRCodeController::class, 'index'])->name('admin.qr-codes-list');
        Route::get('admin/fetch-qr-card', [QRCodeController::class, 'fetchModal'])->name('admin.fetch-qr-card');
        Route::get('admin/regenerate-qr-card', [QRCodeController::class, 'regenerateQrCard'])->name('admin.regenerate-qr-card');
        Route::get('admin/deactivate-qr-card', [QRCodeController::class, 'deactivateQrCard'])->name('admin.deactivate-qr-card');
        Route::get('admin/delete-qr-card', [QRCodeController::class, 'deleteQrCard'])->name('admin.delete-qr-card');
        Route::get('admin/qr-search', [QRCodeController::class, 'search'])->name('admin.qr-search');
    });

    Route::post('admin/calculateCycleTime', [SalesOrderController::class, 'calculateCycleTime'])->name('admin.calculateCycleTime');

    Route::post('admin/submitCycleTime', [SalesOrderController::class, 'submitCycleTime'])->name('admin.submitCycleTime');

    Route::get('genericQRs', [QRCodeController::class, 'genericQRs'])->name('genericQRs');

    Route::get('deleteGenericQR/{id}', [QRCodeController::class, 'deleteGenericQR'])->name('deleteGenericQR');

    Route::post('generate/qr-code', [QRCodeController::class, 'generateQrCard'])->name('generate.qr-code');

    Route::get('admin/qr-pdf-preview', [QRCodeController::class, 'qrPdfPreview'])->name('generate.qrPdfPreview');

    Route::get('admin/machine-pdf-preview/{id}', [QRCodeController::class, 'machinePdfPreview'])->name('admin.machinePdfPreview');

    Route::post('/admin/update-machine-status', [MachineController::class, 'updateStatus'])->name('admin.updateMachineStatus');

    Route::get('admin/notification', [NotificationController::class, 'index'])->name('admin.notification');

    Route::get('generateMachineQrCard', [QRCodeController::class, 'generateMachineQrCard'])->name('generateMachineQrCard');
});

Route::get('importView', function () {
    return view('admin/importView');
});

Route::post('machine/import', [ImportController::class, 'importCSV'])->name('machine.import');

Route::post('product/import', [ImportController::class, 'importProductCSV'])->name('product.importProductCSV');

Route::post('sub-product/import', [ImportController::class, 'importSubProductCSV'])->name('subproduct.importSubProductCSV');

Route::post('operation/import', [ImportController::class, 'importOperationCSV'])->name('operation.import');

Route::post('subProductOperation/import', [ImportController::class, 'subProductOperation'])->name('product.subProductOperation');

Route::post('users/import', [ImportController::class, 'importUsersCSV'])->name('users.import');

Route::get('qrGenerate', [ImportController::class, 'qrGenerate'])->name('qrGenerate');

Route::get('qrGenerateOperations', [ImportController::class, 'qrGenerateOperations'])->name('qrGenerateOperations');

Route::post('importIdealCycleData', [ImportController::class, 'importIdealCycleData'])->name('importIdealCycleData');

Route::get('generateQR', [UserController::class, 'generateQR'])->name('generateQR');

Route::get('/sales-tracking/export', [ImportController::class, 'export']);

// 13112025
Route::get('qrGenerateSO', [ImportController::class, 'qrGenerateSO'])->name('qrGenerateSO');
Route::get('qrGenerateUsers', [ImportController::class, 'qrGenerateUsers'])->name('qrGenerateUsers');
Route::get('qrGenerateGeneric', [ImportController::class, 'qrGenerateGeneric'])->name('qrGenerateGeneric');
Route::get('qrGenerateSoProduct', [ImportController::class, 'qrGenerateSoProduct'])->name('qrGenerateSoProduct');
Route::get('copyOperationPngInSOPDetails', [ImportController::class, 'copyOperationPngInSOPDetails'])->name('copyOperationPngInSOPDetails');
Route::get('generateNewQrCodes', [ImportController::class, 'generateNewQrCodes'])->name('generateNewQrCodes');
Route::get('generateNewQrCodes2', [ImportController::class, 'generateNewQrCodes2'])->name('generateNewQrCodes2');
Route::get('addPassSheetDetails', [ImportController::class, 'addPassSheetDetails'])->name('addPassSheetDetails');
Route::get('addMissingOperation', [SalesOrderController::class, 'addMissingOperation'])->name('addMissingOperation');
