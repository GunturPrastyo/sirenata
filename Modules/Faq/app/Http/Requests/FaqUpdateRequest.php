<?php

namespace Modules\Faq\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Faq\Enums\FaqLevel;

class FaqUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'level' => 'required|in:' . FaqLevel::NASIONAL->value . ',' . FaqLevel::PROVINSI->value . ',' . FaqLevel::KAB_KOTA->value,
        ];
    }

    public function attributes(): array
    {
        return [
            'question' => 'Pertanyaan',
            'answer' => 'Jawaban',
            'level' => 'Level FAQ',
        ];
    }
}
