<?php

namespace Modules\Dashboard\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Faq\Models\Faq;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
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

        // SDM per Tahun (based on user registration date)
        $usersByYear = (clone $baseUserQuery)
            ->select(\Illuminate\Support\Facades\DB::raw('YEAR(users.created_at) as year'), \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy(\Illuminate\Support\Facades\DB::raw('YEAR(users.created_at)'))
            ->pluck('total', 'year');

        // Note: For mock purposes, if no data exists, we pass an empty array
        // We will handle the fallback or format in the blade template.
        $sdmPerTahun = $usersByYear->toArray();

        // Gender stats
        $genders = (clone $baseUserQuery)
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select('user_profiles.gender', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('user_profiles.gender')
            ->pluck('total', 'gender');

        $genderMale = $genders->get('male', 0);
        $genderFemale = $genders->get('female', 0);

        return view('dashboard::admin-kab-kota.index', [
            'user' => $user,
            'sdmPerTahun' => $sdmPerTahun,
            'genderMale' => $genderMale,
            'genderFemale' => $genderFemale,
        ]);
    }
}
