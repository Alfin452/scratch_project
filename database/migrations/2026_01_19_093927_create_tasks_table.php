<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_tasks_table.php
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Relasi ke Module (Satu modul bisa punya banyak tugas)
            $table->foreignId('module_id')->constrained()->onDelete('cascade');

            $table->string('title'); // Judul Tugas, misal: "Buat Kucing Berjalan"
            $table->text('instruction'); // Petunjuk pengerjaan

            // Path file .sb3 starter project yang diupload guru
            // Jika null, berarti siswa mulai dari kanvas kosong
            $table->string('starter_project_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
