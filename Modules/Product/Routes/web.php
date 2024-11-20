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
    Route::post('product/{product}/upload-image', [ProductController::class, 'uploadImage'])->name('product.uploadImage');
    //Print Barcode
    Route::get('/products/print-barcode', 'BarcodeController@printBarcode')->name('barcode.print');
    //Product
    Route::resource('products', 'ProductController');
    //Product Category
    Route::resource('product-categories', 'CategoriesController')->except('create', 'show');
    Route::get('/generate-unique-barcode', [ProductController::class, 'generateUniqueBarcode'])
    ->name('generate.unique.barcode');
});

