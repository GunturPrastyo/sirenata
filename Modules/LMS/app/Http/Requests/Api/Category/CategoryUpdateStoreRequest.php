<?php

namespace Modules\LMS\Http\Requests\Api\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "name" => "sometimes|required|string|max:255",
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
