<?php

namespace Modules\PurchasesReturn\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class PurchaseReturn extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function purchaseReturnDetails() {
        return $this->hasMany(PurchaseReturnDetail::class, 'purchase_return_id', 'id');
    }

    public function purchaseReturnPayments() {
        return $this->hasMany(PurchaseReturnPayment::class, 'purchase_return_id', 'id');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = PurchaseReturn::where('business_id',Auth::user()->business_id)->count('id') + 1;
            $model->reference = make_reference_id('PRRN', $number);
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
