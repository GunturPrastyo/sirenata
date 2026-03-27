<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Dashboard\Traits\Validation\PasswordValidationRules;
use Modules\Dashboard\Traits\Validation\ProfileValidationRules;


class UpdateProfileRequest extends FormRequest
{

    use ProfileValidationRules, PasswordValidationRules;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return array_merge(
            $this->profileRules(),
            [
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ]
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return array_merge(
            $this->profileMessages(),
            $this->passwordMessages()
        );
    }
}
