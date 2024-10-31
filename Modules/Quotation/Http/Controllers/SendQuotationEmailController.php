<?php

namespace Modules\Quotation\Http\Controllers;

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
        // try {
            $businessEmail = BusinessEmail::where('business_id', Auth::user()->business_id)->firstOrFail();
            config([
                'mail.mailers.smtp.username' => $businessEmail->mail_username,
                'mail.mailers.smtp.password' => ($businessEmail->mail_password),
                'mail.mailers.smtp.host' => $businessEmail->mail_host,
                'mail.mailers.smtp.port' => $businessEmail->mail_port,
                'mail.mailers.smtp.encryption' => $businessEmail->mail_encryption,
                'mail.from.address' => $businessEmail->mail_from_address,
                'mail.from.name' =>  $businessEmail->mail_from_name

            ]);
            Mail::to($quotation->customer->customer_email)->send(new QuotationMail($quotation));
            // Mail::to($quotation->customer->customer_email)->send(new QuotationMail($quotation));

            $quotation->update([
                'status' => 'Sent'
            ]);

            toast('Sent On "' . $quotation->customer->customer_email . '"!', 'success');

        // } catch (\Exception $exception) {
        //     Log::error($exception);
        //     toast('Something Went Wrong!', 'error');
        // }

        return back();
    }
}
