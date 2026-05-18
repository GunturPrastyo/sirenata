<?php

namespace Modules\RTK\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RtkSurveyPeriod extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'rtk_survey_periods';

    protected $fillable = [
        'nama',
        'tahun',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Scope: hanya periode aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    /**
     * Label warna status untuk badge di view
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'tutup' => 'bg-red-50 text-red-700 border-red-200',
            default => 'bg-slate-50 text-slate-600 border-slate-200', // draft
        };
    }

    /**
     * Relasi ke hasil kuesioner
     */
    public function submissions()
    {
        return $this->hasMany(RtkPemanfaatanSubmission::class, 'period_id');
    }

    /**
     * Label teks status
     */

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aktif' => 'Aktif',
            'tutup' => 'Ditutup',
            default => 'Draft',
        };
    }
}
