<?php

namespace Modules\Roles\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'min:3',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->ignore($this->route('role')->uuid, 'uuid'),
            ],
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
