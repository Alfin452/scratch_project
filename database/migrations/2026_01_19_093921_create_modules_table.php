<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_modules_table.php
    public function up(): void
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul Materi, misal: "Pengenalan Loop"
            $table->string('slug')->unique(); // Untuk URL yang cantik
            $table->text('description')->nullable(); // Ringkasan kompetensi
            $table->longText('content'); // Isi materi (teks/gambar/embed video)
            $table->integer('order')->default(0); // Urutan bab
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
