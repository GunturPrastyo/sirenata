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

class RekapUserCourseInstansiExport implements
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
        private string       $instansi,
        private CourseService $courseService,
        private ?string      $courseId = null,
        private ?string      $search = null,
    ) {}

    public function query()
    {
        return $this->courseService->exportCourseEnrollmentsByInstansi(
            instansi: $this->instansi,
            courseId: $this->courseId,
            search: $this->search,
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function title(): string
    {
        return 'Rekap ' . $this->instansi;
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
