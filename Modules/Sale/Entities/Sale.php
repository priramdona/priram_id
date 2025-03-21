<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Modules\PaymentGateway\Entities\XenditCreatePayment;

class Sale extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function saleDetails() {
        return $this->hasMany(SaleDetails::class, 'sale_id', 'id');
    }

    public function salePayments() {
        return $this->hasMany(SalePayment::class, 'sale_id', 'id');
    }

    public function xenditCreatePayment()
    {
        return $this->morphOne(XenditCreatePayment::class, 'source');
    }
    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Sale::where('business_id',Auth::user()->business_id)->count('id') + 1;
            $model->reference = make_reference_id('SL', $number);
        });
    }

    public function scopeCompleted($query) {
        return $query->where('status', 'Completed');
    }

    // public function getShippingAmountAttribute($value) {
    //     return $value / 100;
    // }

    // public function getPaidAmountAttribute($value) {
    //     return $value / 100;
    // }

    // public function getTotalAmountAttribute($value) {
    //     return $value / 100;
    // }

    // public function getDueAmountAttribute($value) {
    //     return $value / 100;
    // }

    // public function getTaxAmountAttribute($value) {
    //     return $value / 100;
    // }

    // public function getDiscountAmountAttribute($value) {
    //     return $value / 100;
    // }
}
