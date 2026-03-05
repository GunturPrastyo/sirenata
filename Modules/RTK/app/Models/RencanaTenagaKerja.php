<?php

namespace Modules\RTK\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Policies\RencanaTenagaKerjaPolicy;

// use Modules\RTK\Database\Factories\RencanaTenagaKerjaFactory;

#[UsePolicy(RencanaTenagaKerjaPolicy::class)]
class RencanaTenagaKerja extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'status' => RTKStatus::class,
        'type' => TypeRtk::class,
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'province_code',
        'regency_code',
        'name',
        'start_date',
        'end_date',
        'status',
        'is_active',
        'type',
        'document_path',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function isExpired(): bool
    {
        return (int) $this->end_date < now()->year;
    }

    /**
     * Get the user that owns the RencanaTenagaKerja
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
