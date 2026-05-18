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
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Policies\RencanaTenagaKerjaPolicy;
use Modules\RTK\Enums\StatusDocument;

// use Modules\RTK\Database\Factories\RencanaTenagaKerjaFactory;

#[UsePolicy(RencanaTenagaKerjaPolicy::class)]
class RencanaTenagaKerja extends Model
{
    use HasFactory, HasUuids;

    protected $casts = [
        'status_verification' => RTKStatusVerification::class,
        'status_document' => StatusDocument::class,
        'type' => TypeRtk::class,
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
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
        'status_verification',
        'status_document',
        'is_active',
        'type',
        'document_path',
        'approved_by',
        'approved_at',
        'rejected_reason',
    ];

    public function getStatusVerificationLabelAttribute(): string
    {
        return $this->status_verification->label();
    }

    public function getStatusVerificationColorAttribute(): string
    {
        return $this->status_verification->color();
    }

    public function getStatusDocumentLabelAttribute(): string
    {
        return $this->status_document->label();
    }

    public function getStatusDocumentColorAttribute(): string
    {
        return $this->status_document->color();
    }

    /**
     * RTK berlaku jika ketiga kondisi terpenuhi
     */
    public function getIsBerlakuAttribute(): bool
    {
        return $this->status_verification === RTKStatusVerification::APPROVED
            && $this->status_document === StatusDocument::VALID
            && $this->is_active === true;
    }

    /**
     * Ambil nama lengkap dari profile, jika tidak ada pakai nama default
     */
    public function getDisplayNameApproverAttribute(): string
    {
        return $this->approver?->profile?->full_name ?? $this->approver?->name ?? '-';
    }

    public function getDocumentUrlAttribute(): ?string
    {
        if (! $this->document_path) return null;
        return str_starts_with($this->document_path, 'http') ? $this->document_path : asset('storage/' . $this->document_path);
    }


    public function scopeBerlaku($query)
    {
        return $query->where('status_verification', RTKStatusVerification::APPROVED->value)
            ->where('status_document', StatusDocument::VALID->value)
            ->where('is_active', true);
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_code', 'code');
    }
}
