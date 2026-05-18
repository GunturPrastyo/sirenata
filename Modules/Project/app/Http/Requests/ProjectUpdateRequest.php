<?php

namespace Modules\Project\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id',
        ];
    }

    public function attributes(): array
    {
        return [
            'proyekName' => 'Nama Proyek',
            'startDate' => 'Tanggal Mulai',
            'endDate' => 'Tanggal Selesai',
            'duration' => 'Durasi',
            'teamLeader' => 'Ketua Tim',
            'teamMembers' => 'Anggota Tim',
        ];
    }
}
