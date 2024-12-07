<?php
use Illuminate\Support\Facades\Route;
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

Route::group(['middleware' => 'auth'], function () {
    //Generate PDF
    Route::get('/sale-returns/pdf/{id}', function ($id) {
        $saleReturn = \Modules\SalesReturn\Entities\SaleReturn::findOrFail($id);
        $customer = \Modules\People\Entities\Customer::find($saleReturn->customer_id) ?? null;

        $pdf = PDF::loadView('salesreturn::print', [
            'sale_return' => $saleReturn,
            'customer' => $customer,
        ])->setPaper('a4');

        $output = $pdf->download();
        $filePath = storage_path('app/public/invoices/invoice_' . $saleReturn->id . '_sale_return' . '.pdf');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        file_put_contents($filePath, $output);
        $publicUrl = asset('storage/invoices/invoice_' . $saleReturn->id . '_sale_return' . '.pdf');

        return response()->json([
            'action' => "download_pdf",
            'pdf_url' => $publicUrl
        ]);
    })->name('sale-returns.pdf');

    //Sale Returns
    Route::resource('sale-returns', 'SalesReturnController');

    //Payments
    Route::get('/sale-return-payments/{sale_return_id}', 'SaleReturnPaymentsController@index')
        ->name('sale-return-payments.index');
    Route::get('/sale-return-payments/{sale_return_id}/create', 'SaleReturnPaymentsController@create')
        ->name('sale-return-payments.create');
    Route::post('/sale-return-payments/store', 'SaleReturnPaymentsController@store')
        ->name('sale-return-payments.store');
    Route::get('/sale-return-payments/{sale_return_id}/edit/{saleReturnPayment}', 'SaleReturnPaymentsController@edit')
        ->name('sale-return-payments.edit');
    Route::patch('/sale-return-payments/update/{saleReturnPayment}', 'SaleReturnPaymentsController@update')
        ->name('sale-return-payments.update');
    Route::delete('/sale-return-payments/destroy/{saleReturnPayment}', 'SaleReturnPaymentsController@destroy')
        ->name('sale-return-payments.destroy');
});
