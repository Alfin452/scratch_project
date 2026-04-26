<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\IndependentTaskController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google', [SocialiteController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'callback']);
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/modules/upload-image', [ModuleController::class, 'uploadImage'])->name('modules.uploadImage');
    Route::resource('modules', ModuleController::class);

    // 1. Create Task (Butuh Module ID karena kita harus tahu tugas ini nempel di modul mana)
    Route::get('/modules/{module}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/modules/{module}/tasks', [TaskController::class, 'store'])->name('tasks.store');

    // SubModules (Materi)
    Route::get('/modules/{module}/sub-modules/create', [App\Http\Controllers\SubModuleController::class, 'create'])->name('sub_modules.create');
    Route::post('/modules/{module}/sub-modules', [App\Http\Controllers\SubModuleController::class, 'store'])->name('sub_modules.store');
    Route::get('/sub-modules/{sub_module}/edit', [App\Http\Controllers\SubModuleController::class, 'edit'])->name('sub_modules.edit');
    Route::put('/sub-modules/{sub_module}', [App\Http\Controllers\SubModuleController::class, 'update'])->name('sub_modules.update');
    Route::delete('/sub-modules/{sub_module}', [App\Http\Controllers\SubModuleController::class, 'destroy'])->name('sub_modules.destroy');
    Route::get('/workspace/sub-modules/{sub_module}', [App\Http\Controllers\SubModuleController::class, 'showStudent'])->name('sub_modules.show_student');
    Route::post('/workspace/sub-modules/{sub_module}/complete', [App\Http\Controllers\SubModuleController::class, 'complete'])->name('sub_modules.complete');

    // 2. Edit, Update, Delete Task (Cukup Task ID)
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/workspace/task/{task}', [WorkspaceController::class, 'show'])->name('workspace.show');

    Route::post('/tasks/{task}/submit', [SubmissionController::class, 'store'])->name('submissions.store');
    Route::post('/workspace/task/{task}/submit', [WorkspaceController::class, 'submit'])->name('workspace.submit');
    Route::post('/workspace/task/{task}/retry', [WorkspaceController::class, 'retry'])->name('workspace.retry');
    Route::get('/tasks/{task}/submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::put('/submissions/{submission}/grade', [SubmissionController::class, 'grade'])->name('submissions.grade');
    Route::get('/submissions/{submission}/download', [SubmissionController::class, 'download'])
        ->name('submissions.download');
    Route::get('/gradebook', [SubmissionController::class, 'gradebook'])->name('submissions.gradebook');

    // Route Khusus Siswa
    Route::get('/student/tasks', [App\Http\Controllers\TaskController::class, 'studentIndex'])->name('student.tasks');
    Route::get('/student/activity', [App\Http\Controllers\SubmissionController::class, 'history'])->name('student.activity');

    Route::get('/student/leaderboard', [App\Http\Controllers\SubmissionController::class, 'leaderboard'])->name('student.leaderboard');

    Route::resource('independent-tasks', IndependentTaskController::class)->except(['show', 'edit', 'update']);
    });

require __DIR__.'/auth.php';
