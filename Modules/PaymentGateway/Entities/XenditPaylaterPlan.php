<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Database\factories\XenditPaylaterPlanFactory;
use Modules\People\Entities\Customer;

class XenditPaylaterPlan extends Model
{

    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function XenditPaylaterRequest() {
        return $this->hasMany(XenditPaylaterRequest::class, 'xendit_paylater_plan_id', 'id');
    }
}
