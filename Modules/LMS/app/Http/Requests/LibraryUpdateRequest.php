<?php

namespace Modules\LMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LibraryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'library_category_id' => ['required', 'exists:library_categories,id'],
            'title'           => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'cover_image'     => ['nullable', 'image', 'max:2048'],
            'file_path'       => ['nullable', 'file', 'mimes:pdf', 'max:20480'],
            'video_path'      => ['nullable', 'file', 'mimes:mp4,webm', 'max:51200'],
            'external_link'   => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'library_category_id' => 'Kategori Perpustakaan',
            'title'           => 'Judul',
            'description'     => 'Deskripsi',
            'cover_image'     => 'Gambar Cover',
            'file_path'       => 'File Dokumen (PDF)',
            'video_path'      => 'File Video',
            'external_link'   => 'Link Eksternal',
        ];
    }
}
