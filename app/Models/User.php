<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Modules\LMS\Models\Course;
use Modules\User\Enums\InstitutionType;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;
use Modules\User\Traits\HasScopeAccess;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Auth\Notifications\ResetPassword;
use Modules\Auth\Notifications\Auth\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasUuids, LogsActivity, HasScopeAccess;

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
            ->logOnly(['name', 'email']);
    }

    public function sendPasswordResetNotification($token)
    {
        // $this->notify(new ResetPassword($token));
        $this->notify(new ResetPasswordNotification($token));
    }

    #[Scope]
    protected function search(Builder $query, string $keyword): void
    {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhereHas('profile', fn($sub) => $sub->where('instansi', 'like', "%{$keyword}%"));
            // ->orWhereHas('enrolledCourses', fn($sub) => $sub->where('name', 'like', "%{$keyword}%"));
        });
    }

    #[Scope]
    protected function inProvince(Builder $query, string $provinceCode): void
    {
        $query->whereHas('scopeArea', fn($q) => $q->where('province_code', $provinceCode));
    }

    #[Scope]
    protected function inRegency(Builder $query, string $regencyCode): void
    {
        $query->whereHas('scopeArea', fn($q) => $q->where('regency_code', $regencyCode));
    }

    #[Scope]
    protected function provinceInstitution(Builder $query): void
    {
        $query->whereHas('profile', fn($q) => $q->where('institution_type', InstitutionType::PROVINSI));
    }

    #[Scope]
    protected function regencyInstitution(Builder $query): void
    {
        $query->whereHas('profile', fn($q) => $q->where('institution_type', InstitutionType::KAB_KOTA));
    }

    #[Scope]
    protected function hasEnrolledCourses(Builder $query, string $search = ''): void
    {
        $query->whereHas('enrolledCourses', function ($q) use ($search) {
            if ($search) {
                $q->where('name', 'like', "%{$search}%");
            }
        });
    }

    public function getRedirectRoute(): string
    {
        return match (true) {
            $this->hasRole('super-admin') => 'super-admin.dashboard',
            $this->hasRole('admin-pusat') => 'admin-pusat.dashboard',
            $this->hasRole('admin-province') => 'admin-province.dashboard',
            $this->hasRole('admin-kab-kota') => 'admin-kab-kota.dashboard',
            $this->hasRole('user') => 'user.dashboard',
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

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_student')
            ->withPivot([
                'status',
                'progress',
                'completed_at'
            ])
            ->withTimestamps();
    }
}
