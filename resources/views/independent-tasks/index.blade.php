<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bank Soal / Tugas Mandiri') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-6">
                <p class="text-gray-500 dark:text-gray-400">Tugas ini tidak terikat modul dan akan muncul di menu "Tugas" siswa.</p>
                <a href="{{ route('independent-tasks.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold text-sm shadow">
                    + Buat Tugas Mandiri
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    @if($tasks->count() > 0)
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Judul Tugas</th>
                                <th class="px-6 py-3">Instruksi</th>
                                <th class="px-6 py-3">Jawaban Masuk</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tasks as $task)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                    {{ $task->title }}
                                    @if($task->starter_project_path)
                                    <span class="ml-2 text-[10px] bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Ada File</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 truncate max-w-xs">{{ Str::limit($task->instruction, 50) }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('submissions.index', $task->id) }}" class="text-indigo-600 hover:underline font-bold">
                                        {{ $task->submissions->count() }} Siswa
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('independent-tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?');" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="text-center py-10 text-gray-500">
                        Belum ada tugas mandiri. Silakan buat baru.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>