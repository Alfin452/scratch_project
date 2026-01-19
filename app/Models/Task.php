<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // --- RELATIONS ---

    // Setiap Tugas milik satu Modul
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    // Satu Tugas bisa memiliki banyak Submission (karena dikerjakan banyak siswa)
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
}
