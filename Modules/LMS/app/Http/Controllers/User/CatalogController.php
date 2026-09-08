<?php

namespace Modules\LMS\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Services\CourseService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Modules\LMS\Models\Category;

class CatalogController extends Controller
{
    public function __construct(private CourseService $courseService) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $selectedCategories = $request->get('categories', []);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $enrolledCourseIds = $user ? $user->enrolledCourses()->pluck('courses.id')->toArray() : [];

        // Ambil kursus yang sudah difilter
        $courses = $this->courseService->getCatalogCourses(12, $search, $selectedCategories);
        
    
        $categories = Category::withCount(['courses' => function($query) use ($enrolledCourseIds) {
            $query->whereNotIn('id', $enrolledCourseIds);
        }])->orderBy('name', 'asc')->get();

        return view('lms::user.katalog.index', compact('courses', 'enrolledCourseIds', 'search', 'categories', 'selectedCategories'));
    }

    public function enroll(string $slug)
    {
        $result = $this->courseService->enrollUser($slug);
        
        if ($result['success']) {
            ToastMagic::success($result['message']);
            return redirect()->route('user.course.my-course.detail', $slug);
        }

        ToastMagic::error($result['message']);
        return redirect()->back();
    }
}