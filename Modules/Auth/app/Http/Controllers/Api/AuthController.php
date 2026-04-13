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
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthApiService $authApiService
    ) {}


    /**
     *  List Users
     *  @unauthenticated
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $row_per_page = $request->input('row_per_page', 10);
            $search       = $request->input('search');
            $users        = $this->authApiService->getUsers(
                row_per_page: $row_per_page,
                search: $search
            );

            return ResponseHelper::success(
                status: true,
                message: 'Users retrieved successfully',
                result: PaginateResource::make($users, UserResource::class),
                statusCode: 200
            );
        } catch (\Throwable $th) {
            return ResponseHelper::error(
                message: $th->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     *  Register User
     *  @unauthenticated
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
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
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     *  Login User
     *  @unauthenticated
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
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
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

     /**
     *  Logout User
     *  @authenticated
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $this->authApiService->logout($user);

            return ResponseHelper::success(
                status: true,
                message: 'Logout successful',   
                result: null,
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     *  Get User
     *  @authenticated
     */
    public function me(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $user->load(['roles.permissions']);
            return ResponseHelper::success(
                status: true,
                message: 'Users retrieved successfully',
                result: UserResource::make($user),
                statusCode: 200
            );
        } catch (\Exception $e) {
            return ResponseHelper::error(
                message: $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Send reset password link to email
     * @unauthenticated
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

    try {
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
       } catch (\Exception $e) {
            return ResponseHelper::error(
                message: 'Gagal mengirim link reset password',
                statusCode: 500
            );
       }
    }
    
    /**
     * Reset the user's password
     * @unauthenticated
     */
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
