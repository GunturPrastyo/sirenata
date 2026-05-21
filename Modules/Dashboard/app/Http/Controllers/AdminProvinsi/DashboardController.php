<?php

namespace Modules\Dashboard\Http\Controllers\AdminProvinsi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Http\Requests\UpdateProfileRequest;
use Modules\Dashboard\Services\DashboardService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Http\Request;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Enums\TypeRtk;

class DashboardController extends Controller
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
        $userScope = \Modules\User\Models\UserScope::where('user_id', $user->id)->first();
        $provinceCode = $userScope ? $userScope->province_code : null;

        // Base query for users taking courses in this province
        $baseUserQuery = \Illuminate\Support\Facades\DB::table('user_scopes')
            ->join('users', 'user_scopes.user_id', '=', 'users.id')
            ->join('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_uuid')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.uuid')
            ->where('roles.name', 'user')
            ->where('user_scopes.province_code', $provinceCode);

        // Filter by Year for SDM
        $currentYear = (int) date('Y');
        $selectedSdmYear = (int) $request->input('sdm_year', $currentYear);

        $sdmYears = [];
        for ($y = $currentYear; $y >= $currentYear - 15; $y--) {
            $sdmYears[] = $y;
        }

        // SDM per Kab/Kota
        $userCountsByRegency = (clone $baseUserQuery)
            ->whereYear('users.created_at', $selectedSdmYear)
            ->whereNotNull('user_scopes.regency_code')
            ->select('user_scopes.regency_code', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('user_scopes.regency_code')
            ->get();

        $regencyCodes = $userCountsByRegency->pluck('regency_code')->toArray();
        $regencies = \Creasi\Nusa\Models\Regency::whereIn('code', $regencyCodes)->pluck('name', 'code');

        $sdmPerKabKota = $userCountsByRegency->map(function ($item) use ($regencies) {
            return (object) [
                'regency_name' => collect(explode(' ', $regencies[$item->regency_code] ?? 'Unknown (' . $item->regency_code . ')'))->map(fn($w) => ucfirst(strtolower($w)))->join(' '),
                'total' => $item->total,
            ];
        });

        // Gender stats
        $genders = (clone $baseUserQuery)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('user_profiles.gender', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('user_profiles.gender')
            ->pluck('total', 'gender');

        $genderMale = $genders->get('male', 0);
        $genderFemale = $genders->get('female', 0);

        // Masa Aktif RTK per Kab/Kota (active RTK per regency with remaining years)
        $availableRtkYears = RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->berlaku()
            ->pluck('start_date')
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $selectedRtkYear = $request->input('rtk_year', 'all');
        
        $queryRtk = RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->berlaku();
            
        if ($selectedRtkYear !== 'all') {
            $queryRtk->where('end_date', '>=', (int) $selectedRtkYear);
        }
        
        $rtkKabKota = $queryRtk->get();

        $rtkRegencyCodes = $rtkKabKota->pluck('regency_code')->toArray();
        $rtkRegencyNames = \Creasi\Nusa\Models\Regency::whereIn('code', $rtkRegencyCodes)->pluck('name', 'code');

        $rtkMasaAktifPerKabKota = $rtkKabKota->map(function ($rtk) use ($rtkRegencyNames) {
            $rawName = $rtkRegencyNames[$rtk->regency_code] ?? 'Unknown';
            return (object) [
                'regency_name' => collect(explode(' ', $rawName))->map(fn($w) => ucfirst(strtolower($w)))->join(' '),
                'sisa_tahun' => max(0, (int) $rtk->end_date - (int) date('Y')),
                'start_date' => $rtk->start_date,
                'end_date' => $rtk->end_date,
            ];
        })->sortBy('regency_name')->values();

        // Data for second chart: RTK filtered by end_date
        $availableRtkEndYears = RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->berlaku()
            ->pluck('end_date')
            ->unique()
            ->sortDesc()
            ->values()
            ->toArray();

        $selectedRtkEndYear = $request->input('rtk_end_year', 'all');
        
        $queryRtkEnd = RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->berlaku();
            
        if ($selectedRtkEndYear !== 'all') {
            $queryRtkEnd->where('end_date', (int) $selectedRtkEndYear);
        }
        
        $rtkKabKotaEnd = $queryRtkEnd->get();
        $rtkEndRegencyCodes = $rtkKabKotaEnd->pluck('regency_code')->toArray();
        $rtkEndRegencyNames = \Creasi\Nusa\Models\Regency::whereIn('code', $rtkEndRegencyCodes)->pluck('name', 'code');

        $rtkMasaBerakhirPerKabKota = $rtkKabKotaEnd->map(function ($rtk) use ($rtkEndRegencyNames) {
            $rawName = $rtkEndRegencyNames[$rtk->regency_code] ?? 'Unknown';
            return (object) [
                'regency_name' => collect(explode(' ', $rawName))->map(fn($w) => ucfirst(strtolower($w)))->join(' '),
                'sisa_tahun' => max(0, (int) $rtk->end_date - (int) date('Y')),
                'start_date' => $rtk->start_date,
                'end_date' => $rtk->end_date,
            ];
        })->sortBy('regency_name')->values();

        // Status Distribusi RTK di provinsi ini
        $rtkStatusDistribution = \Illuminate\Support\Facades\DB::table('rencana_tenaga_kerjas')
            ->where('province_code', $provinceCode)
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->select('status_verification as status', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
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
                    'rtkMasaBerakhirPerKabKota' => $rtkMasaBerakhirPerKabKota,
                ]);
            }
            if ($request->has('rtk_year')) {
                return response()->json([
                    'rtkMasaAktifPerKabKota' => $rtkMasaAktifPerKabKota,
                ]);
            }
            if ($request->has('sdm_year')) {
                return response()->json([
                    'sdmPerKabKota' => $sdmPerKabKota,
                ]);
            }
            return response()->json([
                'sdmPerKabKota' => $sdmPerKabKota,
                'rtkMasaAktifPerKabKota' => $rtkMasaAktifPerKabKota,
                'rtkMasaBerakhirPerKabKota' => $rtkMasaBerakhirPerKabKota,
            ]);
        }

        return view('dashboard::pages.admin-provinsi.index', [
            'user' => $user,
            'sdmPerKabKota' => $sdmPerKabKota,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
            'rtkMasaAktifPerKabKota' => $rtkMasaAktifPerKabKota,
            'rtkMasaBerakhirPerKabKota' => $rtkMasaBerakhirPerKabKota,
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
        return view('dashboard::pages.admin-provinsi.profile', [
            'user' => $user,
        ]);
    }

    public function storeOrUpdateProfile(UpdateProfileRequest $request) :RedirectResponse
    {
        $user = Auth::user();
        $this->dashbordService->updateProfile($user, $request->validated());
        ToastMagic::success("Profile berhasil diupdate!");
        return to_route('admin-province.profile');
    }
}
