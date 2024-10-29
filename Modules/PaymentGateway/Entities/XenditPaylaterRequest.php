<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Database\factories\XenditPaylaterRequestFactory;
use Modules\People\Entities\Customer;

class XenditPaylaterRequest extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function XenditPaylaterPlan() {
        return $this->belongsTo(XenditPaylaterPlan::class, 'xendit_paylater_plan_id', 'id');
    }
}
