<?php

namespace Modules\PaymentGateway\Entities;

use App\Models\businessAmount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Database\factories\XenditPaymentMethodFactory;

class xenditPaymentMethod extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function businessAmount()
    {
        return $this->morphOne(businessAmount::class, 'transactional');
    }
}
