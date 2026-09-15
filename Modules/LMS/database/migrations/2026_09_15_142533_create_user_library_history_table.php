<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_library_history', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('library_id')->constrained('libraries')->cascadeOnDelete();
            $table->timestamp('last_accessed_at'); 
            $table->timestamps();

            $table->unique(['user_id', 'library_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_library_history');
    }
};
