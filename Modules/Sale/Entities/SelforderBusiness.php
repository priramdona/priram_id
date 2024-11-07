<?php

namespace Modules\Sale\Entities;

use App\Models\Business;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Sale\Database\factories\SelforderBusinessFactory;

class SelforderBusiness extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    public function business() {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function selforderType() {
        return $this->belongsTo(SelforderType::class, 'selforder_type_id', 'id');
    }
}
