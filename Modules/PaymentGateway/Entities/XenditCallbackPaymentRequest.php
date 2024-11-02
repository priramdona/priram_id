<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PaymentGateway\Database\factories\XenditCallbackPaymentRequestFactory;

class XenditCallbackPaymentRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): XenditCallbackPaymentRequestFactory
    {
        //return XenditCallbackPaymentRequestFactory::new();
    }
}
