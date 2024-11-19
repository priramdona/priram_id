<?php

use Illuminate\Support\Facades\Route;
use Modules\ContactUs\Http\Controllers\ContactUsController;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/contacts/terms', [ContactUsController::class, 'terms'])->name('contacts.terms');
    Route::get('/contacts/privacy', [ContactUsController::class, 'privacy'])->name('contacts.privacy');
    Route::get('/contacts/about-us', [ContactUsController::class, 'aboutUs'])->name('contacts.about-us');
    Route::get('/contacts', [ContactUsController::class, 'index'])->name('contacts.index');
    Route::post('/contacts', [ContactUsController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}', [ContactUsController::class, 'show'])->name('contacts.show');
    Route::post('/contacts/{contact}/reply', [ContactUsController::class, 'reply'])->name('contacts.reply');
});
