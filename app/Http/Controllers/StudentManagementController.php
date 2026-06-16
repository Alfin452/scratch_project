<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\SubModule;
use App\Models\StudentProgress;
use App\Models\Submission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentManagementController extends Controller
{
    // 1. Daftar Siswa
    public function index()
    {
        $students = User::where('role', 'student')
            ->with('classroom')
            ->latest()
            ->get();

        return view('teacher.students.index', compact('students'));
    }

    // 2. Rekapitulasi Nilai (Matriks)
    public function grades(Request $request)
    {
        $classrooms = \App\Models\Classroom::all();

        // Ambil semua task yang berhubungan dengan modul, diurutkan berdasarkan modul dan urutan tugas
        $tasks = Task::with('module')
            ->whereNotNull('module_id')
            ->join('modules', 'tasks.module_id', '=', 'modules.id')
            ->orderBy('modules.order')
            ->orderBy('tasks.order')
            ->select('tasks.*')
            ->get();

        $query = User::where('role', 'student')
            ->with(['submissions' => function($q) {
                $q->select('id', 'user_id', 'task_id', 'score');
            }, 'classroom']);

        // Filter search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kelas
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Ambil semua siswa beserta submission mereka untuk memetakan nilai
        $students = $query->get();

        return view('teacher.students.grades', compact('students', 'tasks', 'classrooms'));
    }

    // 3. Progress Siswa
    public function progress(Request $request)
    {
        $classrooms = \App\Models\Classroom::all();
        $totalSubModules = SubModule::count();
        $totalTasks = Task::count();

        $query = User::where('role', 'student')
            ->with('classroom')
            ->withCount('submissions as completed_tasks') // jumlah tugas yang dikerjakan (submission)
            ->withCount('submissions as active_submissions');

        // Filter search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kelas
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Ambil semua siswa dengan relasi yang dibutuhkan
        $students = $query->get();

        // Ambil kemajuan bacaan materi (SubModule)
        $progressMateri = StudentProgress::selectRaw('user_id, count(*) as total_read')
            ->groupBy('user_id')
            ->pluck('total_read', 'user_id');

        // Ambil aktivitas terakhir per siswa (submission terakhir)
        $lastActivities = Submission::selectRaw('user_id, max(updated_at) as last_activity')
            ->groupBy('user_id')
            ->pluck('last_activity', 'user_id');

        foreach ($students as $student) {
            $student->read_materials = $progressMateri->get($student->id, 0);
            $last_act = $lastActivities->get($student->id);
            $student->last_activity = $last_act ? Carbon::parse($last_act) : null;
            
            // Status: Aktif jika ada aktivitas dalam 14 hari terakhir
            if ($student->last_activity) {
                $student->status = $student->last_activity->diffInDays(now()) <= 14 ? 'Aktif' : 'Pasif';
            } else {
                $student->status = 'Belum Ada Aktivitas';
            }
        }

        // Filter status (dilakukan pada collection karena dihitung manual)
        if ($request->filled('status')) {
            $students = $students->filter(function($student) use ($request) {
                return $student->status === $request->status;
            });
        }

        return view('teacher.students.progress', compact('students', 'totalSubModules', 'totalTasks', 'classrooms'));
    }
}
