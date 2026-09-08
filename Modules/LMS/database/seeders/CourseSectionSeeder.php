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
            $this->command->warn('Courses kosong.');
            return;
        }

        $curriculum = [
            'Modul 1: Landasan & Konsep Dasar' => [
                'Pengantar dan Regulasi Acuan',
                'Prinsip Utama dan Ruang Lingkup',
            ],
            'Modul 2: Metodologi & Instrumen Analisis' => [
                'Teknik Pengumpulan Data Lapangan',
                'Formula Perhitungan dan Proyeksi',
            ],
            'Modul 3: Implementasi & Studi Kasus' => [
                'Simulasi Penghitungan Data Nyata',
                'Penyusunan Laporan dan Rekomendasi',
            ],
        ];

        foreach ($courses as $course) {
            $posSection = 1;
            foreach ($curriculum as $sectionTitle => $contentTitles) {
                $section = CourseSection::updateOrCreate(
                    [
                        'course_id' => $course->id,
                        'name'      => $sectionTitle,
                    ],
                    [
                        'description' => 'Pembahasan modul mengenai ' . strtolower($sectionTitle),
                        'position'    => $posSection++,
                    ]
                );

                $posContent = 1;
                foreach ($contentTitles as $title) {
                    SectionContent::updateOrCreate(
                        [
                            'course_section_id' => $section->id,
                            'name'              => $title,
                        ],
                        [
                            'content_text' => "<h3>Materi: {$title}</h3>"
                                . "<p>Modul ini membahas mengenai kaidah teknis dari <strong>{$course->name}</strong>.</p>"
                                . "<p>Tujuan pembelajaran adalah memastikan setiap peserta memahami indikator utama, kerangka perumusan kebijakan, dan teknik validasi data yang presisi.</p>"
                                . "<ul><li>Identifikasi variabel input primer</li><li>Kalkulasi berbasis acuan regulasi</li><li>Analisis disparitas dan proyeksi kebutuhan</li></ul>"
                                . "<p>Silakan pelajari materi ini dengan saksama sebelum melanjutkan ke evaluasi bab terkait.</p>",
                            'position'     => $posContent++,
                        ]
                    );
                }
            }
        }

        $this->command->info("CourseSectionSeeder materi & isi dummy berhasil dibuat 🚀");
    }
}