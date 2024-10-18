<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\PaymentGateway\Database\factories\XenditCallbackFactory;

class xenditCallback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): XenditCallbackFactory
    {
        //return XenditCallbackFactory::new();
    }
}
