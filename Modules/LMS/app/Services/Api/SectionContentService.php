<?php

namespace Modules\LMS\Services\Api;

use App\Http\Resources\PaginateResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Models\CourseSection;
use Modules\LMS\Models\SectionContent;
use Modules\LMS\Transformers\Api\SectionContentResource;

class SectionContentService
{
    /**
     * List semua konten dalam section
     */
    public function getContents(CourseSection $courseSection): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $contents = $courseSection->contents()->orderBy('position')->get();

        return SectionContentResource::collection($contents);
    }

    /**
     * Detail konten
     */
    public function getContent(SectionContent $content): SectionContentResource
    {
        return new SectionContentResource($content);
    }

    /**
     * Tambah konten baru ke section
     */
    public function store(array $validated, CourseSection $courseSection, $file = null): array
    {

        // 2. Proses File Dokumen jika ada
        if ($file) {
            $validated['document'] = $file->store(
                "courses/contents/{$courseSection->course_id}",
                'public'
            );
        }

        // 3. Auto-set position
        $validated['position'] = $validated['position']
            ?? $courseSection->contents()->max('position') + 1;

        $content = $courseSection->contents()->create($validated);

        return [
            'success' => true,
            'message' => 'Konten berhasil ditambahkan',
            'content' => $content,
        ];
    }

    /**
     * Update konten
     */
    public function update(array $validated, SectionContent $content, $file = null): array
    {
        if ($file) {
            if ($content->document) {
                Storage::disk('public')->delete($content->document);
            }

            $validated['document'] = $file->store(
                "courses/contents/{$content->section->course_id}",
                'public'
            );
        }

        // 3. Update data (termasuk path dokumen baru jika ada, atau URL video)
        $content->update($validated);

        return [
            'success' => true,
            'code'    => 200,
            'message' => 'Konten berhasil diupdate',
            'content' => $content->fresh(),
        ];
    }

    /**
     * Hapus konten beserta file videonya
     */
    public function destroy(SectionContent $content): void
    {
        DB::transaction(function () use ($content) {

            $sectionId = $content->course_section_id;
            $deletedPosition = $content->position;

            // hapus file video dulu
            if ($content->video) {
                Storage::disk('public')->delete($content->video);
            }

            // hapus data
            $content->delete();

            // rapihin posisi setelahnya
            SectionContent::where('course_section_id', $sectionId)
                ->where('position', '>', $deletedPosition)
                ->decrement('position');
        });
    }

    /**
     * Ubah urutan konten dalam section
     */
    public function reorder(array $contents, CourseSection $courseSection): array
    {
        foreach ($contents as $item) {
            $courseSection->contents()
                ->where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return [
            'success' => true,
            'code'    => 200,
            'message' => 'Urutan konten berhasil diupdate',
        ];
    }
}
