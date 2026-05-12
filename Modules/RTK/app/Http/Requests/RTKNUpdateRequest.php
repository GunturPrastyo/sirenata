<?php

namespace Modules\RTK\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Modules\RTK\Enums\RTKStatusVerification;

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
                    $startYear = (int) $this->start_date;
                    $endYear = (int) $value;

                    if ($endYear <= $startYear) {
                        $fail('Tahun akhir harus lebih besar dari tahun mulai.');
                    }

                    // if ($endYear > $startYear + 5) {
                    //     $fail('Tahun akhir maksimal 5 tahun dari tahun mulai.');
                    // }
                },
            ],
            'document_path' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'is_active' => ['required', 'boolean'],
            // 'status_verification' => ['required', new Enum(RTKStatusVerification::class)],
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
