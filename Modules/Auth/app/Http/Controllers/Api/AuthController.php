<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use Modules\Auth\Services\Api\AuthApiService;

class AuthController extends Controller
{
    public function __construct(
        private AuthApiService $authApiService
    ) {}

    public function index(Request $request)
    {
        $row_per_page = $request->input('page', 5);
        $users = $this->authApiService->getUsers($row_per_page);
    
        return ResponseHelper::success(true, 'Users retrieved successfully', PaginateResource::make($users, UserResource::class), 200);
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authApiService->register($request->validated());

        return ResponseHelper::success(true, 'Registration successful', [
            'token_type'   => 'Bearer',
            'access_token' => $result['token'],
            'user'         => new UserResource($result['user']),
        ], 201);
    }

    public function login(LoginRequest $request)
    {   
        $validated = $request->validated();
        $result = $this->authApiService->login($validated);

        if (!$result) {
            return ResponseHelper::error('Kredensial salah atau tidak ditemukan di sistem', 401);
        }

        return ResponseHelper::success(true, 'Login successful', [
            'token_type'   => 'Bearer',
            'access_token' => $result['token'],
            'user'         => new UserResource($result['user']),
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->authApiService->logout($request->user());

        return ResponseHelper::success(true, 'Logout successful', null, 200);
    }

    public function me(Request $request)
    {
        return ResponseHelper::success(true, 'Users retrieved successfully', UserResource::make($request->user()), 200);
    }
}