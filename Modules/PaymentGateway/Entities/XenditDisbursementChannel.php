<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Database\factories\XenditDisbursementChannelFactory;

class XenditDisbursementChannel extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function XenditDisbursementMethod() {
        return $this->belongsTo(XenditDisbursementMethod::class, 'xendit_disbursement_method_id', 'id');
    }

}
