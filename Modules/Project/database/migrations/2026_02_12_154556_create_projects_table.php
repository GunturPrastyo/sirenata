<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration')->comment('Duration in months');
            $table->foreignUuid('team_leader')->constrained('users');
            $table->json('team_members')->nullable();
            $table->string('type')->comment('Nasional, Provinsi, Kab/Kota');
            $table->string('status')->nullable()->default('On Progress');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
