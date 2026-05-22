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

        // Siapkan Data Chart Skor Per Bab & Status Gembok
        $moduleLabels = [];
        $moduleScores = [];
        
        $isPreviousModuleCompleted = true; // Modul 1 selalu terbuka
        $nextModuleToLearn = null; // Menyimpan modul pertama yang belum komplit

        foreach ($modules as $mod) {
            $scorePerModule = 0;
            // Anggap modul ini komplit, lalu verifikasi
            $isModuleCompleted = true;

            // Verifikasi Tugas
            foreach ($mod->tasks as $task) {
                // Periksa apakah tugas ini punya status 'graded' atau setidaknya 'submitted'
                $sub = $task->submissions->first();
                if ($sub) {
                    if ($sub->score !== null && $sub->score < 70) {
                        $isModuleCompleted = false; // Jika nilai di bawah KKM, belum tuntas
                    }
                    if ($sub->score !== null) {
                        $scorePerModule += $sub->score;
                    }
                } else {
                    // Jika ada satu saja tugas yang belum disubmit, modul ini belum tamat
                    $isModuleCompleted = false;
                }
            }

            // Verifikasi SubModule (Materi Bacaan)
            // (Hanya membebankan query kecil di memori / lazy load kalau belum ke-load)
            foreach ($mod->subModules as $subModule) {
               $progressRecord = \App\Models\StudentProgress::where('user_id', $user->id)
                                  ->where('sub_module_id', $subModule->id)->first();
               if (!$progressRecord) {
                   $isModuleCompleted = false;
               }
            }

            // Simpan modul pertama yang belum selesai sebagai target lanjutan
            if (!$isModuleCompleted && !$nextModuleToLearn && $isPreviousModuleCompleted) {
                $nextModuleToLearn = $mod;
            }

            // Set atribut gembok
            // Modul terbuka JIKA modul sebelumnya sudah komplet.
            $mod->is_unlocked = $isPreviousModuleCompleted;

            $moduleLabels[] = "Bab " . $mod->order;
            $moduleScores[] = $scorePerModule;

            // Update pengecekan untuk iterasi loop berikutnya (Modul selanjutnya)
            // Jika modul saat ini gagal komplet, maka modul selanjutnya akan terkunci
            $isPreviousModuleCompleted = $isModuleCompleted;
        }

        return view('dashboard', compact(
            'totalScore',
            'completedTasks',
            'totalTasksAvailable',
            'progress',
            'modules',
            'moduleLabels',
            'moduleScores',
            'nextModuleToLearn'
        ));
    }

    /**
     * Menyimpan kelas pilihan siswa.
     */
    public function selectClass(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
        ], [
            'classroom_id.required' => 'Silakan pilih kelas Anda terlebih dahulu.',
            'classroom_id.exists' => 'Kelas yang dipilih tidak valid.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isStudent()) {
            abort(403, 'Hanya siswa yang dapat memilih kelas.');
        }

        $user->update([
            'classroom_id' => $request->classroom_id,
        ]);

        return back()->with('success', 'Kelas berhasil dipilih, selamat belajar!');
    }
}

