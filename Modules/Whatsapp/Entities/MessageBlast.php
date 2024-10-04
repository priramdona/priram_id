<?php

namespace Modules\Whatsapp\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Whatsapp\Database\factories\MessageBlastFactory;

class MessageBlast extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): MessageBlastFactory
    {
        //return MessageBlastFactory::new();
    }
}
