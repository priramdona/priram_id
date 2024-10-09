<?php

namespace Modules\PaymentMethod\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PaymentMethod\Database\factories\PaymentChannelFactory;

class PaymentChannel extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $guarded = [];

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('images/' . $this->image);
        }

        // Jika tidak ada gambar, kembalikan URL gambar default
        return asset('images/default.png');
    }
}
