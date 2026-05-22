<?php

namespace App\Http\Controllers;

use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the classrooms.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403, 'Akses ditolak. Hanya guru yang dapat mengakses halaman ini.');
        }

        // Fetch classrooms with count of students
        $classrooms = Classroom::withCount('students')->orderBy('name')->get();

        return view('classrooms.index', compact('classrooms'));
    }

    /**
     * Store a newly created classroom in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:classrooms,name',
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas ini sudah ada.',
            'name.max' => 'Nama kelas maksimal 100 karakter.',
        ]);

        Classroom::create([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    /**
     * Update the specified classroom in storage.
     */
    public function update(Request $request, Classroom $classroom)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:classrooms,name,' . $classroom->id,
        ], [
            'name.required' => 'Nama kelas wajib diisi.',
            'name.unique' => 'Nama kelas ini sudah ada.',
            'name.max' => 'Nama kelas maksimal 100 karakter.',
        ]);

        $classroom->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Nama kelas berhasil diperbarui!');
    }

    /**
     * Remove the specified classroom from storage.
     */
    public function destroy(Classroom $classroom)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isTeacher()) {
            abort(403);
        }

        // Detach students from this class by setting classroom_id to null
        $classroom->students()->update(['classroom_id' => null]);

        $classroom->delete();

        return back()->with('success', 'Kelas berhasil dihapus!');
    }
}
