<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class CustomRole extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'business_id']; // Tambahkan atribut yang diperlukan

    public function setAttribute($key, $value)
    {
        // Cek dan tambahkan logika khusus untuk business_id atau team_id
        if ($key === 'business_id' && !array_key_exists('business_id', $this->attributes)) {
            $this->attributes['business_id'] = $value;
        }
        return parent::setAttribute($key, $value);
    }

    public function scopeForBusiness($query, $businessId)
{
    return $query->where('business_id', $businessId);
}
}
