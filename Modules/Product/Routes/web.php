<?php

use Modules\Product\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Milon\Barcode\Facades\DNS1DFacade;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/product-sale/{product}', [ProductController::class, 'showsale'])->name('product.sale');
// Route::get('/purchase-payments/{purchase_id}', 'PurchasePaymentsController@index')->name('purchase-payments.index');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/products/upload', [ProductController::class, 'showUploadForm'])->name('products.upload');
    Route::post('/products/upload', [ProductController::class, 'uploadProducts'])->name('products.upload.process');
    Route::get('/products/template/download', [ProductController::class, 'downloadTemplate'])->name('products.template.download');
    Route::get('/products/check/data/edit', [ProductController::class, 'checkDataEdit'])->name('products.check.data.edit');
    Route::get('/products/check/show', [ProductController::class, 'checkShow'])->name('products.check.show');
    Route::get('/products/check/show/search', [ProductController::class, 'checkShowSearch'])->name('products.check.show.search');
    Route::get('/products/check/show/detail', [ProductController::class, 'checkShowDetail'])->name('products.check.show.detail');
    Route::get('/barcode/generate', function (Request $request) {
        $productCode = $request->query('product_code');
        $barcodeSymbology = $request->query('barcode_symbology', 'C39'); // Default ke C39
        $svg = DNS1DFacade::getBarCodeSVG($productCode, $barcodeSymbology, 2, 110);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    })->name('barcode.generate');

    // Route::get('/file-preview', [ProductController::class, 'index'])->name('file.preview');
    Route::post('/file-preview', [ProductController::class, 'preview'])->name('file.preview.upload');

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

