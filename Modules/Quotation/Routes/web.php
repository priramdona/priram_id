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
   //Send Quotation Mail
 Route::get('/quotation/mail/{quotation}', 'SendQuotationEmailController')->name('quotation.email');

Route::group(['middleware' => 'auth'], function () {
    //Generate PDF
    Route::get('/quotations/pdf/{id}', function ($id) {
        $quotation = \Modules\Quotation\Entities\Quotation::findOrFail($id);
        $customer = \Modules\People\Entities\Customer::findOrFail($quotation->customer_id);

        $pdf = PDF::loadView('quotation::print', [
            'quotation' => $quotation,
            'customer' => $customer,
        ])->setPaper('a4');

        $output = $pdf->download();
        $filePath = storage_path('app/public/invoices/invoice_' . $quotation->id . '_quotation' . '.pdf');

        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        file_put_contents($filePath, $output);
        $publicUrl = asset('storage/invoices/invoice_' . $quotation->id . '_quotation' . '.pdf');

        return response()->json([
            'action' => "download_pdf",
            'pdf_url' => $publicUrl
        ]);
    })->name('quotations.pdf');

    //Send Quotation Mail
    Route::get('/quotation/mail/{quotation}', 'SendQuotationEmailController')->name('quotation.email');

    //Sales Form Quotation
    Route::get('/quotation-sales/{quotation}', 'QuotationSalesController')->name('quotation-sales.create');

    //quotations
    Route::resource('quotations', 'QuotationController');
});
