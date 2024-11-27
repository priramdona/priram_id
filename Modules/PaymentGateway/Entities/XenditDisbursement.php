<?php

namespace Modules\PaymentGateway\Entities;

use App\Models\businessAmount;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentGateway\Database\factories\XenditDisbursementFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class XenditDisbursement extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    protected $casts = [
        'channel_properties' => 'array',
        'receipt_notification' => 'array',
    ];

    public function businessAmount()
    {
        return $this->morphOne(businessAmount::class, 'transactional');
    }
}
