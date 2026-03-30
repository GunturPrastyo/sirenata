<?php

namespace Modules\Dashboard\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Enums\InstitutionType;
use Modules\User\Models\UserProfile;
use Modules\User\Models\UserScope;

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

    public function updateInstansi(User $user, array $data)
    {
        $profile = UserProfile::firstOrNew([
            'user_id' => $user->id
        ]);

        $institutionType = match ($data['asal_instansi']) {
            'pusat' => InstitutionType::PUSAT,
            'provinsi' => InstitutionType::PROVINSI,
            'kab_kota' => InstitutionType::KAB_KOTA,
        };

        // handle instansi "lainnya"
        $instansi = $data['instansi'];
        if ($instansi === 'lainnya' && !empty($data['instansi_lainnya'])) {
            $instansi = $data['instansi_lainnya'];
        }

        $profile->institution_type = $institutionType->value;
        $profile->instansi = $instansi;
        $profile->unit_kerja = $data['unit_kerja'];
        $profile->save();

        UserScope::updateOrCreate(
            ['user_id' => $user->id],
            [
                'province_code' => $data['province_code'] ?? null,
                'regency_code' => $data['regency_code'] ?? null,
            ]
        );

        return $user->load(['profile', 'scopeArea']);
    }
}
