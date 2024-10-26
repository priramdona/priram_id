<?php

namespace Modules\Income\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Income\Database\factories\IncomePaymentFactory;

class IncomePayment extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function income() {
        return $this->belongsTo(Income::class, 'income_id', 'id');
    }

    // public function setAmountAttribute($value) {
    //     $this->attributes['amount'] = decryptWithKey($value);
    // }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->format('d M, Y');
    }

    public function scopeByIncome($query) {
        return $query->where('income_id', request()->route('income_id'));
    }
}
