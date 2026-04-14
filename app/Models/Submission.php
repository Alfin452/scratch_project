<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'answer_data' => 'array',
    ];

    // --- RELATIONS ---

    // Submission ini milik siapa? (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Submission ini untuk tugas apa?
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
