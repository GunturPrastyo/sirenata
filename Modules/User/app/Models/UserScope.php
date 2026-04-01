<?php

namespace Modules\User\Models;

use App\Models\User;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
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

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_code', 'code');
    }


    
}
