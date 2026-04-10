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
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Enums\TypeRtk;
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
    public function index(Request $request)
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

        // Filter by Year for SDM
        $currentYear = (int) date('Y');
        $selectedSdmYear = (int) $request->input('sdm_year', $currentYear);

        $sdmYears = [];
        for ($y = $currentYear; $y >= $currentYear - 15; $y--) {
            $sdmYears[] = $y;
        }

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
            ->whereYear('users.created_at', $selectedSdmYear)
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

        // Masa Aktif RTK per Provinsi (active RTK per province with remaining years)
        $rtkProvinsi = RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)
            ->where('is_active', true)
            ->where('status', 'approved')
            ->get();

        $rtkProvinceCodes = $rtkProvinsi->pluck('province_code')->toArray();
        $rtkProvinceNames = Province::whereIn('code', $rtkProvinceCodes)->pluck('name', 'code');

        $rtkMasaAktifPerProvinsi = $rtkProvinsi->map(function ($rtk) use ($rtkProvinceNames) {
            return (object) [
                'province_name' => $rtkProvinceNames[$rtk->province_code] ?? 'Unknown',
                'sisa_tahun' => max(0, (int) $rtk->end_date - (int) date('Y')),
                'start_date' => $rtk->start_date,
                'end_date' => $rtk->end_date,
            ];
        })->sortBy('province_name')->values();

        // Status Distribusi RTK (all types)
        $rtkStatusDistribution = DB::table('rencana_tenaga_kerjas')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        if ($request->ajax()) {
            return response()->json([
                'sdmPerProvinsi' => $sdmPerProvinsi,
            ]);
        }

        return view('dashboard::pages.admin-pusat.index', [
            'user' => $user,
            'totalAdminPusat' => $totalAdminPusat,
            'adminAktif' => $adminAktif,
            'aktivitasAdmin' => $aktivitasAdmin,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
            'sdmPerProvinsi' => $sdmPerProvinsi,
            'rtkMasaAktifPerProvinsi' => $rtkMasaAktifPerProvinsi,
            'rtkStatusDistribution' => $rtkStatusDistribution,
            'sdmYears' => $sdmYears,
            'selectedSdmYear' => $selectedSdmYear,
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
