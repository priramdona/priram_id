<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\PosController;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Modules\Sale\Http\Controllers\SaleController;

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

Route::get('/sales-show/{sale}', [SaleController::class, 'showsale'])->name('sales.showdata');
// Route::get('/product-sale/{product}', [ProductController::class, 'showsale'])->name('product.sale');
Route::group(['middleware' => 'auth'], function () {

    //POS
    Route::get('/app/pos', 'PosController@index')->name('app.pos.index');
    Route::post('/app/pos', 'PosController@store')->name('app.pos.store');

    //Generate PDF
    Route::get('/sales/pdf/{id}', function ($id) {
        $sale = \Modules\Sale\Entities\Sale::findOrFail($id);
        $customer = \Modules\People\Entities\Customer::find($sale->customer_id) ?? null;

        $pdf = PDF::loadView('sale::print', [
            'sale' => $sale,
            'customer' => $customer,
        ])->setPaper('a4');

        return $pdf->stream('sale-'. $sale->reference .'.pdf');
    })->name('sales.pdf');

    Route::get('/sales/pos/pdf/{id}', [PosController::class, 'printPos'])->name('sales.pos.pdf');

    // Route::get('/sales/pos/pdf/{id}', function ($id) {
    //     $sale = \Modules\Sale\Entities\Sale::findOrFail($id);

    //     $pdf = \PDF::loadView('sale::print-pos', [
    //         'sale' => $sale,
    //     ])
    //     // ->setPaper('a7')
    //     ->setOption('page-width', '80mm')  // Set lebar kertas 80mm
    //     ->setOption('page-height', '1000mm')  // Set tinggi kertas sesuai panjang struk
    //     ->setOption('margin-top', 8)
    //     ->setOption('margin-bottom', 8)
    //     ->setOption('margin-left', 5)
    //     ->setOption('margin-right', 5);

    //     return $pdf->stream('sale-'. $sale->reference .'.pdf');
    // })->name('sales.pos.pdf');

    //Sales
    Route::resource('sales', 'SaleController');
    Route::resource('pos', 'PosController');

    // Route::get('/get-paylater-plans', [PosController::class, 'paylaterPlans']);
    //Payments
    Route::get('/sale-payments/{sale_id}', 'SalePaymentsController@index')->name('sale-payments.index');
    Route::get('/sale-payments/{sale_id}/create', 'SalePaymentsController@create')->name('sale-payments.create');
    Route::post('/sale-payments/store', 'SalePaymentsController@store')->name('sale-payments.store');
    Route::get('/sale-payments/{sale_id}/edit/{salePayment}', 'SalePaymentsController@edit')->name('sale-payments.edit');
    Route::patch('/sale-payments/update/{salePayment}', 'SalePaymentsController@update')->name('sale-payments.update');
    Route::delete('/sale-payments/destroy/{salePayment}', 'SalePaymentsController@destroy')->name('sale-payments.destroy');
});
