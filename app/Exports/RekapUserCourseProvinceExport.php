<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Modules\LMS\Services\CourseService;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RekapUserCourseProvinceExport implements
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
        private string  $provinceName,
        private CourseService $courseService,
        private string  $provinceCode,
        private ?string $courseId = null,
        private ?string $search = null,
    ) {}

    /**
     * FromQuery — Laravel Excel stream query langsung ke Excel
     * tanpa load semua data ke memory
     */
    public function query()
    {
        return $this->courseService->exportCourseEnrollmentsByProvince(
            provinceCode: $this->provinceCode,
            courseId: $this->courseId,
            search: $this->search,
        );
    }

    /**
     * Proses 500 row per batch — tidak boros memory
     * Cocok untuk data ribuan hingga puluhan ribu
     */
    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Rekap ' . $this->provinceName;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Instansi',
            'Nama Kursus',
            'Status',
            'Progress (%)',
        ];
    }

    public function map($row): array
    {
        $this->no++;

        $status = match ($row->status) {
            'completed'   => 'Selesai',
            'in_progress' => 'Sedang Berjalan',
            'enrolled'    => 'Terdaftar',
            default       => $row->status,
        };

        return [
            $this->no,
            $row->user_full_name ?? $row->user_name,
            $row->instansi ?? '-',
            $row->course_name,
            $status,
            $row->progress . '%',
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
