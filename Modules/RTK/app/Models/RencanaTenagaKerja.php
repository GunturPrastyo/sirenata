<?php

namespace Modules\RTK\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;

// use Modules\RTK\Database\Factories\RencanaTenagaKerjaFactory;

class RencanaTenagaKerja extends Model
{
    use HasFactory;

    protected $casts = [
        'status' => RTKStatus::class,
        'type' => TypeRtk::class,
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
        'type',
        'document_path',
    ];

    public function getStatusColorAttribute(): string
    {
        return $this->status->color();
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
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

    // protected static function newFactory(): RencanaTenagaKerjaFactory
    // {
    //     // return RencanaTenagaKerjaFactory::new();
    // }
}
