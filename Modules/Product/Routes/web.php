<?php

use Modules\Product\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/product-sale/{product}', [ProductController::class, 'showsale'])->name('product.sale');
// Route::get('/purchase-payments/{purchase_id}', 'PurchasePaymentsController@index')->name('purchase-payments.index');

Route::group(['middleware' => 'auth'], function () {
    //Print Barcode
    Route::get('/products/print-barcode', 'BarcodeController@printBarcode')->name('barcode.print');
    //Product
    Route::resource('products', 'ProductController');
    //Product Category
    Route::resource('product-categories', 'CategoriesController')->except('create', 'show');
});

