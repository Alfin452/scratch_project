<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id', // Tambahan untuk Socialite
        'avatar',    // Tambahan untuk foto profil Google
        'role',      // 'teacher' atau 'student'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- RELATIONS ---

    // Seorang User (Siswa) punya banyak Submission (Tugas yang dikumpul)
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    // --- HELPER METHODS ---

    // Cek apakah user ini Guru
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    // Cek apakah user ini Siswa
    public function isStudent()
    {
        return $this->role === 'student';
    }
    
}
