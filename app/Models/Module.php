<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    // Semua kolom boleh diisi kecuali ID (keamanan standar)
    protected $guarded = ['id'];

    // --- RELATIONS ---

    // Satu Modul memiliki banyak Tugas (Tasks)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
