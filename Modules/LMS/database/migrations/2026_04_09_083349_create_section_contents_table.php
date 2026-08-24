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
        Schema::create('section_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_section_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('video')->nullable();
            $table->longText('content_text')->nullable();
            $table->string('document')->nullable()->unique();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_contents');
    }
};
