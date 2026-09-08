<?php

namespace Modules\LMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\PostTest;
use Modules\LMS\Models\PostTestQuestion;
use Modules\LMS\Models\PostTestChoice;

class PostTestSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::with('sections')->get();

        foreach ($courses as $course) {
            // 1. Post Test per Bab / Section
            foreach ($course->sections as $section) {
                $test = PostTest::updateOrCreate(
                    [
                        'course_section_id' => $section->id,
                    ],
                    [
                        'course_id'     => $course->id,
                        'title'         => 'Evaluasi: ' . $section->name,
                        'description'   => 'Kuis pemahaman konsep untuk ' . $section->name . '.',
                        'passing_score' => 70,
                        'duration'      => 15,
                    ]
                );

                $this->createQuestions($test, $section->name);
            }

            // 2. Evaluasi Akhir (course_section_id bernilai NULL)
            $finalTest = PostTest::updateOrCreate(
                [
                    'course_id'         => $course->id,
                    'course_section_id' => null,
                ],
                [
                    'title'         => 'Evaluasi Akhir: ' . $course->name,
                    'description'   => 'Ujian kelulusan akhir komprehensif seluruh materi kursus.',
                    'passing_score' => 75,
                    'duration'      => 30,
                ]
            );

            $this->createQuestions($finalTest, $course->name);
        }

        $this->command->info("PostTestSeeder (Kunci Jawaban Selalu Opsi A) berhasil dijalankan 🚀");
    }

    private function createQuestions(PostTest $test, string $contextName): void
    {
        // Cegah duplikasi soal saat seeder dijalankan ulang
        if ($test->questions()->count() > 0) {
            return;
        }

        $sampleQuestions = [
            [
                'q' => 'Apa tujuan fundamental dari pengolahan data pada konteks ' . $contextName . '?',
                'correct' => 'Memastikan proyeksi dan alokasi tenaga kerja akurat sesuai regulasi',
                'wrongs'  => [
                    'Mengurangi transparansi pelaporan administrasi daerah',
                    'Menghapus kewajiban standarisasi data di tingkat pusat',
                    'Memperpanjang birokrasi perizinan instansi vertikal'
                ]
            ],
            [
                'q' => 'Manakah langkah awal yang paling krusial dalam tahap implementasi ' . $contextName . '?',
                'correct' => 'Melakukan inventarisasi dan validasi variabel input primer',
                'wrongs'  => [
                    'Langsung menetapkan target kelulusan tanpa telaah data',
                    'Mengabaikan data demografi ketenagakerjaan wilayah',
                    'Menerbitkan sertifikat sebelum pengujian materi'
                ]
            ],
            [
                'q' => 'Bagaimana mengukur keberhasilan indikator pada ' . $contextName . '?',
                'correct' => 'Tercapainya standar kepatuhan KKM dan keselarasan proyeksi',
                'wrongs'  => [
                    'Tingginya jumlah pengulangan tes oleh peserta',
                    'Penurunan efisiensi jam pelajaran tenaga pendidik',
                    'Tidak tersedianya dokumen portofolio hasil evaluasi'
                ]
            ]
        ];

        foreach ($sampleQuestions as $item) {
            $question = PostTestQuestion::create([
                'post_test_id' => $test->id,
                'question'     => $item['q'],
            ]);

            // Kunci Jawaban A (is_correct = true)
            PostTestChoice::create([
                'post_test_question_id' => $question->id,
                'choice'                => 'A. ' . $item['correct'],
                'is_correct'            => true,
            ]);

            // Opsi Jawaban Salah B, C, D (is_correct = false)
            $labels = ['B', 'C', 'D'];
            foreach ($item['wrongs'] as $idx => $wrongText) {
                PostTestChoice::create([
                    'post_test_question_id' => $question->id,
                    'choice'                => $labels[$idx] . '. ' . $wrongText,
                    'is_correct'            => false,
                ]);
            }
        }
    }
}