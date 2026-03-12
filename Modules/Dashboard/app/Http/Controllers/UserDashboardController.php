<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\MasterData\Models\Province;
use Modules\User\Enums\InstitutionType;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class UserDashboardController extends Controller
{
    /**
     * Display the User Dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);
        $provinces = Province::all();

        return view('dashboard::user.index', compact('profile', 'provinces'));
    }

    public function getRegencies(Request $request)
    {
        $request->validate([
            'province_code' => 'required|string',
        ]);

        $province = Province::where('code', $request->province_code)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $province->regencies
        ]);
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

        $user = auth()->user();

        $profile = UserProfile::firstOrNew([
            'user_id' => $user->id
        ]);

        $institutionType = match ($request->asalInstansi) {
            'pusat' => InstitutionType::PUSAT,
            'provinsi' => InstitutionType::PROVINSI,
            'kabkota' => InstitutionType::KAB_KOTA,
        };

        $instansi = $request->instansi;

        if ($request->instansi === 'lainnya') {
            $instansi = $request->instansi_lainnya;
        }

        $profile->institution_type = $institutionType->value;
        $profile->instansi = $instansi;
        $profile->unit_kerja = $request->unit_kerja;
        $profile->save();

        // Simpan scope wilayah
        UserScope::updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => $request->province_code,
                'regency_code' => $request->regency_code,
            ]
        );
        ToastMagic::success("Data instansi berhasil disimpan!");
        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Data instansi berhasil disimpan.');
    }
}
