<?php

namespace Modules\LMS\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\LMS\Models\Course;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        Course::factory(10)->create();

        $courses = Course::all();

        // Ambil hanya user dengan role 'user'
        $users = User::role('user')->get();

        if ($courses->isEmpty()) {
            $this->command->warn('Courses not found.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users with role "user" found.');
            return;
        }

        foreach ($courses as $course) {

            // Random Mentor (1–2 orang dari role user)
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

            // Random Student (10–30 orang dari role user)
            // $students = $users->random(rand(10, min(30, $users->count())));
            $students = $users;
            // Semua user dengan role 'user' di-enroll ke course ini
            foreach ($users as $student) {
                $progress = rand(0, 100);
                $status   = match (true) {
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
            }
        }

        $this->command->info("Seeded {$courses->count()} courses dengan {$users->count()} users (role: user) 🚀");
    }
}