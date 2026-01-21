<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function show(Task $task)
    {
        // Pastikan user adalah siswa (opsional, guru juga boleh intip)
        $user = Auth::user();

        // Cek apakah siswa sudah pernah submit tugas ini?
        $submission = Submission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        return view('workspace.index', compact('task', 'submission'));
    }

    public function submit(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        // 1. Validasi File (Harus .sb3 dan maks 10MB)
        $request->validate([
            'project_file' => 'required|file|extensions:sb3|max:10240',
        ]);

        // 2. Simpan File ke Storage
        $path = $request->file('project_file')->store('submissions', 'public');

        // 3. Cari Data Submission Lama (Cek apakah siswa sudah pernah kumpul?)
        $submission = \App\Models\Submission::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('task_id', $task->id)
            ->first();

        if ($submission) {
            // Jika sudah ada, Update (Hapus file lama dulu biar server gak penuh)
            if ($submission->file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->file_path);
            }

            $submission->update([
                'file_path' => $path,
                'status' => 'submitted', // Reset status jadi 'menunggu penilaian' jika re-upload
            ]);
        } else {
            // Jika belum ada, Buat Baru
            \App\Models\Submission::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'task_id' => $task->id,
                'file_path' => $path,
                'status' => 'submitted',
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan! Guru akan segera menilainya.');
    }
}
