<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('post_test_results', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Menggunakan UUID agar seragam dengan tabel lainnya
            
            // PERBAIKAN: Gunakan foreignUuid untuk user_id
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->foreignUuid('post_test_id')->constrained('post_tests')->cascadeOnDelete();
            $table->integer('score')->default(0);
            $table->boolean('is_passed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // Mencegah duplikasi data agar 1 user hanya punya 1 record aktif per post test
            $table->unique(['user_id', 'post_test_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_test_results');
    }
};