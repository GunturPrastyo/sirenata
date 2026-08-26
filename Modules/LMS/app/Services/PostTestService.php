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

    public function updatePostTestWithQuestions(\Modules\LMS\Models\PostTest $postTest, array $data): array
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Update Header Post Test
            $postTest->update([
                'title'         => $data['title'],
                'description'   => $data['description'] ?? null,
                'passing_score' => $data['passing_score'] ?? 70,
                'duration'      => $data['duration'] ?? 30,
            ]);

            // 2. Pendekatan Sederhana: Hapus soal & pilihan lama, lalu buat ulang yang baru
            // (Atau bisa di-sync, tapi menghapus dan membuat ulang lebih aman untuk struktur soal pilihan ganda dinamis)
            foreach ($postTest->questions as $question) {
                $question->choices()->delete();
                $question->delete();
            }

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

            \Illuminate\Support\Facades\DB::commit();
            return [
                'success' => true,
                'message' => 'Post Test berhasil diperbarui',
            ];
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Illuminate\Support\Facades\Log::error('PostTestService update error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal memperbarui: ' . $e->getMessage(),
            ];
        }
    }
}