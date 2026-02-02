<?php

namespace Modules\User\Models;

use App\Models\User;
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

    // protected static function newFactory(): UserScopeFactory
    // {
    //     // return UserScopeFactory::new();
    // }
}
