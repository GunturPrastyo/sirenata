<?php

namespace Modules\Auth\Services;

use Illuminate\Support\Facades\Auth;

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
            return true;
        }

        return false;
    }

    /**
     * Handle user registration.
     */
    public function register($username, $email, $password) {}

    /**
     * Handle user logout.
     */
    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }  
}
