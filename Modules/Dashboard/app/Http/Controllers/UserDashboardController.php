<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Models\UserProfile;

class UserDashboardController extends Controller
{
    /**
     * Display the User Dashboard.
     */
    public function index()
    {
        $user = auth()->user();

        // Ensure user has a profile
        $profile = UserProfile::firstOrCreate(['user_id' => $user->id]);

        // Fetch provinces from Creasi/Nusa
        $provinces = \Creasi\Nusa\Models\Province::all();

        return view('dashboard::user.index', compact('profile', 'provinces'));
    }

    public function getRegencies(Request $request)
    {
        $request->validate([
            'province_code' => 'required|string',
        ]);

        $province = \Creasi\Nusa\Models\Province::where('code', $request->province_code)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $province->regencies
        ]);
    }

    public function updateInstansi(Request $request)
    {
        $request->validate([
            'instansi' => 'required|string|max:255',
        ]);

        $user = auth()->user();
        $profile = UserProfile::where('user_id', $user->id)->first();

        // Check if profile exists; if not, create one
        if (!$profile) {
            $profile = new UserProfile();
            $profile->user_id = $user->id;
        }

        $profile->instansi = $request->instansi;
        $profile->save();

        return redirect()->route('user.dashboard')->with('success', 'Data instansi berhasil disimpan.');
    }
}
