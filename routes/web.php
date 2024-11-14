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
Route::view('/privacy-policy', 'auth.privacy_policy')->name('privacy.policy');
Route::view('/terms-of-service', 'auth.terms_of_service')->name('terms.service');

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

    Route::post('/financial-management-withdraw-process', [FinacialController::class, 'withdraw'])
    ->name('financial.management.withdraw.process');

    Route::get('/financial-management-withdraw', [FinacialController::class, 'index'])->name('financial.management.withdraw');
    Route::get('/financial-management-topup', [FinacialController::class, 'index'])->name('financial.management.topup');

    Route::get('notifications/{data}', [UtilityController::class, 'showNotification'])
    ->name('notifications.show');

});

