<?php

namespace Modules\Auth\Services\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthApiService
{
    /**
     * Get paginated users.
     */
    public function getUsers(int $perPage = 5)
    {
        return User::with(['roles.permissions'])->paginate($perPage);
    }

    /**
     * Handle user registration.
     */
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('user');
            $user->tokens()->delete();
            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user'  => $user,
                'token' => $token,
            ];
        });
    }

    public function login(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        // Pengecekan manual ini sudah sangat tepat dan efisien untuk sebuah API (Token-Based)
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return false;
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle user logout.
     */
    public function logout(User $user)
    {
        $user->currentAccessToken()->delete();
    }

    /**
     * Send password reset link to user email.
     */
    public function forgotPassword(array $data): bool
    {
        $status = Password::broker()->sendResetLink($data);

        return $status === Password::ResetLinkSent;
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(array $data): bool
    {
        $status = Password::broker()->reset(
            $data,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET;
    }
}
