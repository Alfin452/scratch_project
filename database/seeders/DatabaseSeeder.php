<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Module;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat Akun GURU
        User::create([
            'name' => 'Bapak Guru',
            'email' => 'guru@skripsi.com',
            'password' => Hash::make('password'), // Passwordnya: password
            'role' => 'teacher',
            'email_verified_at' => now(),
        ]);

        // 2. Buat Akun SISWA
        User::create([
            'name' => 'Siswa Teladan',
            'email' => 'siswa@skripsi.com',
            'password' => Hash::make('password'), // Passwordnya: password
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // 3. Buat Contoh MODUL (Materi)
        $modul1 = Module::create([
            'title' => 'Bab 1: Pengenalan Scratch',
            'slug' => 'bab-1-pengenalan-scratch',
            'description' => 'Memahami antarmuka dan blok dasar Scratch.',
            'content' => '<h2>Selamat Datang di Scratch</h2><p>Di bab ini kita akan belajar cara menggerakkan Sprite...</p>',
            'order' => 1,
            'is_active' => true,
        ]);

        // 4. Buat Contoh TUGAS (Task) untuk Modul 1
        Task::create([
            'module_id' => $modul1->id,
            'title' => 'Latihan 1: Kucing Berjalan',
            'instruction' => 'Buatlah algoritma agar Kucing bergerak maju 10 langkah saat bendera hijau diklik.',
            'starter_project_path' => null, // Nanti kita isi kalau fitur upload sudah jadi
        ]);

        $this->command->info('Data dummy berhasil dibuat! Login pakai email: guru@skripsi.com / password');
    }
}
