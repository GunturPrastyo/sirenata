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

        $courses = collect($result['data'])->map(function ($item) {
            $courseObj = (object) $item;
            $slug = $courseObj->slug ?? null;

            // Inisialisasi default
            $courseObj->total_modul = 0;
            $courseObj->total_materi = 0;

            if ($slug) {
                $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
                if ($dbCourse) {
                    $courseObj->description = $dbCourse->description;
                    $courseObj->category = $dbCourse->category ? (object) $dbCourse->category->toArray() : null;
                    $courseObj->thumbnail_url = $dbCourse->thumbnail_url ?? $courseObj->thumbnail_url;
                    
                    // Hitung Modul & Materi dari Database Lokal
                    $sectionIds = $dbCourse->sections()->pluck('id');
                    $courseObj->total_modul = $sectionIds->count();
                    $courseObj->total_materi = \Modules\LMS\Models\SectionContent::whereIn('course_section_id', $sectionIds)->count();
                }
            }
            return $courseObj;
        });

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

        $courses = collect($result['data'])->map(function ($item) {
            $courseObj = (object) $item;
            $slug = $courseObj->slug ?? null;

            $courseObj->total_modul = 0;
            $courseObj->total_materi = 0;

            if ($slug) {
                $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
                if ($dbCourse) {
                    $courseObj->description = $dbCourse->description;
                    $courseObj->category = $dbCourse->category ? (object) $dbCourse->category->toArray() : null;
                    $courseObj->thumbnail_url = $dbCourse->thumbnail_url ?? $courseObj->thumbnail_url;

                    $sectionIds = $dbCourse->sections()->pluck('id');
                    $courseObj->total_modul = $sectionIds->count();
                    $courseObj->total_materi = \Modules\LMS\Models\SectionContent::whereIn('course_section_id', $sectionIds)->count();
                }
            }
            return $courseObj;
        });

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

        $courses = collect($result['data'])->map(function ($item) {
            $courseObj = (object) $item;
            $slug = $courseObj->slug ?? null;

            $courseObj->total_modul = 0;
            $courseObj->total_materi = 0;

            if ($slug) {
                $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
                if ($dbCourse) {
                    $courseObj->description = $dbCourse->description;
                    $courseObj->category = $dbCourse->category ? (object) $dbCourse->category->toArray() : null;
                    $courseObj->thumbnail_url = $dbCourse->thumbnail_url ?? $courseObj->thumbnail_url;

                    $sectionIds = $dbCourse->sections()->pluck('id');
                    $courseObj->total_modul = $sectionIds->count();
                    $courseObj->total_materi = \Modules\LMS\Models\SectionContent::whereIn('course_section_id', $sectionIds)->count();
                }
            }
            return $courseObj;
        });

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

        $result = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);
        $apiCourseData = $result['data'] ?? [];
        $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();

        if ($dbCourse) {
            $apiCourseData['description']   = $dbCourse->description;
            $apiCourseData['category']      = $dbCourse->category ? $dbCourse->category->toArray() : null;
            $apiCourseData['thumbnail_url'] = $dbCourse->thumbnail_url;
            $apiCourseData['course_name']   = $dbCourse->name;
        }

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

        $enrollment = $course->students()->wherePivot('user_id', $user->id)->first();

        if (!$enrollment) {
            ToastMagic::error('Anda belum terdaftar di kursus ini.');
            return redirect()->back();
        }

        if ($enrollment->pivot->status !== 'completed' || $enrollment->pivot->progress < 100) {
            ToastMagic::error('Anda belum menyelesaikan kursus ini.');
            return redirect()->back();
        }

        $activeSetting = CertificateSetting::getActive();
        if (!$activeSetting) {
            ToastMagic::error('Pengaturan sertifikat aktif belum diatur oleh admin.');
            return redirect()->back();
        }

        $certificateCode = $enrollment->pivot->certificate_code;
        $issuedAt = $enrollment->pivot->certificate_issued_at ?? now();

        if (empty($certificateCode)) {
            do {
                $certificateCode = 'CERT-' . date('Y') . '-' . strtoupper(Str::random(6));
                $codeExists = DB::table('course_student')
                    ->where('certificate_code', $certificateCode)
                    ->exists();
            } while ($codeExists);
            $issuedAt = now();
        }

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

        $completedDate = Carbon::parse($issuedAt)->translatedFormat('d F Y');
        $fullName = $user->profile?->full_name ?? $user->name;

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

        $pdf->setPaper('a5', 'landscape');
        $pdf->getDomPDF()->set_option('isRemoteEnabled', true);

        $pdfFileName = 'certificates/pdfs/cert-' . $course->slug . '-' . $user->id . '.pdf';

        if (!Storage::disk('public')->exists('certificates/pdfs')) {
            Storage::disk('public')->makeDirectory('certificates/pdfs');
        }

        Storage::disk('public')->put($pdfFileName, $pdf->output());

        $course->students()->updateExistingPivot($user->id, [
            'certificate_code' => $certificateCode,
            'certificate_file' => $pdfFileName,
            'certificate_issued_at' => $issuedAt,
        ]);

        ToastMagic::success('Sertifikat Anda berhasil disiapkan dengan data terbaru!');
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

        $result = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);
        $apiCourseData = $result['data'] ?? [];

        $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
        if ($dbCourse) {
            $apiCourseData['course_name'] = $dbCourse->name;
        }
        $course = json_decode(json_encode($apiCourseData));

        $content = \Modules\LMS\Models\SectionContent::findOrFail($contentId);

        return view('lms::user.course.content-show', [
            'course'  => $course,
            'content' => $content,
            'slug'    => $slug,
        ]);
    }

    public function submitTest(Request $request, string $slug, string $postTestId)
    {
        $postTest = \Modules\LMS\Models\PostTest::with('questions.choices')->findOrFail($postTestId);
        $userAnswers = $request->input('answers', []); 

        $totalQuestions = $postTest->questions->count();
        if ($totalQuestions === 0) {
            ToastMagic::error('Tidak ada soal pada ujian ini.');
            return redirect()->route('user.course.my-course.detail', $slug);
        }

        $correctCount = 0;

        foreach ($postTest->questions as $question) {
            $userSelectedChoiceId = $userAnswers[$question->id] ?? null;
            if ($userSelectedChoiceId) {
                $correctChoice = $question->choices->where('is_correct', true)->first();
                if ($correctChoice && $correctChoice->id === $userSelectedChoiceId) {
                    $correctCount++;
                }
            }
        }

        $score = round(($correctCount / $totalQuestions) * 100);
        $isPassed = $score >= $postTest->passing_score;

        $userId = \Illuminate\Support\Facades\Auth::id();

        $existingRecord = \Illuminate\Support\Facades\DB::table('post_test_results')
            ->where('user_id', $userId)
            ->where('post_test_id', $postTest->id)
            ->first();

        if ($existingRecord) {
            \Illuminate\Support\Facades\DB::table('post_test_results')
                ->where('id', $existingRecord->id)
                ->update([
                    'score'        => $score,
                    'is_passed'    => $isPassed,
                    'completed_at' => $isPassed ? now() : null,
                    'updated_at'   => now(),
                ]);
        } else {
            \Illuminate\Support\Facades\DB::table('post_test_results')->insert([
                'id'           => \Illuminate\Support\Str::uuid()->toString(),
                'user_id'      => $userId,
                'post_test_id' => $postTest->id,
                'score'        => $score,
                'is_passed'    => $isPassed,
                'completed_at' => $isPassed ? now() : null,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        if (is_null($postTest->course_section_id) && $isPassed) {
            $course = \Modules\LMS\Models\Course::where('slug', $slug)->first();
            if ($course) {
                $course->students()->updateExistingPivot($userId, [
                    'status' => 'completed',
                    'progress' => 100,
                ]);
            }
        }

        if ($isPassed) {
            ToastMagic::success("Selamat! Anda lulus ujian dengan nilai {$score} (KKM: {$postTest->passing_score}). Modul selanjutnya telah terbuka! 🎉");
        } else {
            ToastMagic::error("Nilai Anda {$score}. Belum mencapai KKM ({$postTest->passing_score}). Silakan coba pelajari materi kembali dan ulangi tes.");
        }

        return redirect()->route('user.course.my-course.detail', $slug);
    }

    public function showTest(\Illuminate\Http\Request $request, string $slug, string $postTestId)
    {
        $token = (string) session('api_token', '');

        $resultData = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);
        $apiCourseData = $resultData['data'] ?? [];
        $dbCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $slug)->first();
        if ($dbCourse) {
            $apiCourseData['course_name'] = $dbCourse->name;
        }
        $course = json_decode(json_encode($apiCourseData));

        $postTest = \Modules\LMS\Models\PostTest::with('questions.choices')->findOrFail($postTestId);

        $result = \Illuminate\Support\Facades\DB::table('post_test_results')
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('post_test_id', $postTestId)
            ->first();

        if ($request->query('retake')) {
            $result = null;
        }

        return view('lms::user.course.test-show', [
            'course'   => $course,
            'slug'     => $slug,
            'postTest' => $postTest,
            'result'   => $result,
        ]);
    }
}