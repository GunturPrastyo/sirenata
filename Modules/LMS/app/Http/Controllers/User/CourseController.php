<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Services\CourseService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\CertificateSetting;
use Modules\LMS\Models\SectionContent;
use Modules\LMS\Services\Api\CourseProgressService;

class CourseController extends Controller
{
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';

    public function __construct(
        private CourseService $courseService
    ) {}

    public function allMyCourse(Request $request)
    {
        $token = (string) session('api_token', '');

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage);
        $courses = collect($result['data'])
            ->map(fn($item) => (object) $item);

        return view('lms::user.course.my-course', [
            'courses' => $courses,
            'meta'    => $result['meta'],
            'links'   => $result['links'],
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }


    public function myCourseProgress(Request $request)
    {

        $token = (string) session('api_token', '');

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage, status: self::IN_PROGRESS);
        $courses = collect($result['data'])
            ->map(fn($item) => (object) $item);

        return view('lms::user.course.my-course-progress', [
            'courses' => $courses,
            'meta'    => $result['meta'],
            'links'   => $result['links'],
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }
    public function myCourseFinish(Request $request)
    {
        $token = (string) session('api_token', '');

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage, status: self::COMPLETED);
        $courses = collect($result['data'])
            ->map(fn($item) => (object) $item);
        return view('lms::user.course.my-course-completed', [
            'courses' => $courses,
            'meta'    => $result['meta'],
            'links'   => $result['links'],
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    public function myCourseDetail(string $slug)
    {
        $token = (string) session('api_token', '');

        // 1. Ambil data progress dan struktur materi dari API
        $result = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);

        // Jadikan array terlebih dahulu agar mudah digabungkan
        $apiCourseData = $result['data'] ?? [];

        // 2. Ambil data master dari Database lokal untuk melengkapi (deskripsi, kategori, dll)
        $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();

        // 3. Gabungkan data API dengan data Database
        if ($dbCourse) {
            $apiCourseData['description']   = $dbCourse->description;
            $apiCourseData['category']      = $dbCourse->category ? $dbCourse->category->toArray() : null;
            $apiCourseData['thumbnail_url'] = $dbCourse->thumbnail_url;
            $apiCourseData['course_name']   = $dbCourse->name;
        }

        // Trik konversi aman: ubah array bertingkat kembali menjadi Object utuh
        $course = json_decode(json_encode($apiCourseData));

        return view('lms::user.course.my-course-detail', [
            'course'  => $course,
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }

    public function generateCertificate(string $slug)
    {
        $user = Auth::user();
        $course = Course::where('slug', $slug)->firstOrFail();

        // Check if student is enrolled
        $enrollment = $course->students()->wherePivot('user_id', $user->id)->first();

        if (!$enrollment) {
            ToastMagic::error('Anda belum terdaftar di kursus ini.');
            return redirect()->back();
        }

        // Check completion status
        if ($enrollment->pivot->status !== 'completed' || $enrollment->pivot->progress < 100) {
            ToastMagic::error('Anda belum menyelesaikan kursus ini.');
            return redirect()->back();
        }

        // Check if already generated
        if (!empty($enrollment->pivot->certificate_file)) {
            ToastMagic::warning('Sertifikat Anda sudah digenerate.');
            return redirect()->back();
        }

        // Get active certificate setting
        $activeSetting = CertificateSetting::getActive();
        if (!$activeSetting) {
            ToastMagic::error('Pengaturan sertifikat aktif belum diatur oleh admin.');
            return redirect()->back();
        }

        // Generate unique certificate code
        do {
            $certificateCode = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(6));
            $codeExists = DB::table('course_student')
                ->where('certificate_code', $certificateCode)
                ->exists();
        } while ($codeExists);

        // Convert template background and signature to base64 for PDF rendering stability
        $backgroundPath = storage_path('app/public/' . $activeSetting->background_image);
        $signaturePath = storage_path('app/public/' . $activeSetting->signature_image);

        $backgroundBase64 = '';
        if (file_exists($backgroundPath)) {
            $type = pathinfo($backgroundPath, PATHINFO_EXTENSION);
            $data = file_get_contents($backgroundPath);
            $backgroundBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $signatureBase64 = '';
        if (file_exists($signaturePath)) {
            $type = pathinfo($signaturePath, PATHINFO_EXTENSION);
            $data = file_get_contents($signaturePath);
            $signatureBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        $completedDate = Carbon::parse($enrollment->pivot->completed_at)->translatedFormat('d F Y');
        $fullName = $user->profile?->full_name ?? $user->name;

        // Render certificate using dompdf
        $pdf = Pdf::loadView('lms::admin-pusat.certificates.certificate-template', [
            'nama_peserta' => $fullName,
            'nama_kursus' => $course->name,
            'tanggal_selesai' => $completedDate,
            'nomor_sertifikat' => $certificateCode,
            'background_url' => $backgroundBase64,
            'signature_url' => $signatureBase64,
            'signer_name' => $activeSetting->signer_name,
            'signer_title' => $activeSetting->signer_title,
        ]);

        $pdf->setPaper('a4', 'landscape');
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);

        // Define file path
        $pdfFileName = 'certificates/pdfs/cert-' . $course->slug . '-' . $user->id . '.pdf';

        // Ensure directories exist
        if (!Storage::disk('public')->exists('certificates/pdfs')) {
            Storage::disk('public')->makeDirectory('certificates/pdfs');
        }

        // Put the generated PDF content into storage
        Storage::disk('public')->put($pdfFileName, $pdf->output());

        // Update the pivot table
        $course->students()->updateExistingPivot($user->id, [
            'certificate_code' => $certificateCode,
            'certificate_file' => $pdfFileName,
            'certificate_issued_at' => now(),
        ]);

        ToastMagic::success('Sertifikat Anda berhasil dibuat!');
        return redirect()->back();
    }

    public function completeContent(SectionContent $content)
    {
        $progressService = app(CourseProgressService::class);
        $result = $progressService->completeContent($content);

        if (!$result['success']) {
            ToastMagic::error($result['message']);
            return redirect()->back();
        }

        if ($result['is_completed']) {
            ToastMagic::success('Selamat! Anda telah menyelesaikan semua materi kursus ini! 🎉');
        } else {
            ToastMagic::success('Materi berhasil ditandai selesai. Progress: ' . $result['progress'] . '%');
        }

        return redirect()->back();
    }

    public function showContent(string $slug, string $contentId)
    {
        $token = (string) session('api_token', '');

        // Ambil data detail course untuk navigasi sidebar/breadcrumb jika diperlukan
        $result = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);
        $apiCourseData = $result['data'] ?? [];

        $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
        if ($dbCourse) {
            $apiCourseData['course_name'] = $dbCourse->name;
        }
        $course = json_decode(json_encode($apiCourseData));

        // Ambil data materi spesifik berdasarkan ID
        $content = \Modules\LMS\Models\SectionContent::findOrFail($contentId);

        return view('lms::user.course.content-show', [
            'course'  => $course,
            'content' => $content,
            'slug'    => $slug,
        ]);
    }
}
