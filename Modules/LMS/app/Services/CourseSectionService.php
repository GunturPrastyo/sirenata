<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class CourseSectionService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = (string) config('lms.api_url', 'https://e-learning.test/api/v1');
    }

    /**
     * Store/Post Data Course Section Api Admin Pusat
     */
    public function storeCourseSection(string $token, array $data): array
    {
        try {
            $client = Http::withToken($token)
                ->acceptJson()
                ->timeout(15);

            // POST request ke endpoint {slug}/sections
            $response = $client->post("{$this->baseUrl}/courses/{$data['slug']}/sections", $data);

            if ($response->failed()) {
                $errorData = $response->json();

                Log::error('Failed to store course section', [
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
                'message' => $responseData['message'] ?? 'Course section berhasil ditambahkan',
                'data'    => $responseData['result'] ?? [],
            ];
        } catch (\Exception $e) {
            Log::error('CourseSectionService::storeCourseSection error', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat menyimpan data',
                'data'    => [],
            ];
        }
    }
}
