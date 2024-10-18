<?php

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

use Modules\Whatsapp\Http\Controllers\WhatsappController;

Route::group(['middleware' => 'auth'], function () {
    Route::resource('whatsapp', 'WhatsappController');
    Route::post('/send-message-image', [WhatsappController::class, 'sendMessageImage'])->name('messages.sendImage');
    Route::post('/broadcast-message', [WhatsappController::class, 'broadcastMessage'])->name('messages.broadcast');

});
