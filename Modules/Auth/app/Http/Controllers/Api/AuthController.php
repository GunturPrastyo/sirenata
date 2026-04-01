<?php

namespace Modules\Auth\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Services\Api\AuthApiService;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function __construct(
        private AuthApiService $authApiService
    ) {}

    public function index(Request $request)
    {
        $row_per_page = $request->input('page', 5);
        $users = $this->authApiService->getUsers($row_per_page);

        return ResponseHelper::success(
            status: true,
            message: 'Users retrieved successfully',
            result: PaginateResource::make($users, UserResource::class),
            statusCode: 200
        );
    }

    public function register(RegisterRequest $request)
    {
        $result = $this->authApiService->register($request->validated());

        return ResponseHelper::success(
            status: true,
            message: 'Registration successful',
            result: [
                'token_type'   => 'Bearer',
                'access_token' => $result['token'],
                'user'         => new UserResource($result['user']),
            ],
            statusCode: 201
        );
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();
        $result = $this->authApiService->login($validated);

        if (!$result) {
            return ResponseHelper::error(
                message: 'Kredensial salah atau tidak ditemukan di sistem',
                statusCode: 401
            );
        }

        return ResponseHelper::success(
            status: true,
            message: 'Login successful',
            result: [
                'token_type'   => 'Bearer',
                'access_token' => $result['token'],
                'user'         => new UserResource($result['user']),
            ],
            statusCode: 200
        );
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $this->authApiService->logout($user);

        return ResponseHelper::success(
            status: true,
            message: 'Logout successful',
            result: null,
            statusCode: 200
        );
    }

    public function me(Request $request)
    {
        $user = Auth::user();
        return ResponseHelper::success(
            status: true,
            message: 'Users retrieved successfully',
            result: UserResource::make($user),
            statusCode: 200
        );
    }

    /**
     * Send reset password link to email
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => 'Link reset password telah dikirim ke email.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim link, coba beberapa saat lagi.',
        ], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset, silakan login.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => match ($status) {
                Password::INVALID_TOKEN => 'Token tidak valid atau sudah kadaluarsa.',
                Password::INVALID_USER  => 'Email tidak ditemukan.',
                default                 => 'Gagal mereset password.',
            },
        ], 400);
    }
}
