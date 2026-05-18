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
        Schema::table('users', function (Blueprint $table) {
            $table->string('siapkerja_id')->nullable()->unique()->after('id');
            $table->string('siapkerja_token')->nullable()->after('siapkerja_id');
            $table->string('siapkerja_refresh_token')->nullable()->after('siapkerja_token');

            // Opsional: Buat password boleh kosong karena user SSO tidak butuh password saat daftar
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('siapkerja_id');
            $table->dropColumn('siapkerja_token');
            $table->dropColumn('siapkerja_refresh_token');
        });
    }
};
