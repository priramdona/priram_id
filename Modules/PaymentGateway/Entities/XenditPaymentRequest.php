<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class xenditPaymentRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];


}
