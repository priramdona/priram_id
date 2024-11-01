<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class CustomRole extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'business_id']; // Tambahkan atribut yang diperlukan

    // Mutator untuk mengatur business_id
    public function setBusinessIdAttribute($value)
    {
        $this->attributes['business_id'] = $value;
    }

    // Scope untuk mengambil role berdasarkan business_id
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

}
