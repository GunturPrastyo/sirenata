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

        // SDM per Provinsi
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

        $provinceCodes = $userCountsByProvince->pluck('province_code')->toArray();
        $provinces = Province::whereIn('code', $provinceCodes)->pluck('name', 'code');

        $sdmPerProvinsi = $userCountsByProvince->map(function ($item) use ($provinces) {
            return (object) [
                'province_name' => $provinces[$item->province_code] ?? 'Unknown (' . $item->province_code . ')',
                'total' => $item->total,
            ];
        })->sortBy('province_name')->values();

        // ====================================================================
        // PERBAIKAN 1: Masa Aktif RTK per Provinsi (Group By Province)
        // ====================================================================
        $availableRtkYears = RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)
            ->berlaku()
            ->pluck('start_date')
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $selectedRtkYear = $request->input('rtk_year', 'all');
        
        $queryRtk = RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)->berlaku();
            
        if ($selectedRtkYear !== 'all') {
            $queryRtk->where('end_date', '>=', (int) $selectedRtkYear);
        }
        
        $rtkProvinsi = $queryRtk->get();

        $rtkProvinceCodes = $rtkProvinsi->pluck('province_code')->toArray();
        $rtkProvinceNames = Province::whereIn('code', $rtkProvinceCodes)->pluck('name', 'code');

        $rtkMasaAktifPerProvinsi = $rtkProvinsi->groupBy('province_code')->map(function ($items, $code) use ($rtkProvinceNames) {
            $rtk = $items->first();
            return (object) [
                'province_name' => $rtkProvinceNames[$code] ?? 'Unknown',
                'sisa_tahun'    => max(0, (int) $rtk->end_date - (int) date('Y')),
                'start_date'    => (int) $rtk->start_date,
                'end_date'      => (int) $rtk->end_date,
                'total'         => $items->count(), // Tambahan agar grafik yang butuh jumlah tetap jalan
            ];
        })->sortBy('province_name')->values();

        // ====================================================================
        // PERBAIKAN 2: Data RTK filtered by end_date (Group By Province)
        // ====================================================================
        $availableRtkEndYears = RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)
            ->berlaku()
            ->pluck('end_date')
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $selectedRtkEndYear = $request->input('rtk_end_year', 'all');
        
        $queryRtkEnd = RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)->berlaku();
            
        if ($selectedRtkEndYear !== 'all') {
            $queryRtkEnd->where('end_date', (int) $selectedRtkEndYear);
        }
        
        $rtkProvinsiEnd = $queryRtkEnd->get();
        $rtkEndProvinceCodes = $rtkProvinsiEnd->pluck('province_code')->toArray();
        $rtkEndProvinceNames = Province::whereIn('code', $rtkEndProvinceCodes)->pluck('name', 'code');

        $rtkMasaBerakhirPerProvinsi = $rtkProvinsiEnd->groupBy('province_code')->map(function ($items, $code) use ($rtkEndProvinceNames) {
            $rtk = $items->first();
            return (object) [
                'province_name' => $rtkEndProvinceNames[$code] ?? 'Unknown',
                'sisa_tahun'    => max(0, (int) $rtk->end_date - (int) date('Y')),
                'start_date'    => (int) $rtk->start_date,
                'end_date'      => (int) $rtk->end_date,
                'total'         => $items->count(),
            ];
        })->sortBy('province_name')->values();

        // Status Distribusi RTK
        $rtkStatusDistribution = DB::table('rencana_tenaga_kerjas')
            ->select('status_verification as status', DB::raw('count(*) as total'))
            ->groupBy('status_verification')
            ->pluck('total', 'status');

        $maxOptionYear = !empty($availableRtkEndYears) ? max($availableRtkEndYears) : $currentYear;
        $minOptionYear = $currentYear;
        
        $rtkYearsOptions = [];
        for ($y = $maxOptionYear; $y >= $minOptionYear; $y--) {
            $rtkYearsOptions[] = $y;
        }

        $minAvailableEndYear = !empty($availableRtkEndYears) ? min($availableRtkEndYears) : $currentYear;
        $maxAvailableEndYear = !empty($availableRtkEndYears) ? max($availableRtkEndYears) : $currentYear;
        $rtkEndYearsOptions = [];
        for ($y = $maxAvailableEndYear; $y >= $minAvailableEndYear; $y--) {
            $rtkEndYearsOptions[] = $y;
        }

        if ($request->ajax()) {
            if ($request->has('rtk_end_year')) {
                return response()->json([
                    'rtkMasaBerakhirPerProvinsi' => $rtkMasaBerakhirPerProvinsi,
                ]);
            }
            if ($request->has('rtk_year')) {
                return response()->json([
                    'rtkMasaAktifPerProvinsi' => $rtkMasaAktifPerProvinsi,
                ]);
            }
            if ($request->has('sdm_year')) {
                return response()->json([
                    'sdmPerProvinsi' => $sdmPerProvinsi,
                ]);
            }
            return response()->json([
                'sdmPerProvinsi' => $sdmPerProvinsi,
                'rtkMasaAktifPerProvinsi' => $rtkMasaAktifPerProvinsi,
                'rtkMasaBerakhirPerProvinsi' => $rtkMasaBerakhirPerProvinsi,
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
            'rtkMasaBerakhirPerProvinsi' => $rtkMasaBerakhirPerProvinsi,
            'rtkStatusDistribution' => $rtkStatusDistribution,
            'sdmYears' => $sdmYears,
            'selectedSdmYear' => $selectedSdmYear,
            'selectedRtkYear' => $selectedRtkYear,
            'selectedRtkEndYear' => $selectedRtkEndYear,
            'rtkYearsOptions' => $rtkYearsOptions,
            'rtkEndYearsOptions' => $rtkEndYearsOptions,
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