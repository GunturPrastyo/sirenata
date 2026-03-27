<?php

namespace Modules\Dashboard\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Creasi\Nusa\Models\Province;
use Modules\User\Models\UserProfile;
use Spatie\Activitylog\Models\Activity;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Http\Requests\UpdateProfileRequest;
use Modules\Dashboard\Services\DashboardService;

class DashbordController extends Controller
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

        // Total Admin Pusat (users with role 'admin-pusat')
        $totalAdminPusat = User::role('admin-pusat')->count();

        // Admin Aktif (Admin Provinsi + Admin Kab/Kota)
        $adminAktif = User::role(['admin-province', 'admin-kab-kota'])->count();

        // Aktivitas Admin bulan ini (from activity_log table)
        $aktivitasAdmin = Activity::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        // Gender distribution from user_profiles
        $genderMale = UserProfile::where('gender', 'male')->count();
        $genderFemale = UserProfile::where('gender', 'female')->count();

        // SDM per Provinsi: count users (role 'user') grouped by province
        // Note: roles table uses 'uuid' as PK, model_has_roles uses 'model_uuid' and 'role_id'
        $userCountsByProvince = DB::table('user_scopes')
            ->join('users', 'user_scopes.user_id', '=', 'users.id')
            ->join('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_uuid')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.uuid')
            ->where('roles.name', 'user')
            ->whereNotNull('user_scopes.province_code')
            ->select('user_scopes.province_code', DB::raw('count(*) as total'))
            ->groupBy('user_scopes.province_code')
            ->get();

        // Map province codes to province names using Nusa Province model (separate SQLite DB)
        $provinceCodes = $userCountsByProvince->pluck('province_code')->toArray();
        $provinces = Province::whereIn('code', $provinceCodes)->pluck('name', 'code');

        $sdmPerProvinsi = $userCountsByProvince->map(function ($item) use ($provinces) {
            return (object) [
                'province_name' => $provinces[$item->province_code] ?? 'Unknown (' . $item->province_code . ')',
                'total' => $item->total,
            ];
        })->sortBy('province_name')->values();

        return view('dashboard::pages.admin-pusat.index', [
            'user' => $user,
            'totalAdminPusat' => $totalAdminPusat,
            'adminAktif' => $adminAktif,
            'aktivitasAdmin' => $aktivitasAdmin,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
            'sdmPerProvinsi' => $sdmPerProvinsi,
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('dashboard::pages.admin-pusat.profile', [
            'user' => $user,
        ]);
    }

    public function storeOrUpdateProfile(UpdateProfileRequest $request) :RedirectResponse
    {
        $user = Auth::user();

        $this->dashbordService->updateProfile($user, $request->validated());
        ToastMagic::success("Profile berhasil diupdate!");
        return to_route('admin-pusat.profile');
    }
}
