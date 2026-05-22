<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the students belonging to this classroom.
     */
    public function students()
    {
        return $this->hasMany(User::class, 'classroom_id')->where('role', 'student');
    }
}
