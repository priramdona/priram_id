<?php

namespace Modules\Sale\Entities;

use App\Models\Business;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\People\Entities\Customer;
use Modules\Sale\Database\factories\SelforderCheckoutFactory;

class SelforderCheckout extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function selforderCheckoutDetails() {
        return $this->hasMany(SelforderCheckoutDetail::class, 'selforder_checkout_id', 'id');
    }

    public function selforderCheckoutPayments() {
        return $this->hasMany(SelforderCheckoutPayment::class, 'selforder_checkout_id', 'id');
    }

    public function business() {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function selforderBusiness() {
        return $this->belongsTo(SelforderBusiness::class, 'selforder_business_id', 'id');
    }

}
