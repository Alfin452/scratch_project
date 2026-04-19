<?php

namespace App\Http\Controllers;

use App\Models\SubModule;
use App\Models\Module;
use App\Models\StudentProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubModuleController extends Controller
{
    public function create(Module $module)
    {
        if (!Auth::user()->isTeacher()) abort(403);
        
        $nextOrder = max(
            $module->subModules()->max('order') ?? 0,
            $module->tasks()->max('order') ?? 0
        ) + 1;

        return view('sub_modules.create', compact('module', 'nextOrder'));
    }

    public function store(Request $request, Module $module)
    {
        if (!Auth::user()->isTeacher()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
        ]);

        SubModule::create([
            'module_id' => $module->id,
            'title' => $request->title,
            'content' => $request->input('content'),
            'order' => $request->order,
        ]);

        return redirect()->route('modules.show', $module->id)->with('success', 'Materi berhasil ditambahkan!');
    }

    public function edit(SubModule $subModule)
    {
        if (!Auth::user()->isTeacher()) abort(403);
        
        return view('sub_modules.edit', compact('subModule'));
    }

    public function update(Request $request, SubModule $subModule)
    {
        if (!Auth::user()->isTeacher()) abort(403);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'order' => 'required|integer',
        ]);

        $subModule->update([
            'title' => $request->title,
            'content' => $request->input('content'),
            'order' => $request->order,
        ]);

        return redirect()->route('modules.show', $subModule->module_id)->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(SubModule $subModule)
    {
        if (!Auth::user()->isTeacher()) abort(403);

        $moduleId = $subModule->module_id;
        $subModule->delete();

        return redirect()->route('modules.show', $moduleId)->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Tampilan untuk siswa membaca Subbab
     */
    public function showStudent(SubModule $subModule)
    {
        $user = Auth::user();
        if ($user->isTeacher()) abort(403); // Guru lihat lewat dashboard

        $module = $subModule->module;

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

        // Cari item selanjutnya berdasarkan order
        $currentIndex = $curriculum->search(function ($item) use ($subModule) {
            return $item->item_type === 'submodule' && $item->id === $subModule->id;
        });

        $nextItem = ($currentIndex !== false && isset($curriculum[$currentIndex + 1])) 
                    ? $curriculum[$currentIndex + 1] 
                    : null;

        // Cari Bab selanjutnya jika sudah habis
        $nextModule = null;
        if (!$nextItem) {
            $nextModule = Module::where('is_active', true)->where('order', '>', $module->order)->orderBy('order')->first();
        }

        return view('sub_modules.show_student', compact('subModule', 'module', 'nextItem', 'nextModule'));
    }

    /**
     * Tandai Subbab selesai dibaca
     */
    public function complete(Request $request, SubModule $subModule)
    {
        $user = Auth::user();
        
        StudentProgress::firstOrCreate([
            'user_id' => $user->id,
            'sub_module_id' => $subModule->id,
        ], [
            'completed_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
