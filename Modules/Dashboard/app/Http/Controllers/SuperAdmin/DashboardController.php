<?php

namespace Modules\Dashboard\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Http\Requests\UpdateProfileRequest;
use Modules\Dashboard\Services\DashboardService;
use Devrabiul\ToastMagic\Facades\ToastMagic;

use App\Models\User;
use Modules\MasterData\Models\Institution;

class DashboardController extends Controller
{

    public function __construct(
        private DashboardService $dashbordService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Stats Data
        $totalUser = User::role('user')->count();
        $totalAdminPusat = User::role('admin-pusat')->count();
        $totalAdminProvinsi = User::role('admin-province')->count();
        $totalAdminKabKota = User::role('admin-kab-kota')->count();

        $totalLembaga = Institution::where('type', 'pusat')->count();
        $totalInstansi = Institution::where('type', 'daerah')->count();

        // 2. LMS Courses Count
        $totalCourses = 0;
        if (class_exists(\Modules\LMS\Models\Course::class)) {
            $totalCourses = \Modules\LMS\Models\Course::count();
        }

        // 3. Activity Logs
        $recentActivities = collect();
        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            try {
                $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(5)->get();
            } catch (\Exception $e) {
                // Keep empty collection
            }
        }

        return view('dashboard::pages.super-admin.index', [
            'user' => $user,
            'totalUser' => $totalUser,
            'totalAdminPusat' => $totalAdminPusat,
            'totalAdminProvinsi' => $totalAdminProvinsi,
            'totalAdminKabKota' => $totalAdminKabKota,
            'totalLembaga' => $totalLembaga,
            'totalInstansi' => $totalInstansi,
            'totalCourses' => $totalCourses,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('dashboard::pages.super-admin.profile', [
            'user' => $user,
        ]);
    }

    public function storeOrUpdateProfile(UpdateProfileRequest $request) :RedirectResponse
    {
        $user = Auth::user();
        $this->dashbordService->updateProfile($user, $request->validated());
        ToastMagic::success("Profile berhasil diupdate!");
        return to_route('super-admin.profile');
    }
}
