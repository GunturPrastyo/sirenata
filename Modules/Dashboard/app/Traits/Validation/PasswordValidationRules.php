<?php

namespace Modules\Dashboard\Traits\Validation;

use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules {

    protected function passwordRules(): array
    {
        return [
            'nullable',
            'string',
            'required_with:current_password',
            'confirmed',
            Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols(),
        ];
    }

    protected function currentPasswordRules(): array
    {
        return [
            'nullable',
            'required_with:password',
            'string',
            'current_password',
        ];
    }

    protected function passwordMessages(): array
    {
        return [
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengubah password',
            'current_password.current_password' => 'Password lama tidak sesuai',
            'password.required_with' => 'Password baru wajib diisi jika mengisi password lama',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }
}