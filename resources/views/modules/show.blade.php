<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $module->title }}
            </h2>
            <a href="{{ route('modules.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col lg:flex-row gap-6">

                <div class="w-full lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden sticky top-6 border border-gray-100 dark:border-gray-700">
                        <div class="p-4 bg-indigo-50 dark:bg-gray-700 border-b border-indigo-100 dark:border-gray-600">
                            <h3 class="font-bold text-indigo-900 dark:text-indigo-300 text-sm uppercase tracking-wide">Daftar Materi</h3>
                        </div>
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700 max-h-[60vh] overflow-y-auto">
                            @foreach($allModules as $item)
                            <li>
                                <a href="{{ route('modules.show', $item->id) }}"
                                    class="block px-4 py-3 text-sm transition-colors {{ $item->id == $module->id ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-500 font-semibold dark:bg-indigo-900/20 dark:text-indigo-300' : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600 dark:text-gray-400 dark:hover:bg-gray-700' }}">
                                    <span class="mr-2 opacity-70">#{{ $item->order }}</span> {{ $item->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="w-full lg:w-3/4">
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl border border-gray-100 dark:border-gray-700 overflow-hidden min-h-[500px] flex flex-col">

                        <div class="p-6 md:p-10 border-b border-gray-100 dark:border-gray-700">
                            <span class="inline-block py-1 px-3 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 text-xs font-bold tracking-wide mb-3">
                                BAB {{ $module->order }}
                            </span>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">
                                {{ $module->title }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ $module->description }}
                            </p>
                        </div>

                        <div class="p-6 md:p-10 flex-1">
                            <div class="prose prose-indigo prose-lg max-w-none dark:prose-invert 
                                prose-headings:font-bold prose-headings:text-gray-800 dark:prose-headings:text-gray-100
                                prose-p:text-gray-600 dark:prose-p:text-gray-300 prose-p:leading-relaxed
                                prose-a:text-indigo-600 dark:prose-a:text-indigo-400 hover:prose-a:underline
                                prose-img:rounded-xl prose-img:shadow-lg">

                                {!! $module->content !!}

                            </div>
                        </div>

                        @if(Auth::user()->isTeacher())
                        <div class="border-t border-gray-100 dark:border-gray-700 p-6 md:p-10 bg-indigo-50/50 dark:bg-gray-800/50">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Tugas Praktik (Mode Guru)
                                </h3>
                                <a href="{{ route('tasks.create', $module->id) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow transition">
                                    + Tambah Soal
                                </a>
                            </div>

                            @if($module->tasks->count() > 0)
                            <div class="space-y-3">
                                @foreach($module->tasks as $task)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex items-center justify-between shadow-sm">
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $task->title }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $task->starter_project_path ? 'File Starter Tersedia 📎' : 'Tanpa File Starter' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">

                                        <a href="{{ route('submissions.index', $task->id) }}" class="flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-md hover:bg-indigo-200 transition" title="Lihat Jawaban Siswa">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ $task->submissions->count() }} Jawaban
                                        </a>

                                        <a href="{{ route('tasks.edit', $task->id) }}" class="p-2 text-gray-400 hover:text-yellow-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>

                                        <button onclick="confirmDeleteTask({{ $task->id }})" class="p-2 text-gray-400 hover:text-red-500 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        <form id="delete-task-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: none;">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada soal praktik untuk modul ini.</p>
                            </div>
                            @endif
                        </div>

                        <script>
                            function confirmDeleteTask(id) {
                                Swal.fire({
                                    title: 'Hapus Tugas?',
                                    text: "Jawaban siswa untuk tugas ini juga akan terhapus!",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ef4444',
                                    cancelButtonColor: '#6b7280',
                                    confirmButtonText: 'Ya, Hapus'
                                }).then((result) => {
                                    if (result.isConfirmed) document.getElementById('delete-task-' + id).submit();
                                })
                            }
                        </script>
                        @endif

                        <div class="bg-gray-50 dark:bg-gray-900/50 p-6 border-t border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-center gap-4">

                            <div class="w-full md:w-auto">
                                @if($previous)
                                <a href="{{ route('modules.show', $previous->id) }}" class="flex items-center justify-center md:justify-start group text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition">
                                    <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center shadow-sm group-hover:border-indigo-300 mr-3">
                                        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-xs text-gray-400 uppercase">Sebelumnya</div>
                                        <div class="font-semibold text-sm">{{ Str::limit($previous->title, 20) }}</div>
                                    </div>
                                </a>
                                @else
                                <div class="hidden md:block"></div> @endif
                            </div>

                            <div class="w-full md:w-auto">
                                @if($next)
                                <a href="{{ route('modules.show', $next->id) }}" class="flex items-center justify-center md:justify-end group text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400 uppercase">Selanjutnya</div>
                                        <div class="font-semibold text-sm">{{ Str::limit($next->title, 20) }}</div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 flex items-center justify-center shadow-sm group-hover:border-indigo-300 ml-3">
                                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>
                                @else
                                <a href="{{ route('modules.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-lg font-semibold text-white hover:bg-indigo-700 transition shadow-lg hover:shadow-indigo-500/30">
                                    Selesai Belajar
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </a>
                                @endif
                            </div>

                        </div>
                    </div>

                    @if($module->tasks->count() > 0)
                    <div class="mt-8 p-6 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl shadow-xl text-white flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h3 class="text-xl font-bold mb-1">Tantangan Pemrograman Tersedia!</h3>
                            <p class="text-indigo-100 text-sm">Ada {{ $module->tasks->count() }} latihan untuk menguji pemahamanmu di bab ini.</p>
                        </div>
                        <div class="space-y-4 mt-4 md:mt-0 w-full md:w-auto">
                            @foreach($module->tasks as $taskItem)
                            <a href="{{ route('workspace.show', $taskItem->id) }}" class="flex items-center justify-between w-full px-6 py-3 bg-white text-indigo-600 font-bold rounded-lg shadow-md hover:bg-gray-50 transition">
                                <span>{{ $taskItem->title }}</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>