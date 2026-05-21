<?php

namespace Modules\RTK\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\RTK\Models\RtkPemanfaatanSubmission;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class HasilPemanfaatanRtkdExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize,
    WithChunkReading
{
    private int $no = 0;

    public function __construct(
        private ?string $periodId = null,
        private ?string $q1PunyaRtkd = null,
        private ?string $q2JadiAcuan = null,
        private ?string $search = null,
    ) {}

    public function query()
    {
        $query = RtkPemanfaatanSubmission::with(['user.scopeArea.province', 'period', 'rtkDocument', 'creator']);

        if ($this->periodId) {
            $query->where('period_id', $this->periodId);
        }

        if ($this->q1PunyaRtkd !== null && $this->q1PunyaRtkd !== '') {
            if ($this->q1PunyaRtkd === 'ya') {
                $query->whereNotNull('rtk_document_id');
            } else {
                $query->whereNull('rtk_document_id');
            }
        }

        if ($this->q2JadiAcuan) {
            $query->where('q2_jadi_acuan', $this->q2JadiAcuan);
        }

        if ($this->search) {
            $search = $this->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('scopeArea.province', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Hasil Pemanfaatan RTKD';
    }

    public function headings(): array
    {
        return [
            'No',
            'Provinsi',
            'Tanggal Isi',
            'Punya RTKD',
            'Masa Berlaku',
            'Jadi Acuan',
            'Dok. Acuan',
            'Status Verifikasi',
            'Diisi Oleh',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        $dokAcuan = '-';
        if ($row->dokumen_acuan && count($row->dokumen_acuan) > 0) {
            $types = array_map(function ($doc) {
                return strtoupper($doc['doc_type']);
            }, $row->dokumen_acuan);
            $dokAcuan = implode(', ', $types);
        }

        $statusLabel = 'Pending';
        if ($row->status_verifikasi === 'verified') {
            $statusLabel = 'Disetujui';
        } elseif ($row->status_verifikasi === 'rejected') {
            $statusLabel = 'Revisi';
        }

        $oleh = ($row->creator && $row->creator->hasRole('admin-pusat')) ? 'Admin Pusat' : 'Mandiri';

        return [
            $this->no,
            $row->user->scopeArea?->province?->name ?? $row->user->name ?? 'Unknown',
            $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-',
            $row->rtk_document_id ? 'Ya' : 'Tidak',
            $row->rtk_document_id ? $row->rtkDocument->start_date . ' - ' . $row->rtkDocument->end_date : '-',
            ($row->q2_jadi_acuan === 'ya') ? 'Ya' : (($row->q2_jadi_acuan === 'tidak') ? 'Belum' : '-'),
            $dokAcuan,
            $statusLabel,
            $oleh,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }
}
