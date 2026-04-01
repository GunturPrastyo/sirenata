<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth::auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors(['email' => 'Gagal mengirim link, coba beberapa saat lagi.']);
    }

    public function showResetForm(Request $request)
    {
        // Cek apakah token valid sebelum tampilkan view
        $tokenExists = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->exists();

        if (!$tokenExists) {
            return redirect()->route('forgot-password')
                ->withErrors(['email' => 'Link reset password tidak valid atau sudah kadaluarsa.']);
        }
        return view('auth::auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
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
            return redirect()->route('login')->with('success', 'Password berhasil direset, silakan login.');
        }

        return back()->withErrors([
            'email' => match ($status) {
                Password::INVALID_TOKEN => 'Token tidak valid atau sudah kadaluarsa.',
                Password::INVALID_USER  => 'Email tidak ditemukan.',
                default                 => 'Gagal mereset password.',
            }
        ]);
    }
}
