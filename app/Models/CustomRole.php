<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;


class CustomRole extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'business_id']; // Tambahkan atribut yang diperlukan

    public function setAttribute($key, $value = null)
{
    // Cek jika hanya satu argumen yang diberikan, langsung lempar ke parent method
    if (func_num_args() === 1) {
        return parent::setAttribute($key);
    }

    // Logika khusus untuk atribut 'business_id'
    if ($key === 'business_id') {
        $this->attributes['business_id'] = $value;
        return $this;
    }

    // Jika bukan 'business_id', gunakan parent method
    return parent::setAttribute($key, $value);
}

    public function scopeForBusiness($query, $businessId)
    {
        return $query->where('business_id', $businessId);
    }
}
