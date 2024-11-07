<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Sale\Database\factories\SelforderTypeFactory;

class SelforderType extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function selforderBusiness() {
        return $this->hasMany(SelforderBusiness::class, 'selforder_type_id', 'id');
    }

}
