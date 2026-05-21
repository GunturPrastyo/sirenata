<?php

namespace Modules\LMS\Http\Controllers\AdminPusat\Course;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\LMS\Http\Requests\Api\StoreCourseRequest;
use Modules\LMS\Http\Requests\Api\UpdateCourseRequest;
use Modules\LMS\Models\Category;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Log;
use Modules\LMS\Models\Course;
use Modules\LMS\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $token = (string) session('api_token', '');

        $page       = $request->get('page', 1);
        $perPage    = $request->get('row_per_page', 12);
        $categoryId = $request->get('category_id');
        $search     = $request->get('search');

        $result = $this->courseService->allCourses(token: $token, page: $page, perPage: $perPage, search: $search, categoryId: $categoryId);
        $courses = collect(json_decode(json_encode($result['data'])));

        $categories = Category::select('id', 'name')->orderBy('name', 'asc')->get();
        return view('lms::admin-pusat.course.index', [
            'courses'    => $courses,
            'categories' => $categories,
            'meta'       => $result['meta'],
            'links'      => $result['links'],
            'success'    => $result['success'],
            'message'    => $result['message'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name', 'asc')->get();
        return view('lms::admin-pusat.course.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request)
    {
        try {
            $validated = $request->validated();

            $token = (string) session('api_token', '');

            $payload = [
                'category_id' => $request->input('category_id'),
                'name'        => $request->input('name'),
                'description' => $request->input('description'),
                // Tambahkan 'slug' => $request->input('slug') jika API Anda mewajibkan slug
            ];

            $thumbnailFile = $request->file('thumbnail');

            $result = $this->courseService->storeCourse(token: $token, data: $payload, thumbnailFile: $thumbnailFile);

            if ($result['success']) {
                ToastMagic::success('Course berhasil ditambahkan!');
                return redirect()->route('admin-pusat.management-course.courses.index');
            }

            return back()->withInput()->with('error', $result['message']);
        } catch (\Exception $e) {
            ToastMagic::error($e->getMessage());
            return back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(string $slug)
    {
        $token = (string) session('api_token', '');
        $courseResponse = $this->courseService->getCourseBySlug($token, $slug);
        $course = json_decode(json_encode($courseResponse['data']));
        if (!$course) {
            abort(404, 'Course tidak ditemukan');
        }
        // dd($course);
        return view('lms::admin-pusat.course.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $slug)
    {

        $token = (string) session('api_token', '');

        $courseResponse = $this->courseService->getCourseBySlug($token, $slug);

        if (!$courseResponse['success'] || empty($courseResponse['data'])) {
            abort(404, 'Course tidak ditemukan di server');
        }

        $course = json_decode(json_encode($courseResponse['data']));

        $categories = Category::select('id', 'name')->orderBy('name', 'asc')->get();

        return view('lms::admin-pusat.course.edit', compact('course', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, string $slug)
    {

        $validated = $request->validated();
        $token = (string) session('api_token', '');

        $payload = [
            'slug'        => $slug,
            'category_id' => $request->input('category_id'),
            'name'        => $request->input('name'),
            'description' => $request->input('description'),
        ];

        $thumbnailFile = $request->file('thumbnail');

        $result = $this->courseService->updateCourse($token, $payload, $thumbnailFile);

        if ($result['success']) {
            return redirect()->route('admin-pusat.management-course.courses.index')
                ->with('success', $result['message']);
        }

        return back()->withInput()->with('error', $result['message']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug)
    {
        $token = (string) session('api_token', '');
        if (empty($token)) {
            return redirect()->route('login')
                ->with('error', 'Sesi API Anda telah habis. Silakan login kembali.');
        }

        // 2. Panggil service untuk hapus data berdasarkan slug
        $result = $this->courseService->deleteCourse($token, $slug);

        // 3. Tangani response
        if ($result['success']) {
            return redirect()->route('admin-pusat.management-course.courses.index')
                ->with('success', $result['message']);
        }

        return back()->with('error', $result['message']);
    }
}
