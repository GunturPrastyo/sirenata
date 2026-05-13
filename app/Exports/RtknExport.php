<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\RTK\Services\RTKNService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class RtknExport implements
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
        private RTKNService $rtknService,
        private ?string  $statusVerification = null,
        private ?string $statusDocument = null,
        private $isActive = null,
        private ?string $search = null,
    ) {}

    public function query()
    {
        return $this->rtknService->exportUserRTKN(
            search: $this->search,
            sortBy: 'desc',
            statusVerification: $this->statusVerification,
            statusDocument: $this->statusDocument,
            isActive: $this->isActive,
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Rencana Tenaga Nasional';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Nama Dokumen RTKN',
            'Tanggal Berlaku',
            'Tanggal Berakhir',
            'Status Verifikasi',
            'Status Dokumen',
            'RTKN Acuan',
            'Type',
            'Dokumen',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        return [
            $this->no,
            $row->user?->profile?->full_name ?? $row->user->name ?? '-',
            $row->name,
            $row->start_date,
            $row->end_date,
            $row->status_verification->label(),
            $row->status_document->label(),
            $row->is_active ? 'Ya' : 'Tidak',
            $row->type?->value,
            $row->document_path ?? '-',
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
