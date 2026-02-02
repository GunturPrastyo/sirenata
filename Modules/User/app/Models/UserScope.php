<?php

namespace Modules\User\Models;

use App\Models\User;
use Creasi\Nusa\Models\Province;
use Creasi\Nusa\Models\Regency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\User\Database\Factories\UserScopeFactory;

class UserScope extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'province_code',
        'regency_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

     public function getProvinceNameAttribute()
    {
        if (!$this->province_code) return null;

        return Province::where('code', $this->province_code)
            ->value('name');
    }

    public function getRegencyNameAttribute()
    {
        if (!$this->regency_code) return null;

        return Regency::where('code', $this->regency_code)
            ->value('name');
    }


    
}
