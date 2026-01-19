<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isTeacher()) {
            return $this->teacherDashboard();
        } else {
            return $this->studentDashboard($user);
        }
    }

    private function teacherDashboard()
    {
        // Statistik untuk Guru
        $totalStudents = User::where('role', 'student')->count();
        $totalModules = Module::count();
        $totalTasks = Task::count();

        // Tugas yang perlu dinilai (Status submitted tapi belum graded)
        $pendingGrading = Submission::where('status', 'submitted')->count();

        // 5 Pengumpulan Terbaru
        $recentSubmissions = Submission::with(['user', 'task'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('totalStudents', 'totalModules', 'totalTasks', 'pendingGrading', 'recentSubmissions'));
    }

    private function studentDashboard($user)
    {
        // Statistik untuk Siswa
        // Hitung total skor yang didapat
        $totalScore = Submission::where('user_id', $user->id)->sum('score');

        // Hitung tugas yang sudah selesai (graded)
        $completedTasks = Submission::where('user_id', $user->id)->where('status', 'graded')->count();

        // Total Tugas yang ada di sistem
        $totalTasksAvailable = Task::count();

        // Hitung Persentase Progress (Cegah pembagian nol)
        $progress = $totalTasksAvailable > 0 ? round(($completedTasks / $totalTasksAvailable) * 100) : 0;

        // Modul terakhir yang diakses (opsional, kita ambil modul pertama saja kalau belum ada tracking history)
        // Disini kita tampilkan daftar modul agar siswa cepat akses
        $modules = Module::with(['tasks' => function ($query) use ($user) {
            $query->with(['submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);
        }])->where('is_active', true)->orderBy('order')->get();

        return view('dashboard', compact('totalScore', 'completedTasks', 'totalTasksAvailable', 'progress', 'modules'));
    }
}
