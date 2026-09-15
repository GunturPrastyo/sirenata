<?php

namespace Modules\LMS\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\LMS\Models\Category;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\StudentContentProgress;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Kursus dengan penanda masuk "Kursus Saya" atau "Katalog"
        $courseData = [
            [
                'name' => 'Perencanaan Tenaga Kerja Makro',
                'category' => 'Perencanaan',
                'description' => 'Mempelajari metodologi penyusunan rencana tenaga kerja makro pada level nasional dan daerah dengan indikator demografi dan ketenagakerjaan.',
                'is_enrolled' => true, // Akan muncul di Kursus Saya
            ],
            [
                'name' => 'Perencanaan Tenaga Kerja Mikro',
                'category' => 'Praktik',
                'description' => 'Panduan teknis analisis kebutuhan dan ketersediaan tenaga kerja pada tingkat unit kerja dan instansi/perusahaan.',
                'is_enrolled' => true, // Akan muncul di Kursus Saya
            ],
            [
                'name' => 'Indeks Pembangunan Ketenagakerjaan (IPK)',
                'category' => 'Perkiraan',
                'description' => 'Kajian mendalam mengenai pengukuran performa indikator pembangunan ketenagakerjaan secara komprehensif.',
                'is_enrolled' => true, // Akan muncul di Kursus Saya
            ],
            [
                'name' => 'Analisis Kebutuhan Pelatihan Kerja',
                'category' => 'Praktik',
                'description' => 'Metodologi identifikasi gap kompetensi dan perencanaan diklat pegawai berbasis kebutuhan riil industri.',
                'is_enrolled' => false, // Akan murni masuk Katalog
            ],
            [
                'name' => 'Regulasi dan Kebijakan Ketenagakerjaan Terkini',
                'category' => 'Perpres',
                'description' => 'Tinjauan yuridis peraturan pemerintah dan instrumen kepatuhan hukum ketenagakerjaan di Indonesia.',
                'is_enrolled' => false, // Akan murni masuk Katalog
            ],
        ];

        // Palet Warna yang disesuaikan dengan referensi gambar (Muted/Deep)
        $brandColors = [
            '13416B', // Base Navy (Blok D/M/A)
            '547996', // Slate Blue (Blok E/H)
            '8BB1CC', // Light Blue (Blok I/F/O)
            '79A736', // Muted Green (Blok J)
            'E58A18', // Muted Orange (Blok K)
            '6E4B82'  // Muted Purple (Blok L)
        ];

        foreach ($courseData as $index => $item) {
            $cat = Category::where('name', $item['category'])->first() ?? Category::first();
            $bgColor = $brandColors[$index % count($brandColors)];

            // Ambil maksimal 2 kata pertama agar inisial API tidak mengambil tanda baca di akhir
            $words = explode(' ', trim($item['name']));
            $safeName = $words[0] . ' ' . ($words[1] ?? '');

            Course::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'category_id' => $cat?->id,
                    'name'        => $item['name'],
                    // Gunakan $safeName untuk API thumbnail
                    'thumbnail'   => 'https://ui-avatars.com/api/?name=' . urlencode($safeName) . '&background=' . $bgColor . '&color=fff&size=512&bold=true',
                    'description' => $item['description'],
                ]
            );
        }

        // 2. Logika Pendaftaran (Enrollment) User (Tetap Sama)
        $courses = Course::with(['sections.contents'])->get();

        $users = User::role('user')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        if ($users->isNotEmpty()) {
            foreach ($courses as $course) {
                // Assign Mentor
                $mentors = $users->take(1);
                foreach ($mentors as $mentor) {
                    $course->mentors()->syncWithoutDetaching([
                        $mentor->id => [
                            'is_active'  => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ],
                    ]);
                }

                $isEnrolledTarget = collect($courseData)->firstWhere('name', $course->name)['is_enrolled'] ?? false;

                if ($isEnrolledTarget) {
                    $allContents   = $course->sections->flatMap->contents;
                    $totalContents = $allContents->count();

                    foreach ($users as $student) {
                        $completedCount = $totalContents > 0 ? rand(0, $totalContents) : 0;
                        $progress = $totalContents > 0 ? (int) round(($completedCount / $totalContents) * 100) : 0;

                        $status = match (true) {
                            $progress === 0 => 'enrolled',
                            $progress < 100 => 'in_progress',
                            default         => 'completed',
                        };

                        $course->students()->syncWithoutDetaching([
                            $student->id => [
                                'status'       => $status,
                                'progress'     => $progress,
                                'completed_at' => $progress === 100 ? now() : null,
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]
                        ]);

                        if ($totalContents > 0 && $completedCount > 0) {
                            $contentsToComplete = $allContents->shuffle()->take($completedCount);
                            foreach ($contentsToComplete as $content) {
                                StudentContentProgress::firstOrCreate(
                                    [
                                        'user_id'            => $student->id,
                                        'section_content_id' => $content->id,
                                    ],
                                    [
                                        'completed_at' => now(),
                                        'created_at'   => now(),
                                        'updated_at'   => now(),
                                    ]
                                );
                            }
                        }
                    }
                }
            }
        }

        $this->command->info("CourseSeeder berhasil dengan warna thumbnail yang disesuaikan! 🚀");
    }
}
