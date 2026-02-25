<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasUuids, LogsActivity;

    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name','email']);
    }

    public function hasCompleteScope(): bool
    {
        if (!$this->scopeArea) {
            return false;
        }

        if ($this->hasRole('super-admin')) {
            return true;
        }

        if ($this->hasRole('admin-provinsi')) {
            return !empty($this->scopeArea->province_code);
        }

        if ($this->hasRole('admin-kabkota')) {
            return !empty($this->scopeArea->province_code)
                && !empty($this->scopeArea->regency_code);
        }

        return false;
    }

    #[Scope]
    protected function search(Builder $query, string $keyword): void
    {
        $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%");
    }

    public function getRedirectRoute(): string
    {
        return match (true) {
            $this->hasRole('super-admin') => 'super-admin.dashboard',
            $this->hasRole('admin-pusat') => 'admin-pusat.dashboard',
            $this->hasRole('admin-province') => 'admin-province.dashboard',
            $this->hasRole('admin-kab-kota') => 'admin-kab-kota.dashboard',
            default => 'portal-dashboard',
        };
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function scopeArea()
    {
        return $this->hasOne(UserScope::class);
    }

}
