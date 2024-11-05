<?php

namespace Modules\Income\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Income\Database\factories\IncomeFactory;
use Modules\PaymentGateway\Entities\XenditCreatePayment;

class Income extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function category() {
        return $this->belongsTo(IncomeCategory::class, 'category_id', 'id');
    }

    public function xenditCreatePayment()
    {
        return $this->morphOne(XenditCreatePayment::class, 'source');
    }

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $number = Income::count('id') + 1;
            $model->reference = make_reference_id('INC', $number);
        });
    }

    public function getDateAttribute($value) {
        return Carbon::parse($value)->setTimezone(config('app.timezone'))->format('d M, Y');
    }

    public function incomePayments() {
        return $this->hasOne(IncomePayment::class, 'income_id', 'id');
    }
}
