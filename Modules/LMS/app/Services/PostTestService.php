<?php

namespace Modules\LMS\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\LMS\Models\PostTest;

class PostTestService
{
    public function storePostTestWithQuestions(array $data): array
    {
        DB::beginTransaction();
        try {
            // 1. Simpan Header Post Test
            $postTest = PostTest::create([
                'course_section_id' => $data['course_section_id'] ?? null,
                'course_id'         => $data['course_id'] ?? null,
                'title'             => $data['title'],
                'description'       => $data['description'] ?? null,
                'passing_score'     => $data['passing_score'] ?? 70,
                'duration'          => $data['duration'] ?? 30,
            ]);

            // 2. Simpan Soal dan Pilihan Ganda
            if (!empty($data['questions'])) {
                foreach ($data['questions'] as $qData) {
                    $question = $postTest->questions()->create([
                        'question' => $qData['question'],
                    ]);

                    if (!empty($qData['choices'])) {
                        foreach ($qData['choices'] as $cIndex => $choiceText) {
                            $question->choices()->create([
                                'choice'     => $choiceText,
                                'is_correct' => (string) $qData['correct_choice'] === (string) $cIndex,
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Post Test beserta soal berhasil disimpan',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('PostTestService store error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ];
        }
    }
}