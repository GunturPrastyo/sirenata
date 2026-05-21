<?php

namespace Modules\LMS\Http\Controllers\AdminPusat\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\SectionContentService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Log;

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
    public function create()
    {
        return view('lms::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_slug'       => 'required|string',
                'course_section_id' => 'required|string',
                'name'              => 'required|string|max:255',
                'video'             => 'nullable|string',
                'document'          => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            $token = (string) session('api_token', '');


            $payload = [
                'course_section_id'  => $validated['course_section_id'],
                'name'              => $validated['name'],
                'video'             => $validated['video'] ?? null,
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
    public function show($id)
    {
        return view('lms::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('lms::edit');
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'course_slug'  => 'required|string',
                'name'         => 'required|string|max:255',
                'video'    => 'nullable|string',
                'document'     => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            ]);

            $token = (string) session('api_token', '');

            $payload = [
                'name'      => $validated['name'],
                'video' => $validated['video'] ?? null,
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
