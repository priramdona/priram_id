<?php

use Illuminate\Support\Facades\Route;
use Modules\PaymentMethod\Http\Controllers\PaymentMethodController;

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

Route::group([], function () {
    Route::resource('paymentmethod', PaymentMethodController::class)->names('paymentmethod');
    Route::get('/get-payment-channels', [PaymentMethodController::class, 'getPaymentChannels']);
    Route::get('/get-payment-channel-details', [PaymentMethodController::class, 'getPaymentChannelDetail']);
    Route::get('/get-payment-method', [PaymentMethodController::class, 'getAllPaymentMethod']);

});