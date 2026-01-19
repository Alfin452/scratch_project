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
            'content' => 'required|string', // Validasi input form bernama 'content'
            'order' => 'required|integer',
        ]);

        Module::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            // PERBAIKAN: Gunakan input('content') agar tidak bentrok dengan protected property
            'content' => $request->input('content'),
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
            'content' => 'required|string', // Menggunakan $request->input('content') nanti
            'order' => 'required|integer',
            'is_active' => 'boolean',
        ]);

        $module->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'content' => $request->input('content'),
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

        // Ambil daftar semua modul untuk Sidebar Navigasi samping
        if ($user->isTeacher()) {
            $allModules = Module::orderBy('order')->get();
        } else {
            $allModules = Module::where('is_active', true)->orderBy('order')->get();
        }

        // Cari Modul Sebelumnya & Selanjutnya (berdasarkan urutan/order)
        // Kita filter dari $allModules yang sudah diambil di atas agar efisien
        $previous = $allModules->where('order', '<', $module->order)->sortByDesc('order')->first();
        $next = $allModules->where('order', '>', $module->order)->sortBy('order')->first();

        return view('modules.show', compact('module', 'allModules', 'previous', 'next'));
    }

}
