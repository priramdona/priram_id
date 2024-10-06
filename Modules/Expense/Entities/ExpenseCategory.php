<?php

namespace Modules\Expense\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
class ExpenseCategory extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $guarded = [];

    public function expenses() {
        return $this->hasMany(Expense::class, 'category_id', 'id');
    }
}
