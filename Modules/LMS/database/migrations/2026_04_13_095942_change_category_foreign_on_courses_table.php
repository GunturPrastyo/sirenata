<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('courses', function (Blueprint $table) {
        // Hapus foreign key lama
        $table->dropForeign(['category_id']);

        // category_id harus nullable dulu
        $table->foreignUuid('category_id')->nullable()->change();

        // Tambah foreign key baru dengan nullOnDelete
        $table->foreign('category_id')
              ->references('id')
              ->on('categories')
              ->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('courses', function (Blueprint $table) {
        $table->dropForeign(['category_id']);

        $table->foreignUuid('category_id')->nullable(false)->change();

        $table->foreign('category_id')
              ->references('id')
              ->on('categories')
              ->cascadeOnDelete();
    });
    }
};
