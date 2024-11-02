<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentMethod\Entities\PaymentChannel;
class SalePayment extends Model
{

    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function sale() {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    // public function setAmountAttribute($value) {
    //     $this->attributes['amount'] = decryptWithKey($value);
    // }

    // public function getAmountAttribute($value) {
    //     return $value / 100;
    // }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function scopeBySale($query) {
        return $query->where('sale_id', request()->route('sale_id'));
    }

    public function paymentChannels() {
        return $this->belongsTo(PaymentChannel::class, 'payment_channel_id', 'id');
    }
}
