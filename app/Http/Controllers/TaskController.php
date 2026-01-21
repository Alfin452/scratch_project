<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    // Kita tidak butuh index() karena daftar tugas akan muncul di detail modul

    /**
     * Show the form for creating a new resource.
     * Kita butuh module_id agar tahu tugas ini milik siapa.
     */
    public function create(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        return view('tasks.create', compact('module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            // UBAH BARIS INI:
            // Kita izinkan ekstensi sb3 secara eksplisit, dan naikkan limit max jadi 10MB (10240 KB)
            'starter_file' => 'nullable|file|extensions:sb3,zip|max:10240',
        ], [
            // Custom pesan error agar lebih jelas
            'starter_file.extensions' => 'File harus berekstensi .sb3',
            'starter_file.max' => 'Ukuran file tidak boleh lebih dari 10MB',
        ]);

        $path = null;
        if ($request->hasFile('starter_file')) {
            $path = $request->file('starter_file')->store('starters', 'public');
        }

        Task::create([
            'module_id' => $module->id,
            'title' => $request->title,
            'instruction' => $request->instruction,
            'starter_project_path' => $path,
        ]);

        return redirect()->route('modules.show', $module->id)->with('success', 'Tugas praktik berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'starter_file' => 'nullable|file|mimes:sb3,zip|max:5120',
        ]);

        // Logic Update File jika ada upload baru
        if ($request->hasFile('starter_file')) {
            // Hapus file lama jika ada
            if ($task->starter_project_path) {
                Storage::disk('public')->delete($task->starter_project_path);
            }
            // Upload file baru
            $task->starter_project_path = $request->file('starter_file')->store('starters', 'public');
        }

        $task->update([
            'title' => $request->title,
            'instruction' => $request->instruction,
            // starter_project_path diupdate manual di atas
        ]);

        return redirect()->route('modules.show', $task->module_id)->with('success', 'Tugas berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) abort(403);

        $moduleId = $task->module_id;

        // Hapus file fisik
        if ($task->starter_project_path) {
            Storage::disk('public')->delete($task->starter_project_path);
        }

        $task->delete();

        return redirect()->route('modules.show', $moduleId)->with('success', 'Tugas berhasil dihapus!');
    }

    // Menampilkan daftar semua tugas untuk siswa
    public function studentIndex()
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Ambil semua tugas, urutkan dari yang terbaru
        // Kita load juga submission milik user yang sedang login untuk cek status
        $tasks = \App\Models\Task::with(['module', 'submissions' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])->get();

        return view('student.tasks.index', compact('tasks'));
    }
}
