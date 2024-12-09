<?php

namespace Modules\Quotation\Emails;

use App\Models\Business;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $business = Business::find($this->quotation->business_id);

            $businessName = $business->name ?? null;
            $businessEmail = $business->email ?? null;
            $isEmailValid = filter_var($businessEmail, FILTER_VALIDATE_EMAIL) !== false;

            // $fromEmail = $isEmailValid ? $businessEmail : config('mail.from.address');
            // $fromName = $businessName ?: config('mail.from.name');

            $fromEmail = config('mail.from.address');
            $fromName = config('mail.from.name');

        return $this->subject('Quotation - ' . settings()->company_name . ' Date ' . Carbon::parse(now())->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'))
            ->from($fromEmail, $fromName)
            ->view('quotation::emails.quotation', [
                'settings' => settings(),
                'customer' => $this->quotation->customer,
                'business' => $business
            ]);
    }
}
