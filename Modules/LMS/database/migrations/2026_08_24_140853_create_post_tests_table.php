<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Tabel Utama Post Test (Header)
        Schema::create('post_tests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Relasi (Nullable agar fleksibel untuk Section ATAU Course)
            $table->foreignUuid('course_section_id')->nullable()->constrained('course_sections')->cascadeOnDelete();
            $table->foreignUuid('course_id')->nullable()->constrained('courses')->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_score')->default(70); // KKM
            $table->integer('duration')->default(30); // Durasi dalam menit
            
            $table->timestamps();
        });

        // 2. Tabel Pertanyaan / Soal Post Test
        Schema::create('post_test_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('post_test_id')->constrained('post_tests')->cascadeOnDelete();
            $table->text('question'); // Teks soal
            $table->timestamps();
        });

        // 3. Tabel Pilihan Opsi Jawaban (Multiple Choice)
        Schema::create('post_test_choices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('post_test_question_id')->constrained('post_test_questions')->cascadeOnDelete();
            $table->text('choice'); // Teks pilihan opsi
            $table->boolean('is_correct')->default(false); // Penanda kunci jawaban benar
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_test_choices');
        Schema::dropIfExists('post_test_questions');
        Schema::dropIfExists('post_tests');
    }
};