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

        // SDM per Kab/Kota
        $userCountsByRegency = (clone $baseUserQuery)
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

        return view('dashboard::pages.admin-provinsi.index', [
            'user' => $user,
            'sdmPerKabKota' => $sdmPerKabKota,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
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
