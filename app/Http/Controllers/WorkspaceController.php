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
        $user = Auth::user();

        // Cari Modul
        $module = $task->module;
        $nextUrl = route('dashboard'); 
        $nextTask = null;

        if ($module) {
            // Ambil kurikulum
            $module->load(['subModules', 'tasks']);
            $curriculum = collect();
            foreach($module->subModules as $sm) {
                $sm->item_type = 'submodule';
                $curriculum->push($sm);
            }
            foreach($module->tasks as $t) {
                $t->item_type = 'task';
                $curriculum->push($t);
            }
            $curriculum = $curriculum->sortBy('order')->values();

            // Cari index tugas ini
            $currentIndex = $curriculum->search(function ($item) use ($task) {
                return $item->item_type === 'task' && $item->id === $task->id;
            });

            // Cari item selanjutnya
            $nextItem = ($currentIndex !== false && isset($curriculum[$currentIndex + 1])) 
                        ? $curriculum[$currentIndex + 1] 
                        : null;

            if ($nextItem) {
                $nextUrl = $nextItem->item_type === 'submodule' 
                            ? route('sub_modules.show_student', $nextItem->id) 
                            : route('workspace.show', $nextItem->id);
            } else {
                $nextModule = \App\Models\Module::where('is_active', true)->where('order', '>', $module->order)->orderBy('order')->first();
                if ($nextModule) {
                    $nextUrl = route('modules.show', $nextModule->id);
                } else {
                    $nextUrl = route('dashboard');
                }
            }
        }

        // Cek apakah siswa sudah pernah submit tugas ini?
        $submission = Submission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        // Route ke view yang berbeda berdasarkan tipe tugas
        if ($task->type === 'decomposition') {
            return view('workspace.decomposition', compact('task', 'submission', 'nextUrl', 'nextTask'));
        }

        if ($task->type === 'drag_and_drop') {
            return view('workspace.drag_drop', compact('task', 'submission', 'nextUrl', 'nextTask'));
        }

        return view('workspace.index', compact('task', 'submission', 'nextUrl', 'nextTask'));
    }

    public function submit(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        // Jika tugas bertipe drag_and_drop atau decomposition, simpan jawaban JSON
        if ($task->type === 'drag_and_drop' || $task->type === 'decomposition') {
            $request->validate([
                'answer_data' => 'required', // Bisa array atau string JSON
            ]);

            $answerData = $request->input('answer_data');
            
            // Jika dikirim sebagai string JSON, decode dulu. Jika sudah array/objek, pakai langsung.
            if (is_string($answerData)) {
                $answerData = json_decode($answerData, true);
            }

            $submission = Submission::updateOrCreate(
                ['user_id' => Auth::id(), 'task_id' => $task->id],
                [
                    'answer_data' => $answerData,
                    'status'      => 'submitted',
                    'score'       => null,
                    'feedback'    => null,
                ]
            );

            return response()->json(['message' => 'Jawaban berhasil dikumpulkan!'], 200);
        }

        // Tugas Scratch - simpan file
        $request->validate([
            'project_file' => 'required|file|extensions:sb3|max:10240',
        ]);

        $path = $request->file('project_file')->store('submissions', 'public');

        $submission = \App\Models\Submission::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('task_id', $task->id)
            ->first();

        if ($submission) {
            if ($submission->project_file_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($submission->project_file_path);
            }

            $submission->update([
                'project_file_path' => $path,
                'status'            => 'submitted',
            ]);
        } else {
            \App\Models\Submission::create([
                'user_id'           => \Illuminate\Support\Facades\Auth::id(),
                'task_id'           => $task->id,
                'project_file_path' => $path,
                'status'            => 'submitted',
            ]);
        }

        return back()->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
