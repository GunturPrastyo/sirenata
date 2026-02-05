<?php

namespace Modules\RTK\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\RTK\Enums\RTKStatus;
use Illuminate\Validation\Rules\Enum;


class RTKNUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'integer', 'digits:4', 'min:1900'],
            'end_date' => [
                'required',
                'integer',
                'digits:4',
                function ($attribute, $value, $fail) {
                    $expectedEndYear = (int) $this->start_date + 5;

                    if ((int) $value !== $expectedEndYear) {
                        $fail('Tahun akhir harus tepat 5 tahun setelah tahun mulai.');
                    }
                },
            ],
            'document_path' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'status' => ['required', new Enum(RTKStatus::class)],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama Dokumen Rencana Tenaga Kerja',
            'start_date' => 'Tahun Mulai',
            'end_date' => 'Tahun Akhir',
            'document_path' => 'File Dokumen Rencana Tenaga Kerja',
        ];
    }
}
