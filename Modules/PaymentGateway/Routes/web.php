<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentGateway\Http\Controllers\PaymentGatewayController;
use Modules\PaymentGateway\Http\Controllers\XenditWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'prefix' => 'payment-gateways',
], function () {
    Route::any('/callback', [XenditWebhookController::class, 'callback']);
});

Route::group(['middleware' => 'auth'], function() {
     Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting'])->name('payment-gateways.setting');

    Route::resource('payment-gateways', PaymentGatewayController::class);
    // Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting']);
    // Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting'])->name('payment-gateways.setting');

    // Route::get('/settings', 'PaymentGatewayController@settingan')->name('payment.setting');
    // Route::get('', [PaymentGatewayController::class, 'setting']);
    // Route::get('/payment-gateways-settings', [PaymentGatewayController::class,'setting']);
        // ->name('setting-payment-gateways');
});
