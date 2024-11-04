<?php

namespace Modules\Quotation\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessEmail;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Modules\Quotation\Emails\QuotationMail;
use Modules\Quotation\Entities\Quotation;

class SendQuotationEmailController extends Controller
{
    public function __invoke(Quotation $quotation) {

        try {

            // return view('quotation::emails.quotation', [
            //     'settings' => settings(),
            //     'customer' => $quotation->customer,
            //     'quotation' => $quotation,
            //     'business' => Business::find($quotation->business_id)
            // ]);
            Mail::to($quotation->customer->customer_email)->send(new QuotationMail($quotation));

            $quotation->update([
                'status' => 'Sent'
            ]);

            toast('Sent On "' . $quotation->customer->customer_email . '"!', 'success');

        } catch (\Exception $exception) {
            Log::error($exception);
            toast('Something Went Wrong!', 'error');
        }

        return back();
    }
}
