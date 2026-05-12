<?php

namespace Modules\LMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;
use Modules\LMS\Models\SectionContent;

class CourseSectionSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->command->warn('Courses not found. Jalankan CourseFactory dulu.');
            return;
        }

        $sectionNames = [
            'Pengenalan & Persiapan',
            'Materi Dasar',
            'Materi Inti',
            'Studi Kasus & Praktik',
            'Evaluasi & Penutup',
        ];

        $contentNames = [
            'Pengenalan & Persiapan' => [
                'Apa itu course ini?',
                'Persiapan lingkungan belajar',
                'Overview materi',
            ],
            'Materi Dasar' => [
                'Konsep dasar',
                'Terminologi penting',
                'Latihan dasar',
            ],
            'Materi Inti' => [
                'Pembahasan mendalam bagian 1',
                'Pembahasan mendalam bagian 2',
                'Pembahasan mendalam bagian 3',
                'Latihan inti',
            ],
            'Studi Kasus & Praktik' => [
                'Studi kasus 1',
                'Studi kasus 2',
                'Praktik mandiri',
            ],
            'Evaluasi & Penutup' => [
                'Kuis evaluasi',
                'Rangkuman materi',
                'Penutup & langkah selanjutnya',
            ],
        ];

        foreach ($courses as $course) {
            foreach ($sectionNames as $position => $sectionName) {

                $section = CourseSection::create([
                    'course_id'   => $course->id,
                    'name'        => $sectionName,
                    'description' => null,
                    'position'    => $position + 1,
                ]);

                // Buat konten untuk tiap section
                $contents = $contentNames[$sectionName] ?? [];
                foreach ($contents as $contentPosition => $contentName) {
                    SectionContent::create([
                        'course_section_id' => $section->id,
                        'name'              => $contentName,
                        'position'          => $contentPosition + 1,
                    ]);
                }
            }
        }

        $totalSections = CourseSection::count();
        $totalContents = SectionContent::count();

        $this->command->info("Seeded {$totalSections} sections dan {$totalContents} contents 🚀");
    }
}