<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RtkRegencyByProvinceExport implements
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
        private ?string $search = null,
        private ?string $provinceCode = null,
        private ?string $statusVerification = null,
        private ?string $statusDocument = null,
        private ?string $isActive = null,
    ) {}

    /**
     * Query langsung ke RencanaTenagaKerja — ambil semua yang is_active = true per provinsi
     * Prioritaskan yang berlaku penuh (APPROVED + VALID + is_active)
     */
    public function query()
    {
        return RencanaTenagaKerja::query()
            ->with(['user.profile', 'approver.profile', 'province', 'regency'])
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $this->provinceCode)
            ->where('is_active', true)
            ->when($this->search, function ($q) {
                $q->whereHas('regency', fn($p) => $p->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('province', fn($p) => $p->where('name', 'like', "%{$this->search}%"))
                    ->orWhere('name', 'like', "%{$this->search}%");
            })
            ->when($this->statusVerification, fn($q) => $q->where('status_verification', $this->statusVerification))
            ->when($this->statusDocument, fn($q) => $q->where('status_document', $this->statusDocument))
            ->when($this->isActive !== null && $this->isActive !== '', function ($q) {
                $q->where('is_active', filter_var($this->isActive, FILTER_VALIDATE_BOOLEAN));
            })
            ->orderByRaw("
                CASE
                    WHEN status_verification = ? AND status_document = ? THEN 0
                    ELSE 1
                END ASC,
                province_code ASC
            ", [
                RTKStatusVerification::APPROVED->value,
                StatusDocument::VALID->value,
            ]);
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'RTK Provinsi';
    }

    public function headings(): array
    {
        return [
            'No',
            'Provinsi',
            'Nama Dokumen RTK',
            'Tanggal Mulai',
            'Tanggal Berakhir',
            'Status Verifikasi',
            'Status Dokumen',
            'RTK Acuan',
            'RTK Berlaku',
            'File Dokumen RTK Provinsi',
            'Diverifikasi Oleh',
            'Tanggal Diverifikasi',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        $isBerlaku = $row->status_verification === RTKStatusVerification::APPROVED
            && $row->status_document === StatusDocument::VALID
            && $row->is_active;

        return [
            $this->no,
            $row->province?->name ?? $row->province_code,
            $row->name,
            $row->start_date,
            $row->end_date,
            $row->status_verification->label(),
            $row->status_document->label(),
            $row->is_active ? 'Ya' : 'Tidak',
            $isBerlaku ? 'Berlaku' : 'Tidak Berlaku',
            $row->document_url ?? '-',
            $row->approver?->profile?->full_name ?? $row->approver?->name ?? '-',
            $row->approved_at?->format('d M Y') ?? '-',
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
