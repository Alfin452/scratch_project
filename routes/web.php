<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('modules', ModuleController::class);

    // 1. Create Task (Butuh Module ID karena kita harus tahu tugas ini nempel di modul mana)
    Route::get('/modules/{module}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/modules/{module}/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // 2. Edit, Update, Delete Task (Cukup Task ID)
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/workspace/task/{task}', [WorkspaceController::class, 'show'])->name('workspace.show');

    Route::post('/tasks/{task}/submit', [SubmissionController::class, 'store'])->name('submissions.store');

    Route::get('/tasks/{task}/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::put('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');

    Route::get('/gradebook', [SubmissionController::class, 'gradebook'])->name('submissions.gradebook');
    });

require __DIR__.'/auth.php';
