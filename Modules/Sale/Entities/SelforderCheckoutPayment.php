<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Entities\XenditCreatePayment;
use Modules\PaymentMethod\Entities\PaymentChannel;
use Modules\PaymentMethod\Entities\PaymentMethod;
use Modules\Sale\Database\factories\SelforderCheckoutPaymentFactory;

class SelforderCheckoutPayment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];


    public function selforderCheckout() {
        return $this->belongsTo(SelforderCheckout::class, 'selforder_checkout_id', 'id');
    }
    public function xenditCreatePayment() {
        return $this->belongsTo(XenditCreatePayment::class, 'xendit_create_payment_id', 'id');
    }
    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'id');
    }
    public function paymentChannels() {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id', 'id');
    }
}
