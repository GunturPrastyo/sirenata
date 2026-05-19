<?php

namespace Modules\LMS\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\StudentContentProgress;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courseNames = [
            'Perencanaan Tenaga Kerja Makro',
            'Perencanaan Tenaga Kerja Mikro',
            'Indeks Pembangunan Ketenagakerjaan',
            'Sistem Informasi Pasar Kerja',
            'Keselamatan dan Kesehatan Kerja Dasar'
        ];

        $categories = \Modules\LMS\Models\Category::all();

        foreach ($courseNames as $name) {
            Course::create([
                'category_id' => $categories->count() > 0 ? $categories->random()->id : null,
                'name' => $name,
                'slug' => \Illuminate\Support\Str::slug($name),
                'thumbnail' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random',
                'description' => 'Deskripsi untuk ' . $name . '. Kursus ini akan membahas dasar-dasar dan konsep secara mendalam.',
            ]);
        }

        $courses = Course::with(['sections.contents'])->get();
        $users   = User::role('user')->get();

        if ($courses->isEmpty()) {
            $this->command->warn('Courses not found.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users with role "user" found.');
            return;
        }

        foreach ($courses as $course) {


            $mentors = $users->random(rand(1, min(2, $users->count())));
            foreach ($mentors as $mentor) {
                $course->mentors()->syncWithoutDetaching([
                    $mentor->id => [
                        'is_active'  => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }


            $allContents   = $course->sections->flatMap->contents;
            $totalContents = $allContents->count();


            foreach ($users as $student) {


                $completedCount = $totalContents > 0
                    ? rand(0, $totalContents)
                    : 0;


                $progress = $totalContents > 0
                    ? (int) round(($completedCount / $totalContents) * 100)
                    : 0;

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
                    ],
                ]);


                if ($totalContents > 0 && $completedCount > 0) {
                    $contentsToComplete = $allContents
                        ->shuffle()
                        ->take($completedCount);

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

        $this->command->info("Seeded {$courses->count()} courses dengan {$users->count()} users (role: user) 🚀");
    }
}