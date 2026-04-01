<?php

namespace Modules\Dashboard\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function index()
    {
        $user = Auth::user();
        return view('dashboard::pages.super-admin.index', [
            'user' => $user
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
