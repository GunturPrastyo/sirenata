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
        Schema::table('rencana_tenaga_kerjas', function (Blueprint $table) {
            $table->renameColumn('status', 'status_verification');
            $table->enum('status_document', ['valid', 'expired', 'na'])
                ->default('na')
                ->after('status_verification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rencana_tenaga_kerjas', function (Blueprint $table) {
            $table->renameColumn('status_verification', 'status');
            $table->dropColumn('status_document');
        });
    }
};
