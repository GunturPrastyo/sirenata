<?php

namespace Modules\Dashboard\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DashboardService
{
    public function updateProfile($user, $validated)
    {
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $validated['full_name'] ?? $user->profile?->full_name,
                'phone' => $validated['phone'] ?? $user->profile?->phone,
                'gender' => $validated['gender'] ?? $user->profile?->gender,
                'instansi' => $validated['instansi'] ?? $user->profile?->instansi,
                'unit_kerja' => $validated['unit_kerja'] ?? $user->profile?->unit_kerja,
            ]
        );

        $user->scopeArea()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => $validated['province_code'] ?? $user->scopeArea?->province_code,
                'regency_code' => $validated['regency_code'] ?? $user->scopeArea?->regency_code,
            ]
        );

        if (!empty($validated['password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'Password lama salah',
                ]);
            }
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }
        return $user;
    }
}
