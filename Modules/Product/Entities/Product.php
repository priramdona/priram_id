<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Product\Notifications\NotifyQuantityAlert;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model implements HasMedia
{

    use HasFactory, InteractsWithMedia, HasUuids, SoftDeletes;

    protected $guarded = [];

    protected $with = ['media'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function registerMediaCollections(): void {
        $this->addMediaCollection('images')
            ->useFallbackUrl('/images/fallback_product_image.png');
    }

    public function registerMediaConversions(Media $media = null): void {
        $this->addMediaConversion('thumb')
            ->width(50)
            ->height(50);
    }

    // public function setProductCostAttribute($value) {
    //     $this->attributes['product_cost'] = ($value * 100);
    // }

    // public function getProductCostAttribute($value) {
    //     return ($value / 100);
    // }

    // public function setProductPriceAttribute($value) {
    //     $this->attributes['product_price'] = ($value * 100);
    // }

    // public function getProductPriceAttribute($value) {
    //     return ($value / 100);
    // }
}
