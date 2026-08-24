<?php

namespace Modules\LMS\Http\Controllers\AdminPusat\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\SectionContentService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Log;

// Import Model yang dibutuhkan untuk halaman Create
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CourseSection;

class SectionContentController extends Controller
{
    public function __construct(private SectionContentService $sectionContentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lms::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // 1. Tangkap parameter dari URL (query string)
        $courseSlug = $request->query('course_slug');
        $sectionId = $request->query('section_id');

        // 2. Ambil data Course dan Section dari database
        $course = Course::where('slug', $courseSlug)->firstOrFail();
        $section = CourseSection::findOrFail($sectionId);

        // 3. Arahkan ke view yang benar dan kirim datanya
        return view('lms::admin-pusat.section-content.create', compact('course', 'section'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Tambahkan validasi content_text
            $validated = $request->validate([
                'course_slug'       => 'required|string',
                'course_section_id' => 'required|string',
                'name'              => 'required|string|max:255',
                'video'             => 'nullable|string',
                'document'          => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'content_text'      => 'nullable|string',
            ]);

            $token = (string) session('api_token', '');

            // Tambahkan content_text ke payload API
            $payload = [
                'course_section_id' => $validated['course_section_id'],
                'name'              => $validated['name'],
                'video'             => $validated['video'] ?? null,
                'content_text'      => $validated['content_text'] ?? null,
            ];

            $documentFile = $request->file('document');

            // 3. Panggil service
            $result = $this->sectionContentService->storeSectionContent($token, $payload, file: $documentFile);

            if (!$result['success']) {
                ToastMagic::error($result['message']);
                return redirect()->back()->withInput();
            }

            ToastMagic::success($result['message']);
            return redirect()->route('admin-pusat.management-course.courses.show', $validated['course_slug']);
        } catch (\Exception $e) {
            ToastMagic::error('Terjadi kesalahan saat membuat konten bagian: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(Request $request, $id)
    {
        // 1. Ambil course_slug dari parameter URL (dikirim via href)
        $courseSlug = $request->query('course_slug');
        if (!$courseSlug) {
            abort(404, 'Course Slug tidak ditemukan.');
        }

        // 2. Ambil data Course
        $course = \Modules\LMS\Models\Course::where('slug', $courseSlug)->firstOrFail();

        // 3. Ambil data Content (Materi) beserta relasi Section-nya
        $content = \Modules\LMS\Models\SectionContent::with('section')->findOrFail($id);

        // 4. Kirim ke view
        return view('lms::admin-pusat.section-content.show', compact('course', 'content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $courseSlug = $request->query('course_slug');
        if (!$courseSlug) {
            abort(404, 'Course Slug tidak ditemukan.');
        }

        // Ambil data Course dan Content
        $course = \Modules\LMS\Models\Course::where('slug', $courseSlug)->firstOrFail();
        $content = \Modules\LMS\Models\SectionContent::with('section')->findOrFail($id);

        return view('lms::admin-pusat.section-content.edit', compact('course', 'content'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Tambahkan validasi content_text untuk update
            $validated = $request->validate([
                'course_slug'  => 'required|string',
                'name'         => 'required|string|max:255',
                'video'        => 'nullable|string',
                'document'     => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'content_text' => 'nullable|string',
            ]);

            $token = (string) session('api_token', '');

            $payload = [
                'name'         => $validated['name'],
                'video'        => $validated['video'] ?? null,
                'content_text' => $validated['content_text'] ?? null,
            ];

            // Ambil file jika ada
            $documentFile = $request->file('document');

            $result = $this->sectionContentService->updateContent(token: $token, contentId: $id, data: $payload, file: $documentFile);
            Log::info('SectionContentController::update result', ['result' => $result]);
            if (!$result['success']) {
                ToastMagic::error($result['message']);
                return redirect()->back()->withInput();
            }

            ToastMagic::success($result['message']);
            return redirect()->route('admin-pusat.management-course.courses.show', $validated['course_slug']);
        } catch (\Exception $e) {
            ToastMagic::error('Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        try {
            $request->validate([
                'course_slug' => 'required|string'
            ]);

            $token = (string) session('api_token', '');

            $result = $this->sectionContentService->deleteContent($token, $id);

            if (!$result['success']) {
                ToastMagic::error($result['message']);
                return redirect()->back();
            }

            ToastMagic::success($result['message']);

            // Redirect kembali ke halaman show course menggunakan slug
            return redirect()->route('admin-pusat.management-course.courses.show', $request->course_slug);
        } catch (\Exception $e) {
            ToastMagic::error('Terjadi kesalahan saat menghapus materi: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
