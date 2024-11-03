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
        return $this->subject('Quotation - ' . settings()->company_name . ' Date ' . Carbon::parse(now())->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'))
            ->view('quotation::emails.quotation', [
                'settings' => settings(),
                'customer' => $this->quotation->customer,
                'business' => Business::find($this->quotation->business_id)
            ]);
    }
}
