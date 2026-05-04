<?php

namespace Modules\RTK\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtkPemanfaatanSubmission extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rtk_pemanfaatan_submissions';

    protected $fillable = [
        'user_id',
        'period_id',
        'q1_punya_rtkd',
        'tahun_dari',
        'tahun_sampai',
        'rtk_document_id',
        'q2_jadi_acuan',
        'dokumen_acuan',
        'komponen_acuan',
        'alasan_tidak_punya',
        'alasan_belum_acuan',
        'dokumen_uploads',
        'status_verifikasi',
        'catatan_verifikasi',
        'field_verifications',
    ];

    protected $casts = [
        'dokumen_acuan' => 'array',
        'komponen_acuan' => 'array',
        'alasan_tidak_punya' => 'array',
        'alasan_belum_acuan' => 'array',
        'dokumen_uploads' => 'array',
        'field_verifications' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(RtkSurveyPeriod::class, 'period_id');
    }

    public function rtkDocument(): BelongsTo
    {
        return $this->belongsTo(RencanaTenagaKerja::class, 'rtk_document_id');
    }
}
