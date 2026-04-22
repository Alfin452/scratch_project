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

        $nextOrder = max(
            $module->subModules()->max('order') ?? 0,
            $module->tasks()->max('order') ?? 0
        ) + 1;

        return view('tasks.create', compact('module', 'nextOrder'));
    }

    public function store(Request $request, Module $module)
    {
        $type = $request->input('type', 'scratch');

        if ($type === 'decomposition') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'main_description'     => 'required|string',
                'min_decomposition'    => 'required|integer|min:1',
                'activities'           => 'required|array|min:1',
                'activities.*.name'    => 'required|string|max:255',
                'activities.*.icon'    => 'required|string',
                'activities.*.steps'   => 'required|array|min:1',
                'activities.*.steps.*' => 'required|string|max:500',
            ]);

            $content = [
                'main_description'  => $request->input('main_description'),
                'min_decomposition' => (int) $request->input('min_decomposition'),
                'activities'        => $request->input('activities'),
            ];

            Task::create([
                'module_id'   => $module->id,
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'type'        => 'decomposition',
                'content'     => $content,
                'order'       => $request->input('order', 0),
            ]);
        } elseif ($type === 'classification') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'category_a'           => 'required|string|max:100',
                'category_b'           => 'required|string|max:100',
                'questions'            => 'required|array|min:1',
                'questions.*.text'     => 'required|string|max:500',
                'questions.*.answer'   => 'required|string|max:100',
                'questions.*.explanation'=> 'required|string',
            ]);

            $content = [
                'categories' => [
                    $request->input('category_a'),
                    $request->input('category_b')
                ],
                'questions' => $request->input('questions'),
            ];

            Task::create([
                'module_id'   => $module->id,
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'type'        => 'classification',
                'content'     => $content,
                'order'       => $request->input('order', 0),
            ]);
        } elseif ($type === 'simulation') {
            $request->validate([
                'title'       => 'required|string|max:255',
                'instruction' => 'required|string',
                'deadline'    => 'nullable|date',
            ]);

            Task::create([
                'module_id'   => $module->id,
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'type'        => 'simulation',
                'content'     => [], // Kontennya hardcoded di frontend
                'order'       => $request->input('order', 0),
            ]);
        } elseif ($type === 'drag_and_drop') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'pick_count'           => 'required|integer|min:1',
                'activities'           => 'required|array|min:1',
                'activities.*.name'    => 'required|string|max:255',
                'activities.*.icon'    => 'required|string',
                'activities.*.steps'   => 'required|array|min:2',
                'activities.*.steps.*' => 'required|string|max:500',
            ]);

            $content = [
                'pick_count' => (int) $request->input('pick_count'),
                'activities' => $request->input('activities'),
            ];

            Task::create([
                'module_id'   => $module->id,
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'type'        => 'drag_and_drop',
                'content'     => $content,
                'order'       => $request->input('order', 0),
            ]);
        } else {
            $request->validate([
                'title'        => 'required|string|max:255',
                'instruction'  => 'required|string',
                'deadline'     => 'nullable|date',
                'starter_file' => 'nullable|file|extensions:sb3|max:10240',
            ]);

            $path = null;
            if ($request->hasFile('starter_file')) {
                $path = $request->file('starter_file')->store('starter_projects', 'public');
            }

            Task::create([
                'module_id'            => $module->id,
                'title'                => $request->title,
                'instruction'          => $request->instruction,
                'deadline'             => $request->deadline,
                'starter_project_path' => $path,
                'type'                 => 'scratch',
                'order'                => $request->input('order', 0),
            ]);
        }

        return redirect()->route('modules.show', $module->id)->with('success', 'Tugas berhasil ditambahkan!');
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

    public function update(Request $request, Task $task)
    {
        $type = $task->type; // Tipe tidak bisa diubah setelah dibuat

        if ($type === 'decomposition') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'main_description'     => 'required|string',
                'min_decomposition'    => 'required|integer|min:1',
                'activities'           => 'required|array|min:1',
                'activities.*.name'    => 'required|string|max:255',
                'activities.*.icon'    => 'required|string',
                'activities.*.steps'   => 'required|array|min:1',
                'activities.*.steps.*' => 'required|string|max:500',
            ]);

            $content = [
                'main_description'  => $request->input('main_description'),
                'min_decomposition' => (int) $request->input('min_decomposition'),
                'activities'        => $request->input('activities'),
            ];

            $task->update([
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'content'     => $content,
                'order'       => $request->input('order', $task->order),
            ]);
        } elseif ($type === 'classification') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'category_a'           => 'required|string|max:100',
                'category_b'           => 'required|string|max:100',
                'questions'            => 'required|array|min:1',
                'questions.*.text'     => 'required|string|max:500',
                'questions.*.answer'   => 'required|string|max:100',
                'questions.*.explanation'=> 'required|string',
            ]);

            $content = [
                'categories' => [
                    $request->input('category_a'),
                    $request->input('category_b')
                ],
                'questions' => $request->input('questions'),
            ];

            $task->update([
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'content'     => $content,
                'order'       => $request->input('order', $task->order),
            ]);
        } elseif ($type === 'simulation') {
            $request->validate([
                'title'       => 'required|string|max:255',
                'instruction' => 'required|string',
                'deadline'    => 'nullable|date',
            ]);

            $task->update([
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'content'     => [], // Konten hardcoded
                'order'       => $request->input('order', $task->order),
            ]);
        } elseif ($type === 'drag_and_drop') {
            $request->validate([
                'title'                => 'required|string|max:255',
                'instruction'          => 'required|string',
                'deadline'             => 'nullable|date',
                'pick_count'           => 'required|integer|min:1',
                'activities'           => 'required|array|min:1',
                'activities.*.name'    => 'required|string|max:255',
                'activities.*.icon'    => 'required|string',
                'activities.*.steps'   => 'required|array|min:2',
                'activities.*.steps.*' => 'required|string|max:500',
            ]);

            $content = [
                'pick_count' => (int) $request->input('pick_count'),
                'activities' => $request->input('activities'),
            ];

            $task->update([
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'content'     => $content,
                'order'       => $request->input('order', $task->order),
            ]);
        } else {
            $request->validate([
                'title'        => 'required|string|max:255',
                'instruction'  => 'required|string',
                'deadline'     => 'nullable|date',
                'starter_file' => 'nullable|file|extensions:sb3|max:10240',
            ]);

            if ($request->hasFile('starter_file')) {
                if ($task->starter_project_path) {
                    Storage::disk('public')->delete($task->starter_project_path);
                }
                $task->starter_project_path = $request->file('starter_file')->store('starter_projects', 'public');
            }

            $task->update([
                'title'       => $request->title,
                'instruction' => $request->instruction,
                'deadline'    => $request->deadline,
                'order'       => $request->input('order', $task->order),
            ]);
        }

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
