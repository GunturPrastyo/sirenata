<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rtk_pemanfaatan_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('period_id')->constrained('rtk_survey_periods')->cascadeOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->uuid('rtk_document_id')->nullable(); 
            $table->string('q2_jadi_acuan')->nullable();
            $table->json('dokumen_acuan')->nullable();
            $table->json('komponen_acuan')->nullable();
            $table->json('alasan_tidak_punya')->nullable();
            $table->json('alasan_belum_acuan')->nullable();
            $table->json('dokumen_uploads')->nullable();
            $table->string('status_verifikasi')->default('pending');
            $table->text('catatan_verifikasi')->nullable();
            $table->json('field_verifications')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rtk_pemanfaatan_submissions');
    }
};
