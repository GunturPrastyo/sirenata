<?php

namespace Modules\LMS\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Http\Requests\Api\StoreSectionContentRequest;
use Modules\LMS\Http\Requests\Api\UpdateSectionContentRequest;
use Modules\LMS\Models\CourseSection;
use Modules\LMS\Models\SectionContent;
use Modules\LMS\Transformers\Api\SectionContentResource;

class SectionContentController extends Controller
{
    /**
     * GET /api/v1/sections/{section}/contents
     * List semua konten dalam section (harus sudah enroll)
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
     * GET /api/v1/contents/{content}
     * Detail konten + video URL (harus sudah enroll)
     */
    public function show(SectionContent $content): JsonResponse
    {
        return response()->json([
            'message' => 'Success',
            'data'    => new SectionContentResource($content),
        ]);
    }

    /**
     * POST /api/v1/sections/{section}/contents
     * Upload konten baru (admin)
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
     * PUT /api/v1/contents/{content}
     * Update konten — bisa ganti nama, video, atau position (admin)
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
     * DELETE /api/v1/contents/{content}
     * Hapus konten beserta file videonya (admin)
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
     * PATCH /api/v1/sections/{section}/contents/reorder
     * Ubah urutan konten dalam section (admin)
     */
    public function reorder(Request $request, CourseSection $section): JsonResponse
    {
        $request->validate([
            'contents'             => ['required', 'array'],
            'contents.*.id'        => ['required', 'uuid', 'exists:section_contents,id'],
            'contents.*.position'  => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->contents as $item) {
            $section->contents()
                ->where('id', $item['id'])
                ->update(['position' => $item['position']]);
        }

        return response()->json([
            'message' => 'Urutan konten berhasil diupdate',
        ]);
    }
}