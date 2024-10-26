<?php

namespace Modules\Income\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Income\Database\factories\IncomeCategoryFactory;

class IncomeCategory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function incomes() {
        return $this->hasMany(Income::class, 'category_id', 'id');
    }
}
