<?php

namespace Modules\People\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Entities\XenditPaylaterPlan;
use Modules\PaymentGateway\Entities\XenditPaylaterRequest;

class Customer extends Model
{

    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];
    public function XenditPaylaterPlan() {
        return $this->hasMany(XenditPaylaterPlan::class, 'customer_id', 'id');
    }

    public function XenditPaylaterRequest() {
        return $this->hasMany(XenditPaylaterRequest::class, 'customer_id', 'id');
    }

    protected static function newFactory() {
        return \Modules\People\Database\factories\CustomerFactory::new();
    }

}
