<?php

namespace Modules\Sale\Entities;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Sale\Database\factories\SelforderCheckoutDetailFactory;

class SelforderCheckoutDetail extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];


    protected $with = ['product'];

    public function product() {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function selforderCheckout() {
        return $this->belongsTo(SelforderCheckout::class, 'selforder_checkout_id', 'id');
    }
}
