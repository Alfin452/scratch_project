<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_submissions_table.php
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa
            $table->foreignId('task_id')->constrained()->onDelete('cascade'); // Tugas mana

            // File hasil kerja siswa (.sb3)
            $table->string('project_file_path');

            // Nilai dan Feedback (Nullable karena awal submit belum dinilai)
            $table->integer('score')->nullable(); // 0-100
            $table->text('feedback')->nullable(); // Komentar guru

            // Status: 'submitted', 'graded', 'returned'
            $table->enum('status', ['submitted', 'graded'])->default('submitted');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
