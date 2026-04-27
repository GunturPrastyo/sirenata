<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\CourseService;

class CourseController extends Controller
{
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
        $perPage = $request->get('row_per_page', 5);
        $result = $this->courseService->myCourses(token: $token, page: $page, perPage: $perPage);

        return view('lms::user.course.my-course', [
            'courses' => $result['data'],
            'meta'    => $result['meta'],
            'links'   => $result['links'],
            'success' => $result['success'],
            'message' => $result['message'],
        ]);
    }
}
