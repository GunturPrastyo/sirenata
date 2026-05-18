<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rtk_pemanfaatan_submissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('period_id')->constrained('rtk_survey_periods')->cascadeOnDelete();
            
            // Mencatat siapa yang menginput data ini (bisa Admin Pusat atau User Provinsi itu sendiri)
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();

            // Referensi ke RencanaTenagaKerja Provinsi (jika ada)
            // Jika isi: Punya RTKD (Data tahun ambil dari relasi ini)
            // Jika kosong/null: Tidak punya RTKD
            $table->uuid('rtk_document_id')->nullable(); 

            // Field lanjutan (hanya diisi jika punya rtkd / rtk_document_id != null)
            $table->string('q2_jadi_acuan')->nullable(); // ya, tidak
            
            // JSON Columns untuk checkbox/opsi multi-select
            $table->json('dokumen_acuan')->nullable(); // [ {doc_type: 'rpjmd', nama_lainnya: null}, ... ]
            $table->json('komponen_acuan')->nullable(); // [ {doc_type: 'rpjmd', komponen: 'Angka Pengangguran', ...}, ... ]
            
            // JSON Columns untuk alasan
            $table->json('alasan_tidak_punya')->nullable(); // [ {alasan: '...', keterangan_lainnya: '...'} ]
            $table->json('alasan_belum_acuan')->nullable(); // [ {alasan: '...', keterangan_lainnya: '...'} ]
            
            // File uploads pendukung
            $table->json('dokumen_uploads')->nullable(); // [ {doc_type: 'rpjmd', file_path: '...', original_name: '...'} ]

            // Status verifikasi
            $table->string('status_verifikasi')->default('pending'); // pending, verified, rejected
            $table->text('catatan_verifikasi')->nullable();
            $table->json('field_verifications')->nullable();

            $table->timestamps();

            // Constraint agar user_id dan period_id unik (1 provinsi hanya 1 submission per periode)
            $table->unique(['user_id', 'period_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rtk_pemanfaatan_submissions');
    }
};
