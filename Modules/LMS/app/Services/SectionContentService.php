<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SectionContentService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = (string) config('lms.api_url', 'https://e-learning.test/api/v1');
    }

    /**
     * Store/Post Data Section Content Api Admin Pusat
     */
    public function storeSectionContent(string $token, array $data, $file = null): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            if ($file) {
                $client->attach(
                    'document',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }

            $response = $client->post("{$this->baseUrl}/sections/{$data['course_section_id']}/contents", $data);

            if ($response->failed()) {
                $errorData = $response->json();

                Log::error('Failed to store section content', [
                    'status' => $response->status(),
                    'body'   => $errorData ?? $response->body(),
                ]);

                $errorMessage = $errorData['message'] ?? 'Terjadi kesalahan di server API';

                return [
                    'success' => false,
                    'message' => 'Gagal: ' . $errorMessage,
                    'data'    => [],
                ];
            }

            $responseData = $response->json();
            return [
                'success' => true,
                'message' => $responseData['message'] ?? 'Section content berhasil ditambahkan',
                'data'    => $responseData['result'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('SectionContentService::storeSectionContent error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data',
                'data'    => [],
            ];
        }
    }

    /**
     * Update Data Content
     */
    public function updateContent(string $token, string $contentId, array $data, $file = null): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);


            if ($file) {
                $client->attach(
                    'document',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                );
            }
            // URL: contents/{content}/update
            $data['_method'] = 'PUT';
            $response = $client->post("{$this->baseUrl}/contents/{$contentId}/update", $data);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'Gagal memperbarui materi',
                ];
            }

            Log::info('SectionContentService::updateContent success', [
                'contentId' => $contentId,
                'response' => $response->json(),
            ]);

            return [
                'success' => true,
                'message' => 'Materi berhasil diperbarui',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ];
        }
    }


    /**
     * Delete Data Content
     */
    public function deleteContent(string $token, string $contentId): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            // URL: contents/{id}/delete
            $response = $client->delete("{$this->baseUrl}/contents/{$contentId}/delete");

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => $response->json('message') ?? 'Gagal menghapus materi',
                ];
            }

            return [
                'success' => true,
                'message' => 'Materi berhasil dihapus',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan sistem: ' . $e->getMessage(),
            ];
        }
    }
}
