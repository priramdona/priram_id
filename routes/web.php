<?php

use App\Http\Controllers\BarcodeScannerController;
use App\Http\Controllers\FinacialController;
use App\Http\Controllers\UtilityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/clear-permission-cache', function() {
    app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    return 'Permission cache cleared';
});

Route::get('/', function () {
    return view('auth.login');
})->middleware('guest');

Auth::routes(['register' => true]);

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index')
        ->name('home');

    Route::get('/sales-purchases/chart-data', 'HomeController@salesPurchasesChart')
        ->name('sales-purchases.chart');

    Route::get('/current-month/chart-data', 'HomeController@currentMonthChart')
        ->name('current-month.chart');

    Route::get('/payment-flow/chart-data', 'HomeController@paymentChart')
        ->name('payment-flow.chart');

    Route::get('/scan-barcode', [BarcodeScannerController::class, 'index'])->name('scan.barcode');

    Route::post('/financial-management-withdraw', [FinacialController::class, 'withdraw'])
    ->name('financial.management.withdraw');
    Route::get('/financial-management', [FinacialController::class, 'index'])->name('financial.management');

    Route::get('notifications/{data}', [UtilityController::class, 'showNotification'])
    ->name('notifications.show');

});

