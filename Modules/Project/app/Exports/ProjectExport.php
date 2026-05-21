<?php

namespace Modules\Project\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProjectExport implements
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
        private ?string $status = null,
    ) {}

    public function query()
    {
        $query = Project::with('leader')->latest();
        $query->where('type', ProjectType::NASIONAL->value);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Daftar Proyek';
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Proyek',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Durasi (Hari)',
            'Ketua Tim',
            'Status',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        return [
            $this->no,
            $row->name,
            $row->start_date ? $row->start_date->format('Y-m-d') : '-',
            $row->end_date ? $row->end_date->format('Y-m-d') : '-',
            $row->duration ?? '-',
            $row->leader?->profile?->full_name ?? $row->leader?->name ?? '-',
            $row->status,
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
