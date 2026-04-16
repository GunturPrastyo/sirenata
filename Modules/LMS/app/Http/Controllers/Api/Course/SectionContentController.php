<?php

namespace Modules\LMS\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Http\Requests\Api\StoreSectionContentRequest;
use Modules\LMS\Http\Requests\Api\UpdateSectionContentRequest;
use Modules\LMS\Models\CourseSection;
use Modules\LMS\Models\SectionContent;
use Modules\LMS\Transformers\Api\SectionContentResource;

class SectionContentController extends Controller
{
    /**
     * Menampilkan list konten dalam section
     * 
     * 
     * List semua konten dalam section (harus sudah enroll / terdaftar),
     * Menampilkan SectionContentCourse berdasarkan courseSection Id
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function index(CourseSection $courseSection): JsonResponse
    {
        $contents = $courseSection->contents()->orderBy('position')->get();

        return response()->json([
            'message' => 'Success',
            'data'    => SectionContentResource::collection($contents),
        ]);
    }

    /**
     * List konten dalam section detail
     * 
     * 
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function show(SectionContent $content): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data'    => new SectionContentResource($content),
        ]);
    }

    /**
     * Menambahkan konten baru dalam section
     * 
     * Create SectionContentCourse berdasarkan courseSection Id
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function store(StoreSectionContentRequest $request, CourseSection $courseSection): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('video')) {
            $data['video'] = $request->file('video')
                ->store("courses/contents/{$courseSection->course_id}", 'public');
        }

        // Auto-set position ke urutan terakhir kalau tidak diisi
        $data['position'] = $request->position
            ?? $courseSection->contents()->max('position') + 1;

        $content = $courseSection->contents()->create($data);

        return response()->json([
            'message' => 'Konten berhasil ditambahkan',
            'data'    => new SectionContentResource($content),
        ], 201);
    }

    /**
     * Update konten — bisa ganti nama, video, atau position (admin)
     * 
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function update(UpdateSectionContentRequest $request, SectionContent $content): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('video')) {
            // Hapus video lama
            if ($content->video) {
                Storage::disk('public')->delete($content->video);
            }

            $data['video'] = $request->file('video')
                ->store("courses/contents/{$content->section->course_id}", 'public');
        }

        $content->update($data);

        return response()->json([
            'message' => 'Konten berhasil diupdate',
            'data'    => new SectionContentResource($content->fresh()),
        ]);
    }

    /**
     * Delete SectionContent
     * 
     * delete SectionContentCourse berdasarkan Id
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function destroy(SectionContent $content): JsonResponse
    {
        if ($content->video) {
            Storage::disk('public')->delete($content->video);
        }

        $content->delete();

        return response()->json([
            'message' => 'Konten berhasil dihapus',
        ]);
    }

    /**
     * Ubah urutan SectionContent
     * 
     * Ubah urutan SectionContent berdasarkan courseSection Id
     * 
     * @authenticated
     * @role:admin-pusat
     */
    public function reorder(Request $request, CourseSection $courseSection): JsonResponse
    {
        $request->validate([
            'contents'             => ['required', 'array'],
            'contents.*.id'        => ['required', 'uuid', 'exists:section_contents,id'],
            'contents.*.position'  => ['required', 'integer', 'min:0'],
        ]);
        
        foreach ($request->contents as $item) {
            $courseSection->contents()
            ->where('id', $item['id'])
            ->update(['position' => $item['position']]);
        }
        

        return response()->json([
            'message' => 'Urutan konten berhasil diupdate',
        ]);
    }
}