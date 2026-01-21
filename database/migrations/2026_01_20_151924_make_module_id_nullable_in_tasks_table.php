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
        Schema::table('tasks', function (Blueprint $table) {
            // Mengubah kolom module_id agar boleh NULL (nullable)
            $table->unsignedBigInteger('module_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Kembalikan menjadi TIDAK boleh NULL
            $table->unsignedBigInteger('module_id')->nullable(false)->change();
        });
    }
};
