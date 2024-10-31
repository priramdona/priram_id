<?php

namespace Modules\PaymentGateway\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class XenditCreatePayment extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];
    public function transactional(): MorphTo
    {
        return $this->morphTo();
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

}
