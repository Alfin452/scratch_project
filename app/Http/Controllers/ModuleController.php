<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User; // Pastikan Model User di-import
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // Guru melihat semua modul (termasuk yang non-aktif)
        // Siswa hanya melihat modul yang aktif (is_active = true)

        if ($user->isTeacher()) {
            $modules = Module::orderBy('order')->get();
        } else {
            $modules = Module::where('is_active', true)->orderBy('order')->get();
        }

        return view('modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403, 'Akses Ditolak. Hanya Guru yang boleh membuat materi.');
        }
        return view('modules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        Module::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'content' => '', // Dummy content
            'order' => $request->order,
            'is_active' => true,
        ]);

        return redirect()->route('modules.index')->with('success', 'Modul berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }
        return view('modules.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $module->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'content' => '', // Dummy content
            'order' => $request->order,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('modules.index')->with('success', 'Modul berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            abort(403);
        }

        $module->delete();

        return redirect()->route('modules.index')->with('success', 'Modul berhasil dihapus!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        /** @var User $user */
        $user = Auth::user();

        // Validasi: Jika siswa, pastikan modul aktif. Guru bebas lihat draft.
        if (!$user->isTeacher() && !$module->is_active) {
            abort(404);
        }

        $module->load(['subModules', 'tasks']);

        // Gabungkan submodules dan tasks, lalu urutkan
        $curriculum = collect();
        foreach($module->subModules as $subModule) {
            $subModule->item_type = 'submodule';
            $curriculum->push($subModule);
        }
        foreach($module->tasks as $task) {
            $task->item_type = 'task';
            $curriculum->push($task);
        }
        $curriculum = $curriculum->sortBy('order')->values();

        if ($user->isTeacher()) {
            return view('modules.show', compact('module', 'curriculum'));
        }

        // Logic Siswa (Student)
        $allModules = Module::where('is_active', true)->orderBy('order')->get();
        
        // --- CEK GEMBOK MODUL (MODULE LOCKING) ---
        // Verifikasi apakah semua modul sebelumnya sudah komplit
        $isLocked = false;
        foreach ($allModules as $mod) {
            if ($mod->id === $module->id) break; // Cek berurutan sampai modul saat ini
            
            // Periksa tasks
            $mod->loadMissing('tasks.submissions', 'subModules');
            foreach ($mod->tasks as $task) {
                $sub = $task->submissions->where('user_id', $user->id)->first();
                if (!$sub) {
                    $isLocked = true;
                    break 2; // Langsung keluar dari semua loop
                }
            }
            // Periksa submodules
            foreach ($mod->subModules as $subm) {
                $prog = \App\Models\StudentProgress::where('user_id', $user->id)
                                    ->where('sub_module_id', $subm->id)->first();
                if (!$prog) {
                    $isLocked = true;
                    break 2;
                }
            }
        }

        if ($isLocked) {
            return redirect()->route('dashboard')->withErrors(['Bab ini masih terkunci! Selesaikan bab sebelumnya terlebih dahulu.']);
        }
        // ----------------------------------------
        
        $previous = $allModules->where('order', '<', $module->order)->sortByDesc('order')->first();
        $next = $allModules->where('order', '>', $module->order)->sortBy('order')->first();

        // Ambil data submission tasks dan studentprogress untuk module ini
        $submissions = \App\Models\Submission::where('user_id', $user->id)
            ->whereIn('task_id', $module->tasks->pluck('id'))
            ->get()->keyBy('task_id');
            
        $progress = \App\Models\StudentProgress::where('user_id', $user->id)
            ->whereIn('sub_module_id', $module->subModules->pluck('id'))
            ->get()->keyBy('sub_module_id');

        return view('modules.show_student', compact('module', 'allModules', 'previous', 'next', 'curriculum', 'submissions', 'progress'));
    }

    /**
     * Handle image upload from CKEditor.
     */
    public function uploadImage(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user->isTeacher()) {
            return response()->json(['error' => ['message' => 'Unauthorized']], 403);
        }

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Simpan gambar di folder public/storage/modules
            $path = $file->storeAs('modules', $filename, 'public');

            $url = asset('storage/' . $path);

            return response()->json([
                'url' => $url
            ]);
        }

        return response()->json(['error' => ['message' => 'No file uploaded']], 400);
    }
}
