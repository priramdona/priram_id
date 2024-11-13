<?php

use App\Livewire\Pos\Checkout;
use Illuminate\Support\Facades\Route;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;
use Modules\Sale\Http\Controllers\PosController;
use Modules\Sale\Http\Controllers\SelforderController;

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

Route::get('/show-payment-products/{channel}', [PaymentMethodController::class, 'showChannel'])->name('channel.product');

Route::group([], function () {
    Route::resource('paymentmethod', PaymentMethodController::class)->names('paymentmethod');
    Route::get('/get-payment-channels', [PaymentMethodController::class, 'getPaymentChannels']);
    Route::get('/get-payment-channel-details', [PaymentMethodController::class, 'getPaymentChannelDetail']);
    Route::get('/get-payment-method', [PaymentMethodController::class, 'getAllPaymentMethod']);
    Route::get('/get-payment-method-id/{id}', [PaymentMethodController::class, 'getPaymentMethod']);
    Route::get('/check-payment', [PaymentMethodController::class, 'checkPayment']);
    Route::get('/get-payment-channel-id/{id}', [PaymentMethodController::class, 'getPaymentChannel']);
    Route::get('/get-payment', [PosController::class, 'createPaymentGatewayRequest']);
    Route::get('/get-barcode', [PosController::class, 'getBarcodePayment']);
    Route::get('/get-channel-attribute', [PosController::class, 'paymentFeeChange']);

    Route::get('/get-payment-selforder', [SelforderController::class, 'createPaymentGatewayRequest']);
});
