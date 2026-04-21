<?php
namespace Modules\LMS\Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\LMS\Models\Course;
class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory(10)->create();
        $courses = Course::all();
        $users   = User::all();
        if ($courses->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Courses or Users not found.');
            return;
        }
        foreach ($courses as $course) {
            // 🔹 Random Mentor (1–2 orang)
            $mentors = $users->random(rand(1, min(2, $users->count())));
            foreach ($mentors as $mentor) {
                $course->mentors()->syncWithoutDetaching([
                    $mentor->id => [
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
            // 🔹 Random Student (10–30 orang dari 100 user)
            $students = $users->random(rand(10, min(30, $users->count())));
            foreach ($students as $student) {
                $progress = rand(0, 100);
                $status = match (true) {
                    $progress == 0 => 'enrolled',
                    $progress < 100 => 'in_progress',
                    default => 'completed'
                };
                $course->students()->syncWithoutDetaching([
                    $student->id => [
                        'status' => $status,
                        'progress' => $progress,
                        'completed_at' => $progress === 100 ? now() : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        }
        $this->command->info('Course enrollment seeded successfully 🚀');
    }
}