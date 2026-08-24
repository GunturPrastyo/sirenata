<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\LMS\Models\SectionContent;

class SectionContentService
{
    /**
     * Store Data Section Content langsung ke Database menggunakan Eloquent
     * Parameter $token tetap dibiarkan agar tidak membuat error pada Controller yang sudah ada, 
     * meski tidak lagi digunakan.
     */
    public function storeSectionContent(string $token, array $data, $file = null): array
    {
        try {
            $documentPath = null;
            
            // Simpan file jika ada ke folder 'course-documents' di disk public
            if ($file) {
                $documentPath = $file->store('course-documents', 'public');
            }

            // (Opsional) Mengatur posisi urutan materi secara otomatis di akhir
            $lastPosition = SectionContent::where('course_section_id', $data['course_section_id'])->max('position');
            $newPosition = $lastPosition ? $lastPosition + 1 : 1;

            // Simpan langsung ke database
            $content = SectionContent::create([
                'course_section_id' => $data['course_section_id'],
                'name'              => $data['name'],
                'video'             => $data['video'] ?? null,
                'content_text'      => $data['content_text'] ?? null,
                'document'          => $documentPath,
                'position'          => $newPosition,
            ]);

            return [
                'success' => true,
                'message' => 'Materi berhasil ditambahkan',
                'data'    => $content,
            ];
        } catch (\Exception $e) {
            Log::error('SectionContentService::storeSectionContent error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data: ' . $e->getMessage(),
                'data'    => [],
            ];
        }
    }

    /**
     * Update Data Content langsung ke Database
     */
    public function updateContent(string $token, string $contentId, array $data, $file = null): array
    {
        try {
            // Cari data materi berdasarkan ID
            $content = SectionContent::findOrFail($contentId);
            $documentPath = $content->document; // Pertahankan dokumen lama sebagai default

            // Jika ada file baru yang diupload
            if ($file) {
                // Hapus file lama secara fisik dari server jika ada
                if ($documentPath && Storage::disk('public')->exists($documentPath)) {
                    Storage::disk('public')->delete($documentPath);
                }
                
                // Simpan file baru
                $documentPath = $file->store('course-documents', 'public');
            }

            // Update ke database
            $content->update([
                'name'         => $data['name'],
                'video'        => $data['video'] ?? null,
                'content_text' => $data['content_text'] ?? null,
                'document'     => $documentPath,
            ]);

            return [
                'success' => true,
                'message' => 'Materi berhasil diperbarui',
            ];
        } catch (\Exception $e) {
            Log::error('SectionContentService::updateContent error', [
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Delete Data Content langsung dari Database
     */
    public function deleteContent(string $token, string $contentId): array
    {
        try {
            $content = SectionContent::findOrFail($contentId);

            // Bersihkan file dokumen secara fisik dari server sebelum data dihapus
            if ($content->document && Storage::disk('public')->exists($content->document)) {
                Storage::disk('public')->delete($content->document);
            }

            // Hapus data dari tabel database
            $content->delete();

            return [
                'success' => true,
                'message' => 'Materi berhasil dihapus',
            ];
        } catch (\Exception $e) {
            Log::error('SectionContentService::deleteContent error', [
                'error' => $e->getMessage(),
            ]);
            
            return [
                'success' => false,
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ];
        }
    }
}