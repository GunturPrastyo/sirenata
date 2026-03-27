<?php

namespace Modules\Dashboard\Traits\Validation;

use Illuminate\Validation\Rule;

trait ProfileValidationRules {
    
    /**
     * Main rules
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'full_name' => $this->fullNameRules(),
            'phone' => $this->phoneRules(),
            'gender' => $this->genderRules(),
            'instansi' => $this->instansiRules(),
            'unit_kerja' => $this->unitKerjaRules(),
            
            'province_code' => $this->provinceRules(),
            'regency_code' => $this->regencyRules(),
        ];
    }

    /**
     * Full Name
     */
    protected function fullNameRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    /**
     * Phone
     */
    protected function phoneRules(): array
    {
        return ['required', 'string', 'regex:/^(\+62|08)[0-9]{8,13}$/'];
    }

    /**
     * Gender
     */
    protected function genderRules(): array
    {
        return ['required', Rule::in(['male', 'female'])];
    }

    /**
     * Instansi
     */
    protected function instansiRules(): array
    {
        return ['required', 'string', 'max:255'];
    }

    protected function unitKerjaRules(): array
    {
        return ['required', 'string', 'max:255'];
    }


    /**
     * Scope Area
     */
    protected function provinceRules(): array
    {
        return ['nullable'];
    }

    protected function regencyRules(): array
    {
        return ['nullable'];
    }


    protected function profileMessages(): array
    {
        return [
            'phone.regex' => 'Format nomor HP tidak valid',
        ];
    }
}
