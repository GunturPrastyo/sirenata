<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Province;
use Modules\User\Enums\InstitutionType;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Modules\Dashboard\Http\Requests\UpdateProfileRequest;
use Modules\Dashboard\Services\DashboardService;
use Modules\LMS\Services\CourseService;
use Modules\LMS\Models\PostTestResult;
use Modules\LMS\Models\PostTest;

class UserDashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashbordService,
        private CourseService $courseService,
    ) {}

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        $provinces = Province::all();
        $stats = $this->courseService->myCourseStats();
        
        // --- DATA LAST COURSE ---
        $lastCourse = $this->courseService->getLastAccessedCourse();
        if ($lastCourse) {
            $dbLastCourse = \Modules\LMS\Models\Course::with('category')->where('slug', $lastCourse->slug)->first();
            if ($dbLastCourse) {
                $lastCourse->description = $dbLastCourse->description;
                $lastCourse->category_name = $dbLastCourse->category ? $dbLastCourse->category->name : null;
            }
        }
        
        // --- DATA RECENT COURSES ---
        $recentCourses = $this->courseService->getRecentCourses()->take(4);
        $recentCourses->transform(function ($course) {
            $dbCourse = \Modules\LMS\Models\Course::where('slug', $course->slug)->first();
            if ($dbCourse) {
                $course->description = $dbCourse->description;
            }
            return $course;
        });

        // =====================================================================================
        // DATA ANALITIK: PERBANDINGAN SKOR GLOBAL POST-TEST
        // =====================================================================================
        $userId = $user->id;
        $myCourses = $user->enrolledCourses()->get(); 
        
        $chartDataByCourse = [];

        if ($myCourses->count() > 0) {
            $courseIds = $myCourses->pluck('id');
            
            $allPostTests = PostTest::whereIn('course_id', $courseIds)->orderBy('id')->get();
            $postTestIds = $allPostTests->pluck('id');

            $userResults = PostTestResult::selectRaw('post_test_id, MAX(score) as user_score')
                ->where('user_id', $userId)
                ->whereIn('post_test_id', $postTestIds)
                ->groupBy('post_test_id')
                ->pluck('user_score', 'post_test_id');

            $cacheKey = 'avg_post_test_' . md5($postTestIds->toJson());
            $avgResults = Cache::remember($cacheKey, 3600, function () use ($postTestIds) {
                return PostTestResult::selectRaw('post_test_id, AVG(score) as avg_score')
                    ->whereIn('post_test_id', $postTestIds)
                    ->groupBy('post_test_id')
                    ->pluck('avg_score', 'post_test_id');
            });

            foreach ($myCourses as $c) {
                $courseTests = $allPostTests->where('course_id', $c->id);
                
                $labels = [];
                $uScores = [];
                $aScores = [];
                
                foreach ($courseTests as $test) {
                    $labels[] = strlen($test->title) > 15 ? substr($test->title, 0, 15) . '...' : $test->title;
                    $uScores[] = $userResults[$test->id] ?? 0;
                    $aScores[] = isset($avgResults[$test->id]) ? round($avgResults[$test->id], 1) : 0;
                }
                
                $chartDataByCourse[$c->id] = [
                    'course_id' => $c->id,
                    'course_name' => $c->name,
                    'labels' => $labels,
                    'user_scores' => $uScores,
                    'avg_scores' => $aScores,
                ];
            }
        }
        
        return view('dashboard::pages.user.index', [
            'profile' => $profile,
            'provinces' => $provinces,
            'stats' => $stats,
            'lastCourse' => $lastCourse,
            'recentCourses' => $recentCourses,
            'chartDataByCourse' => $chartDataByCourse,
        ]);
    }

    public function getRegencies(Request $request)
    {
        $request->validate(['province_code' => 'required|string']);
        $province = Province::where('code', $request->province_code)->firstOrFail();
        return response()->json(['success' => true, 'data' => $province->regencies]);
    }

    public function updateInstansi(Request $request)
    {
        $request->validate([
            'asalInstansi' => 'required|in:pusat,provinsi,kabkota',
            'instansi' => 'nullable|string|max:255',
            'instansi_lainnya' => 'nullable|string|max:255',
            'unit_kerja' => 'required|string|max:255',
            'province_code' => 'nullable|string',
            'regency_code' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = UserProfile::firstOrNew(['user_id' => $user->id]);

        $institutionType = match ($request->asalInstansi) {
            'pusat' => InstitutionType::PUSAT,
            'provinsi' => InstitutionType::PROVINSI,
            'kabkota' => InstitutionType::KAB_KOTA,
        };

        $instansi = $request->instansi === 'lainnya' ? $request->instansi_lainnya : $request->instansi;

        $profile->institution_type = $institutionType->value;
        $profile->instansi = $instansi;
        $profile->unit_kerja = $request->unit_kerja;
        $profile->save();

        UserScope::updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => $request->province_code ?? null,
                'regency_code' => $request->regency_code ?? null,
            ]
        );
        ToastMagic::success("Data instansi berhasil disimpan!");
        return redirect()->route('user.dashboard')->with('success', 'Data instansi berhasil disimpan.');
    }
    
    public function profile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $provinces = Province::all();
        return view('dashboard::pages.user.profile', compact('user', 'provinces'));
    }

    public function storeOrUpdateProfile(UpdateProfileRequest $request) :RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $validated = $request->validated();
        $data = $this->dashbordService->updateProfile($user, $validated);
        Log::info($data);
        ToastMagic::success("Profile berhasil diupdate!");
        return to_route('user.profile');
    }
}