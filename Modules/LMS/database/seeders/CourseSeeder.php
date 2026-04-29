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
        Course::factory(10)->create();

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

            // ── Mentors (1–2 orang) ───────────────────────────────────────
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

            // Ambil semua konten dalam course ini (flatten dari semua sections)
            $allContents   = $course->sections->flatMap->contents;
            $totalContents = $allContents->count();

            // ── Students — semua user role 'user' ────────────────────────
            foreach ($users as $student) {

                // Random berapa konten yang sudah diselesaikan student ini
                $completedCount = $totalContents > 0
                    ? rand(0, $totalContents)
                    : 0;

                // Progress dihitung dari konten selesai / total konten
                $progress = $totalContents > 0
                    ? (int) round(($completedCount / $totalContents) * 100)
                    : 0;

                $status = match (true) {
                    $progress === 0 => 'enrolled',
                    $progress < 100 => 'in_progress',
                    default         => 'completed',
                };

                // Enroll student ke course
                $course->students()->syncWithoutDetaching([
                    $student->id => [
                        'status'       => $status,
                        'progress'     => $progress,
                        'completed_at' => $progress === 100 ? now() : null,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ],
                ]);

                // Seed student_content_progress sesuai konten yang selesai
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