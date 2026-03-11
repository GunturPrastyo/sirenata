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
        Schema::table('course_student', function (Blueprint $table) {
            $table->string('certificate_code')->nullable()->unique()->after('status');
            $table->string('certificate_file')->nullable()->after('certificate_code');
            $table->timestamp('certificate_issued_at')->nullable()->after('certificate_file');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropColumn([
                'certificate_code',
                'certificate_file',
                'certificate_issued_at'
            ]);
        });
    }
};
