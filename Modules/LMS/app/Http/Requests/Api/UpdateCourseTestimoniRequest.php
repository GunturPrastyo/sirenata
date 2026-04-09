<?php

namespace Modules\LMS\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseTestimoniRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
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
