<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\Log;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;

class CourseSectionService
{
    /**
     * Menyimpan data Course Section langsung ke database Monolith
     */
    public function storeCourseSection(array $data): array
    {
        try {
            // 1. Cari course berdasarkan slug
            $course = Course::where('slug', $data['slug'])->first();

            if (!$course) {
                return [
                    'success' => false,
                    'message' => 'Course tidak ditemukan di database.',
                ];
            }

            // 2. Tentukan posisi/urutan bagian (opsional tapi disarankan agar rapi)
            $maxPosition = CourseSection::where('course_id', $course->id)->max('position') ?? 0;

            // 3. Simpan langsung ke database melalui model
            $section = CourseSection::create([
                'course_id'   => $course->id,
                'name'        => $data['name'],
                'description' => $data['description'] ?? null,
                'position'    => $maxPosition + 1, // Agar bagian baru selalu ada di urutan bawah
            ]);

            return [
                'success' => true,
                'message' => 'Bagian materi berhasil ditambahkan',
                'data'    => $section,
            ];
        } catch (\Exception $e) {
            Log::error('CourseSectionService::storeCourseSection error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage(),
                'data'    => [],
            ];
        }
    }
}