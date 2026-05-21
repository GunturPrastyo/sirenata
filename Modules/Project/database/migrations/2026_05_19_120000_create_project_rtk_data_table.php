<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_rtk_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('nama_daerah', 100)->default('');
            $table->smallInteger('tahun_hist_awal')->default(0);
            $table->smallInteger('tahun_hist_akhir')->default(0);
            $table->smallInteger('tahun_proj_awal')->default(0);
            $table->smallInteger('tahun_proj_akhir')->default(0);
            $table->unsignedSmallInteger('jml_sheet')->default(0);
            $table->longText('data');
            $table->unsignedInteger('size_bytes')->default(0);
            $table->timestamps();

            $table->unique([
                'project_id',
                'tahun_hist_awal',
                'tahun_hist_akhir',
                'tahun_proj_awal',
                'tahun_proj_akhir',
            ], 'uq_project_tahun');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_rtk_data');
    }
};
