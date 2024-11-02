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
    Route::any('/payment-methods-callback', [XenditWebhookController::class, 'callbackPaymentMethod']);
    Route::any('/payment-method-succeeded', [XenditWebhookController::class, 'callbackPaymentSucceeded']);
    Route::any('/create-va-callback', [XenditWebhookController::class, 'callbackCreateVirtualAccount']);
    Route::any('/va-paid', [XenditWebhookController::class, 'callbackPaidVirtualAccount']);
});




Route::get('/pay-cc', [PaymentGatewayController::class, 'payCC'])->name('pay.cc');

Route::group(['middleware' => 'auth'], function() {
Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting'])->name('payment-gateways.setting');

Route::get('/create-invoice-request', [PaymentGatewayController::class, 'createInvoiceRequest'])->name('create.invoice.request');

Route::resource('payment-gateways', PaymentGatewayController::class);
    // Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting']);
    // Route::get('payment-gateways/setting', [PaymentGatewayController::class, 'setting'])->name('payment-gateways.setting');

    // Route::get('/settings', 'PaymentGatewayController@settingan')->name('payment.setting');
    // Route::get('', [PaymentGatewayController::class, 'setting']);
    // Route::get('/payment-gateways-settings', [PaymentGatewayController::class,'setting']);
        // ->name('setting-payment-gateways');

Route::get('/get-disbursement-channels', [PaymentGatewayController::class, 'getDisbursementChannels']);

});
