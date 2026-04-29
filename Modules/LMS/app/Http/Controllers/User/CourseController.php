<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Services\CourseService;

class CourseController extends Controller
{
    const IN_PROGRESS = 'in_progress';
    const COMPLETED = 'completed';

     public function __construct(
        private CourseService $courseService
    ) {}

    public function allMyCourse(Request $request)
    {
        $user = $request->user();

        // Ambil token dari session kalau sudah ada
        $token = session('api_token');

        // Kalau belum ada di session, generate baru dan simpan
        if (! $token) {
            // Hapus token web-session lama kalau ada
            $user->tokens()->where('name', 'web-session')->delete();

            // Generate token baru dan simpan ke session
            $token = $user->createToken('web-session')->plainTextToken;
            session(['api_token' => $token]);
        }

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage);
        $courses = collect($result['data'])
            ->map(fn ($item) => (object) $item);

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
        $status = $request->get('status');
        $user = $request->user();

        // Ambil token dari session kalau sudah ada
        $token = session('api_token');

        // Kalau belum ada di session, generate baru dan simpan
        if (! $token) {
            // Hapus token web-session lama kalau ada
            $user->tokens()->where('name', 'web-session')->delete();

            // Generate token baru dan simpan ke session
            $token = $user->createToken('web-session')->plainTextToken;
            session(['api_token' => $token]);
        }

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage, status: self::IN_PROGRESS);
        $courses = collect($result['data'])
            ->map(fn ($item) => (object) $item);

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
        $user = $request->user();

        // Ambil token dari session kalau sudah ada
        $token = session('api_token');

        // Kalau belum ada di session, generate baru dan simpan
        if (! $token) {
            // Hapus token web-session lama kalau ada
            $user->tokens()->where('name', 'web-session')->delete();

            // Generate token baru dan simpan ke session
            $token = $user->createToken('web-session')->plainTextToken;
            session(['api_token' => $token]);
        }

        $page   = $request->get('page', 1);
        $perPage = $request->get('row_per_page', 11);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage, status: self::COMPLETED);
        $courses = collect($result['data'])
            ->map(fn ($item) => (object) $item);
            // dd($courses);
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
        $user = Auth::user();

        // Ambil token dari session kalau sudah ada
        $token = session('api_token');

        // Kalau belum ada di session, generate baru dan simpan
        if (! $token) {
            // Hapus token web-session lama kalau ada
            $user->tokens()->where('name', 'web-session')->delete();

            // Generate token baru dan simpan ke session
            $token = $user->createToken('web-session')->plainTextToken;
            session(['api_token' => $token]);
        }

        $result = $this->courseService->getCourseDetailSlug(token: $token, slug: $slug);
        
        $courses = (object) $result['data'];
        // dd($courses);
        return view('lms::user.course.my-course-detail', [
            'course' => $courses,
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }
}
