<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Submission;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // <--- PENTING: Jangan sampai lupa baris ini

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
        // === 1. STATISTIK KARTU UTAMA ===
        $totalStudents = User::where('role', 'student')->count();
        $totalModules = Module::count();
        $totalTasks = Task::count();

        // Tugas masuk tapi belum dinilai
        $pendingGrading = Submission::where('status', 'submitted')->count();
        // Tugas yang sudah selesai dinilai
        $gradedCount = Submission::where('status', 'graded')->count();

        // === 2. CHART AREA: TREN 7 HARI TERAKHIR ===
        // Ambil data 7 hari ke belakang
        $sevenDaysAgo = Carbon::now()->subDays(6)->startOfDay();

        $submissions = Submission::select('updated_at')
            ->where('updated_at', '>=', $sevenDaysAgo)
            ->get()
            ->groupBy(function ($data) {
                return Carbon::parse($data->updated_at)->format('Y-m-d');
            });

        $chartDates = [];
        $chartCounts = [];

        // Loop 7 hari agar grafik tidak putus (isi 0 jika kosong)
        for ($i = 0; $i < 7; $i++) {
            $date = $sevenDaysAgo->copy()->addDays($i)->format('Y-m-d');
            $chartDates[] = Carbon::parse($date)->format('d M'); // Label: "20 Jan"
            $chartCounts[] = $submissions->get($date)?->count() ?? 0; // Jumlah atau 0
        }

        // === 3. TABEL AKTIVITAS TERBARU ===
        $recentSubmissions = Submission::with(['user', 'task'])
            ->latest()
            ->take(5)
            ->get();

        // Kirim SEMUA variabel ke view
        return view('dashboard', compact(
            'totalStudents',
            'totalModules',
            'totalTasks',
            'pendingGrading',
            'gradedCount',
            'chartDates',
            'chartCounts',
            'recentSubmissions'
        ));
    }

    private function studentDashboard($user)
    {
        // === 1. STATISTIK UTAMA SISWA ===
        $totalScore = Submission::where('user_id', $user->id)->sum('score');
        $completedTasks = Submission::where('user_id', $user->id)->where('status', 'graded')->count();
        $totalTasksAvailable = Task::count();

        // Hitung Progress %
        $progress = $totalTasksAvailable > 0 ? round(($completedTasks / $totalTasksAvailable) * 100) : 0;

        // === 2. DATA MODUL & CHART SKOR ===
        $modules = Module::with(['tasks' => function ($query) use ($user) {
            $query->with(['submissions' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);
        }])->where('is_active', true)->orderBy('order')->get();

        // Siapkan Data Chart Skor Per Bab
        $moduleLabels = [];
        $moduleScores = [];

        foreach ($modules as $mod) {
            $scorePerModule = 0;
            foreach ($mod->tasks as $task) {
                $sub = $task->submissions->first();
                if ($sub && $sub->score) {
                    $scorePerModule += $sub->score;
                }
            }
            $moduleLabels[] = "Bab " . $mod->order;
            $moduleScores[] = $scorePerModule;
        }

        return view('dashboard', compact(
            'totalScore',
            'completedTasks',
            'totalTasksAvailable',
            'progress',
            'modules',
            'moduleLabels',
            'moduleScores'
        ));
    }
}
