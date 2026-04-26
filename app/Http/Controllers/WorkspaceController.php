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
        $isCourseCompleted = false;

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
                    $nextUrl = route('modules.show_student', $nextModule->id);
                } else {
                    $nextUrl = route('dashboard');
                    $isCourseCompleted = true;
                }
            }
        }

        // Cek apakah siswa sudah pernah submit tugas ini?
        $submission = Submission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->first();

        if ($task->type === 'scratch') {
            return view('workspace.scratch', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        // Route ke view yang berbeda berdasarkan tipe tugas
        if ($task->type === 'decomposition') {
            return view('workspace.decomposition', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        if ($task->type === 'drag_and_drop') {
            return view('workspace.drag_drop', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        if ($task->type === 'classification') {
            if ($submission && $submission->status === 'graded') {
                return view('workspace.classification_result', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
            }
            return view('workspace.classification', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        if ($task->type === 'multiple_choice') {
            if ($submission && $submission->status === 'graded') {
                return view('workspace.multiple_choice_result', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
            }
            return view('workspace.multiple_choice', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        if ($task->type === 'simulation') {
            if ($submission && $submission->status === 'graded') {
                return view('workspace.simulation_result', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
            }
            return view('workspace.simulation', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
        }

        return view('workspace.index', compact('task', 'submission', 'nextUrl', 'nextTask', 'isCourseCompleted'));
    }

    public function submit(\Illuminate\Http\Request $request, \App\Models\Task $task)
    {
        // Jika tugas bertipe drag_and_drop, decomposition, classification, simulation, atau multiple_choice simpan jawaban JSON
        if ($task->type === 'drag_and_drop' || $task->type === 'decomposition' || $task->type === 'classification' || $task->type === 'simulation' || $task->type === 'multiple_choice') {
            $request->validate([
                'answer_data' => 'required', // Bisa array atau string JSON
            ]);

            $answerData = $request->input('answer_data');
            
            // Jika dikirim sebagai string JSON, decode dulu. Jika sudah array/objek, pakai langsung.
            if (is_string($answerData)) {
                $answerData = json_decode($answerData, true);
            }

            // Auto-Scoring untuk Classification
            $score = null;
            $status = 'submitted';
            if ($task->type === 'classification') {
                $correctCount = 0;
                $questions = $task->content['questions'] ?? [];
                $totalQuestions = count($questions);
                
                if ($totalQuestions > 0 && is_array($answerData)) {
                    foreach ($questions as $index => $q) {
                        if (isset($answerData[$index]) && $answerData[$index] === $q['answer']) {
                            $correctCount++;
                        }
                    }
                    $score = round(($correctCount / $totalQuestions) * 100);
                    $status = 'graded'; // Langsung nilai
                }
            } elseif ($task->type === 'multiple_choice') {
                $correctCount = 0;
                $questions = $task->content['questions'] ?? [];
                $totalQuestions = count($questions);
                
                if ($totalQuestions > 0 && is_array($answerData)) {
                    foreach ($questions as $index => $q) {
                        // answerData[$index] will store the chosen option index.
                        // $q['correct_option'] holds the correct option index.
                        if (isset($answerData[$index]) && (int)$answerData[$index] === (int)$q['correct_option']) {
                            $correctCount++;
                        }
                    }
                    $score = round(($correctCount / $totalQuestions) * 100);
                    $status = 'graded';
                }
            } elseif ($task->type === 'simulation') {
                // Untuk simulasi, jika sudah dikirim berarti dianggap selesai 100
                $score = 100;
                $status = 'graded';
            }

            $submission = Submission::updateOrCreate(
                ['user_id' => Auth::id(), 'task_id' => $task->id],
                [
                    'answer_data' => $answerData,
                    'status'      => $status,
                    'score'       => $score,
                    'feedback'    => null,
                ]
            );

            return response()->json(['message' => 'Jawaban berhasil dikumpulkan!', 'redirect' => route('workspace.show', $task->id)], 200);
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

    public function retry(Request $request, Task $task)
    {
        $user = Auth::user();
        
        // Hapus submission yang ada agar siswa bisa mengulang
        Submission::where('user_id', $user->id)
            ->where('task_id', $task->id)
            ->delete();

        return redirect()->route('workspace.show', $task->id)->with('success', 'Silakan ulangi latihan ini. Berikan yang terbaik!');
    }
}
