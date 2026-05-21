<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Permission\Enums\StackHolder;

class AuthService
{
    /**
     * Handle user authentication (login).
     *
     * @param array $credentials
     * @param bool $remember
     * @return bool
     */
    public function authenticate(array $credentials, bool $remember = false): bool
    {
        if (Auth::attempt($credentials, $remember)) {
            session()->regenerate();

            $user = Auth::user();
            $user->tokens()->delete();
            $token = $user->createToken('api-token')->plainTextToken;
            session(['api_token' => $token]);

            return true;
        }

        return false;
    }

    /**
     * Handle user registration.
     */
    public function register($validated)
    {
        return DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $user->assignRole(StackHolder::USER->value);
            return $user;
        });
    }

    /**
     * Handle user logout.
     */
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            // Hapus token yang bernama 'api-token'
            $user->tokens()->where('name', 'api-token')->delete();
            // Atau cukup gunakan $user->tokens()->delete(); jika ingin menghapus semua token user
        }

        // Hapus dari session
        session()->forget('api_token');

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }
}
