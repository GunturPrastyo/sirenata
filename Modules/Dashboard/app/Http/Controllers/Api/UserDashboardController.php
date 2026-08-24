<?php

namespace Modules\Dashboard\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Modules\Dashboard\Http\Requests\UpdateInstansiRequest;
use Modules\Dashboard\Services\DashboardService;

class UserDashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashbordService
    ) {}

    /**
     * Get user profile
     * 
     * GET /api/user/profile
     * 
     * @authenticated
     */
    public function profile(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::User();
        $user->load(['profile', 'scopeArea.province', 'scopeArea.regency']);
        return ResponseHelper::success(
            status: true, 
            message: 'User Data Profile successfully', 
            result: new UserProfileResource($user), 
            statusCode: 200
        );
    }

    /**
     * Update user instansi
     * 
     * PUT /api/user/instansi
     * 
     * @authenticated
     */
    public function updateInstansi(UpdateInstansiRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::User();

        $validated = $request->validated();
        $user = $this->dashbordService->updateInstansi($user, $validated);
        $user->load(['profile', 'scopeArea.province', 'scopeArea.regency']);
        return ResponseHelper::success(
            status: true, 
            message: 'Data instansi berhasil disimpan', 
            result: new UserProfileResource($user), 
            statusCode: 200
        );
    }
}
