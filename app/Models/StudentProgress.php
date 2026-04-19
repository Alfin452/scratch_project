<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progress';
    protected $fillable = ['user_id', 'sub_module_id', 'completed_at'];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subModule()
    {
        return $this->belongsTo(SubModule::class);
    }
}
