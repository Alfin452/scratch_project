<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialiteController extends Controller
{
    // 1. Arahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // 2. Handle balikan data dari Google
    public function callback()
    {
        try {
            // Ambil user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan google_id ATAU email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            if ($user) {
                // Jika user sudah ada, update google_id jika belum ada, lalu login
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                Auth::login($user);
            } else {
                // Jika user belum ada, BUAT BARU (Otomatis jadi SISWA)
                $newUser = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'role' => 'student', // <--- PENTING: Default role siswa
                    'password' => bcrypt(Str::random(16)), // Password acak karena login via google
                ]);
                Auth::login($newUser);
            }

            // Redirect ke Dashboard
            return redirect('/dashboard');
        } catch (\Exception $e) {
            // Jika gagal/cancel
            return redirect('/')->with('error', 'Login Google Gagal atau Dibatalkan.');
        }
    }
}
