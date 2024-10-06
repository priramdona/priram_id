<?php

namespace Modules\Upload\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
class Upload extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, HasUuids, SoftDeletes;

    protected $guarded = [];


}
