<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IndependentTaskController extends Controller
{
    // Tampilkan Daftar Tugas Mandiri
    public function index()
    {
        // Ambil task yang tidak punya module_id (NULL)
        $tasks = Task::whereNull('module_id')->latest()->get();
        return view('independent-tasks.index', compact('tasks'));
    }

    // Form Tambah Tugas Mandiri
    public function create()
    {
        return view('independent-tasks.create');
    }

    // Simpan Tugas Mandiri
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'starter_file' => 'nullable|file|extensions:sb3|max:10240',
        ]);

        $path = null;
        if ($request->hasFile('starter_file')) {
            $path = $request->file('starter_file')->store('starter_projects', 'public');
        }

        Task::create([
            'module_id' => null, // PENTING: Set NULL karena ini tugas mandiri
            'title' => $request->title,
            'instruction' => $request->instruction,
            'starter_project_path' => $path,
        ]);

        return redirect()->route('independent-tasks.index')->with('success', 'Tugas Mandiri berhasil dibuat!');
    }

    // Hapus Tugas
    public function destroy(Task $task)
    {
        if ($task->starter_project_path) {
            Storage::disk('public')->delete($task->starter_project_path);
        }
        $task->delete();
        return back()->with('success', 'Tugas berhasil dihapus.');
    }
}
