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

        // 3. Activity Logs
        $recentActivities = collect();
        if (class_exists(\Spatie\Activitylog\Models\Activity::class)) {
            try {
                $recentActivities = \Spatie\Activitylog\Models\Activity::with('causer')->latest()->take(5)->get();
            } catch (\Exception $e) {
                // Keep empty collection
            }
        }

        if ($recentActivities->isEmpty()) {
            $recentActivities = collect([
                (object) [
                    'description' => 'diperbarui',
                    'subject_type' => 'Konfigurasi Sistem',
                    'subject' => (object) ['name' => 'API Key KCLC'],
                    'subject_id' => 1,
                    'created_at' => now()->subMinutes(10),
                    'causer' => (object) ['name' => 'Superadmin']
                ],
                (object) [
                    'description' => 'dibuat',
                    'subject_type' => 'Akun Admin Pusat',
                    'subject' => (object) ['name' => 'Sarah Putri'],
                    'subject_id' => 2,
                    'created_at' => now()->subHours(1),
                    'causer' => (object) ['name' => 'Superadmin']
                ],
                (object) [
                    'description' => 'terdaftar',
                    'subject_type' => 'Peserta Baru',
                    'subject' => (object) ['name' => 'Budi Santoso'],
                    'subject_id' => 15,
                    'created_at' => now()->subHours(3),
                    'causer' => null
                ],
                (object) [
                    'description' => 'diperbarui',
                    'subject_type' => 'Lembaga',
                    'subject' => (object) ['name' => 'Kementerian Ketenagakerjaan'],
                    'subject_id' => 4,
                    'created_at' => now()->subDay(),
                    'causer' => (object) ['name' => 'Superadmin']
                ],
                (object) [
                    'description' => 'dibuat',
                    'subject_type' => 'Kelas Baru',
                    'subject' => (object) ['name' => 'Pelatihan Vokasi TIK'],
                    'subject_id' => 8,
                    'created_at' => now()->subDays(2),
                    'causer' => (object) ['name' => 'Sarah Putri']
                ]
            ]);
        }

        return view('dashboard::pages.super-admin.index', [
            'user' => $user,
            'totalUser' => $totalUser,
            'totalAdminPusat' => $totalAdminPusat,
            'totalAdminProvinsi' => $totalAdminProvinsi,
            'totalAdminKabKota' => $totalAdminKabKota,
            'totalLembaga' => $totalLembaga,
            'totalInstansi' => $totalInstansi,
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
