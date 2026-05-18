<?php

namespace Modules\RTK\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SurveyPeriodUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'deskripsi' => 'nullable|string|max:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'Nama Periode',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'deskripsi' => 'Deskripsi',
        ];
    }
}
