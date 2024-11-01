<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class CustomRole extends SpatieRole
{
    use HasFactory;
    use HasUuids;
    protected $fillable = ['name', 'guard_name', 'business_id']; // Tambahkan atribut yang diperlukan


    // Scope untuk mengambil role berdasarkan business_id
    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }

}
