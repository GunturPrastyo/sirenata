<?php

namespace Modules\Dashboard\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Modules\Dashboard\Http\Requests\UpdateProfileRequest;
use Modules\Dashboard\Services\DashboardService;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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
        $regencyCode = $userScope ? $userScope->regency_code : null;

        // Base query for users taking courses in this regency
        $baseUserQuery = \Illuminate\Support\Facades\DB::table('user_scopes')
            ->join('users', 'user_scopes.user_id', '=', 'users.id')
            ->join('model_has_roles', function ($join) {
                $join->on('users.id', '=', 'model_has_roles.model_uuid')
                    ->where('model_has_roles.model_type', '=', 'App\\Models\\User');
            })
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.uuid')
            ->where('roles.name', 'user')
            ->where('user_scopes.regency_code', $regencyCode);

        // Period calculation (5-year blocks aligned: 2015-2019, 2020-2024, 2025-2029, ...)
        $currentYear = (int) date('Y');
        $currentPeriodStart = (int)(floor($currentYear / 5)) * 5;
        $selectedPeriodStart = (int) $request->input('period_start', $currentPeriodStart);
        $selectedPeriodEnd = $selectedPeriodStart + 4;

        // Generate period options: 2 before, current, 2 after
        $periods = [];
        for ($i = -2; $i <= 2; $i++) {
            $ps = $currentPeriodStart + ($i * 5);
            $pe = $ps + 4;
            $periods[] = ['start' => $ps, 'end' => $pe, 'label' => "$ps - $pe"];
        }

        // SDM per Tahun (filtered by selected period)
        $usersByYear = (clone $baseUserQuery)
            ->select(\Illuminate\Support\Facades\DB::raw('YEAR(users.created_at) as year'), \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy(\Illuminate\Support\Facades\DB::raw('YEAR(users.created_at)'))
            ->pluck('total', 'year');

        // Ensure all 5 years in the period appear (fill missing with 0)
        $sdmPerTahun = [];
        for ($y = $selectedPeriodStart; $y <= $selectedPeriodEnd; $y++) {
            $sdmPerTahun[$y] = $usersByYear->get($y, 0);
        }

        // Gender stats
        $genders = (clone $baseUserQuery)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('user_profiles.gender', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('user_profiles.gender')
            ->pluck('total', 'gender');

        $genderMale = $genders->get('male', 0);
        $genderFemale = $genders->get('female', 0);

        return view('dashboard::pages.admin-kab-kota.index', [
            'user' => $user,
            'sdmPerTahun' => $sdmPerTahun,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
            'periods' => $periods,
            'selectedPeriodStart' => $selectedPeriodStart,
        ]);
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('dashboard::pages.admin-kab-kota.profile', [
            'user' => $user,
        ]);
    }

    public function storeOrUpdateProfile(UpdateProfileRequest $request) :RedirectResponse
    {
        $user = Auth::user();
        $this->dashbordService->updateProfile($user, $request->validated());
        ToastMagic::success("Profile berhasil diupdate!");
        return to_route('admin-kab-kota.profile');
    }
}
