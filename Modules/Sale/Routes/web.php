<?php

use Illuminate\Support\Facades\Route;
use Modules\Sale\Http\Controllers\PosController;
// use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Modules\Sale\Http\Controllers\SaleController;
use Modules\Sale\Http\Controllers\SelforderController;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
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
Route::get('/selforder/enter', [SelforderController::class, 'enterSelforder'])
->name('selforder.enterSelforder');
Route::get('/mobileorder/{business}/{key}', [SelforderController::class, 'indexMobileOrder'])
->name('selforder.indexMobileOrder');

Route::post('/selforder-posmobileorder/{id}', [SelforderController::class, 'posMobileOrder'])
->name('selforder.posmobileorder');

Route::get('/selforder/mobile-order-qr/{id}', [SelforderController::class, 'mobileOrderQrCodeGenerator'])->name('selforder.mobileOrderQrCodeGenerator');
Route::get('/sales-show/{sale}', [SaleController::class, 'showsale'])->name('sales.showdata');
Route::post('/app/mobileorder', 'SelforderController@storeMobileOrder')->name('app.selforder.mobileorder.store');

Route::get('/selforder/checkout/{id}', [SelforderController::class, 'redirectSuccessSelfOrder'])->name('selforder-checkout.success');

Route::group(['middleware' => 'auth'], function () {

    //selforder
    // Route::get('/app/mobileorder', 'SelforderController@indexMobileOrder')->name('app.selforder.mobileorder');

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

        $output = $pdf->download();
        $filePath = storage_path('app/public/invoices/invoice_' . $sale->id . '_sale' . '.pdf');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        file_put_contents($filePath, $output);
        $publicUrl = asset('storage/invoices/invoice_' . $sale->id . '_sale' . '.pdf');

        return response()->json([
            'action' => "download_pdf",
            'pdf_url' => $publicUrl
        ]);

        // return $pdf->stream('sale-'. $sale->reference .'.pdf');
    })->name('sales.pdf');

    Route::get('/sales/pos/pdf/{id}', [PosController::class, 'printPos'])->name('sales.pos.pdf');

    Route::resource('sales', 'SaleController');
    Route::resource('pos', 'PosController');

    // Route::get('/get-paylater-plans', [PosController::class, 'paylaterPlans']);
    //Payments
    Route::get('/sale-payments/{sale_id}', 'SalePaymentsController@index')->name('sale-payments.index');
    Route::get('/get-payment-sales', 'SalePaymentsController@getPaymentSales')->name('getpaymentsales');
    Route::get('/sale-payments/{sale_id}/create', 'SalePaymentsController@create')->name('sale-payments.create');
    Route::post('/sale-payments/store', 'SalePaymentsController@store')->name('sale-payments.store');
    Route::get('/sale-payments/{sale_id}/edit/{salePayment}', 'SalePaymentsController@edit')->name('sale-payments.edit');
    Route::patch('/sale-payments/update/{salePayment}', 'SalePaymentsController@update')->name('sale-payments.update');
    Route::delete('/sale-payments/destroy/{salePayment}', 'SalePaymentsController@destroy')->name('sale-payments.destroy');

    Route::get('/selforder/mobileorder', 'SelforderController@manageMobileOrder')->name('selforder.mobileorder');
    Route::patch('/selforder/business/{id}', 'SelforderController@updateSelforderBusinessMobile')->name('selforder.business.update');
    Route::get('/selforder/mobileorder/index', [SelforderController::class, 'indexSelforderCheckout'])->name('selforder.mobileorder.index');
    Route::get('/selfordercheckout/{selforderCheckout}', [SelforderController::class, 'selforderCheckout'])->name('selfordercheckout');
    Route::get('/selforder/selforderprocess', [SelforderController::class, 'selforderProcess'])->name('selforder.selforderprocess');

    Route::get('/selforder/deliveryorder', 'SelforderController@manageDeliveryOrder')->name('selforder.deliveryorder');
    Route::post('/calculate-route', [SelforderController::class, 'calculateRoute'])->name('calculate.route');

});
