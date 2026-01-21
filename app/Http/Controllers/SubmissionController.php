<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'project_file' => 'required|file|extensions:sb3,zip|max:10240', // Max 10MB
        ]);

        $user = Auth::user();

        // Cek apakah siswa sudah pernah submit (untuk update) atau buat baru
        $submission = Submission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        $path = $request->file('project_file')->store('submissions', 'public');

        if ($submission) {
            // Update submission lama
            // Hapus file lama biar hemat storage
            if ($submission->project_file_path) {
                Storage::disk('public')->delete($submission->project_file_path);
            }

            $submission->update([
                'project_file_path' => $path,
                'status' => 'submitted', // Reset status jika sebelumnya sudah dinilai
            ]);
        } else {
            // Buat submission baru
            Submission::create([
                'user_id' => $user->id,
                'task_id' => $task->id,
                'project_file_path' => $path,
                'status' => 'submitted',
            ]);
        }

        return response()->json(['message' => 'Tugas berhasil dikumpulkan!'], 200);
    }

    // ... method store yang lama ...

    // Menampilkan daftar submission per Tugas (Khusus Guru)
    public function index(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        // Ambil semua submission untuk task ini, beserta data user-nya
        $submissions = Submission::with('user')
            ->where('task_id', $task->id)
            ->latest()
            ->get();

        return view('submissions.index', compact('task', 'submissions'));
    }

    // Menyimpan Nilai & Feedback
    public function grade(Request $request, Submission $submission)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'status' => 'graded',
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }

    // Halaman Dashboard Penilaian (Daftar Semua Tugas)
    public function gradebook()
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        // Ambil semua modul beserta tugas-tugasnya dan hitung jumlah submission
        $modules = \App\Models\Module::with(['tasks' => function ($query) {
            $query->withCount('submissions'); // Menghitung berapa siswa yang sudah kumpul
        }])->orderBy('order')->get();

        return view('submissions.gradebook', compact('modules'));
    }

    // Menampilkan riwayat pengumpulan siswa
    public function history()
    {
        $submissions = \App\Models\Submission::with(['task.module'])
            ->where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->latest()
            ->get();

        return view('student.activity', compact('submissions'));
    }

    // Menampilkan Peringkat / Leaderboard
    public function leaderboard()
    {
        // Ambil semua user dengan role 'student'
        // Hitung total score dari relasi submissions
        // Urutkan dari yang terbesar (descending)
        // Ambil 50 besar saja biar tidak berat
        $students = \App\Models\User::where('role', 'student')
            ->withSum('submissions', 'score')
            ->orderByDesc('submissions_sum_score')
            ->take(50)
            ->get();

        return view('student.leaderboard', compact('students'));
    }
}
