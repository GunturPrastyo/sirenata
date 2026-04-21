<?php

namespace Modules\LMS\Services\Api;

use Illuminate\Support\Facades\DB;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;
use Illuminate\Validation\ValidationException;

class CourseSectionService
{
    public function getSectionsBySlug(string $slug)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        return $course->sections()
            ->withCount('contents')
            ->with('contents')
            ->orderBy('position')
            ->get();
    }

    public function createSection(string $slug, array $data)
    {
        $course = Course::where('slug', $slug)->firstOrFail();

        // ambil posisi terakhir
        $lastPosition = $course->sections()->max('position') ?? 1;

        return $course->sections()->create([
            ...$data,
            'position' => $lastPosition + 1,
        ]);
    }

    public function updateSection(CourseSection $section, array $data)
    {
        // pastikan position tidak ikut diupdate
        unset($data['position']);

        $section->update($data);

        return $section->fresh(['contents']);
    }

    public function deleteSection(CourseSection $section): void
    {
        DB::transaction(function () use ($section) {

            $courseId = $section->course_id;
            $deletedPosition = $section->position;

            // hapus section
            $section->delete();

            // rapihin posisi setelahnya
            CourseSection::where('course_id', $courseId)
                ->where('position', '>', $deletedPosition)
                ->decrement('position');
        });
    }

    public function reorderSections(string $slug, array $sections): void
    {
        DB::transaction(function () use ($slug, $sections) {

            $course = Course::where('slug', $slug)->firstOrFail();

            // validasi: tidak boleh ada duplicate position
            $positions = collect($sections)->pluck('position');

            if ($positions->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'sections' => ['Position tidak boleh duplicate']
                ]);
            }

            // validasi: semua section harus milik course ini
            $validIds = $course->sections()->pluck('id')->toArray();

            foreach ($sections as $item) {
                if (!in_array($item['id'], $validIds)) {
                    throw ValidationException::withMessages([
                        'sections' => ['Ada section yang tidak valid']
                    ]);
                }
            }

            // update (loop masih OK karena sudah dalam transaction)
            foreach ($sections as $item) {
                $course->sections()
                    ->where('id', $item['id'])
                    ->update(['position' => $item['position']]);
            }
        });
    }
}
