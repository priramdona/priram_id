<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class businessAmount extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;


    protected $guarded = [];


    public function transactional()
    {
        return $this->morphTo();
    }
}