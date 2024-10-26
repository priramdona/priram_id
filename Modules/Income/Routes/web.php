<?php

use Illuminate\Support\Facades\Route;
use Modules\Income\Http\Controllers\IncomeController;

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
    Route::resource('income', IncomeController::class)->names('income');
    Route::resource('income-categories', 'IncomeCategoriesController')->except('show', 'create');
    //Income
    Route::resource('incomes', 'IncomeController')->except('show');

});
