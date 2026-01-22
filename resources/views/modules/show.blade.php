<x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'">

    {{-- HEADER (KHUSUS GURU) --}}
    @if(Auth::user()->isTeacher())
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detail Materi') }}
            </h2>
            <a href="{{ route('modules.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar
            </a>
        </div>
    </x-slot>
    @endif

    <div class="py-2 md:py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-2">

            {{-- BREADCRUMB (KHUSUS SISWA) --}}
            @if(!Auth::user()->isTeacher())
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('modules.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white md:ml-2">Materi</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">{{ Str::limit($module->title, 20) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
            @endif

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- SIDEBAR: DAFTAR MATERI --}}
                <div class="w-full lg:w-1/4">
                    <div class="sticky top-24 space-y-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-2 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-800 dark:text-white text-sm uppercase tracking-wider">Navigasi Materi</h3>
                            </div>
                            <nav class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                                <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($allModules as $item)
                                    <li>
                                        <a href="{{ route('modules.show', $item->id) }}"
                                            class="group flex items-center px-4 py-3.5 text-sm transition-all duration-200 
                                           {{ $item->id == $module->id 
                                             ? 'bg-indigo-50 text-indigo-700 border-l-4 border-indigo-600 font-bold dark:bg-indigo-900/20 dark:text-indigo-300' 
                                             : 'text-gray-600 hover:bg-gray-50 hover:text-indigo-600 hover:pl-5 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                                            <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-md text-xs font-bold mr-3 
                                                {{ $item->id == $module->id ? 'bg-indigo-200 text-indigo-800' : 'bg-gray-100 text-gray-500 group-hover:bg-indigo-100 group-hover:text-indigo-600' }}">
                                                {{ $item->order }}
                                            </span>
                                            <span class="line-clamp-2">{{ $item->title }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- KONTEN UTAMA --}}
                <div class="w-full lg:w-3/4">

                    {{-- KARTU MATERI --}}
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">

                        {{-- HEADER MATERI --}}
                        <div class="relative p-8 md:p-10 border-b border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-3xl"></div>

                            <div class="relative z-10">
                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 text-xs font-bold tracking-wide uppercase mb-4">
                                    BAB {{ $module->order }}
                                </span>
                                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
                                    {{ $module->title }}
                                </h1>
                                <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed max-w-3xl">
                                    {{ $module->description }}
                                </p>
                            </div>
                        </div>

                        {{-- ISI KONTEN --}}
                        <div class="p-8 md:p-12">
                            <div class="prose prose-lg prose-indigo max-w-none dark:prose-invert">
                                {!! $module->content !!}
                            </div>
                        </div>

                        {{-- FOOTER NAVIGASI --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="w-full md:w-auto">
                                @if($previous)
                                <a href="{{ route('modules.show', $previous->id) }}" class="group flex items-center p-3 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md transition-all duration-300">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-xs text-gray-400 uppercase font-bold">Sebelumnya</div>
                                        <div class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ Str::limit($previous->title, 20) }}</div>
                                    </div>
                                </a>
                                @endif
                            </div>

                            <div class="w-full md:w-auto">
                                @if($next)
                                <a href="{{ route('modules.show', $next->id) }}" class="group flex items-center justify-end p-3 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md transition-all duration-300">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-400 uppercase font-bold">Selanjutnya</div>
                                        <div class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ Str::limit($next->title, 20) }}</div>
                                    </div>
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </a>
                                @else
                                <a href="{{ route('modules.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                                    Selesai Belajar
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- SECTION: TUGAS / PRAKTIK --}}

                    {{-- MODE GURU: Panel Kelola Tugas --}}
                    @if(Auth::user()->isTeacher())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-indigo-100 dark:border-indigo-900/50 overflow-hidden">
                        <div class="p-6 bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-between border-b border-indigo-100 dark:border-indigo-800">
                            <h3 class="font-bold text-lg text-indigo-900 dark:text-indigo-300 flex items-center">
                                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Manajemen Tugas Praktik
                            </h3>
                            <a href="{{ route('tasks.create', $module->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Soal
                            </a>
                        </div>
                        <div class="p-6">
                            @if($module->tasks->count() > 0)
                            <div class="grid gap-4">
                                @foreach($module->tasks as $task)
                                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl border border-gray-100 dark:border-gray-700 hover:border-indigo-300 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                                            {{ $loop->iteration }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">{{ $task->title }}</h4>

                                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                                {{-- LABEL DEADLINE --}}
                                                @if($task->deadline)
                                                <span class="inline-flex items-center text-red-600 dark:text-red-400 font-medium">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Due: {{ $task->deadline->format('d M H:i') }}
                                                </span>
                                                @endif

                                                @if($task->starter_project_path)
                                                <span class="inline-flex items-center text-green-600 mr-3"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg> Starter File</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('submissions.index', $task->id) }}" class="px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition" title="Lihat Jawaban">
                                            {{ $task->submissions->count() }} Submisi
                                        </a>
                                        <div class="h-4 w-px bg-gray-300 dark:bg-gray-600 mx-2"></div>
                                        <a href="{{ route('tasks.edit', $task->id) }}" class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <button onclick="confirmDeleteTask({{ $task->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        <form id="delete-task-{{ $task->id }}" action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-8">
                                <p class="text-gray-500 italic">Belum ada soal praktik untuk modul ini.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    <script>
                        function confirmDeleteTask(id) {
                            Swal.fire({
                                title: 'Hapus Tugas?',
                                text: "Jawaban siswa yang sudah masuk juga akan terhapus!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#ef4444',
                                confirmButtonText: 'Ya, Hapus'
                            }).then((result) => {
                                if (result.isConfirmed) document.getElementById('delete-task-' + id).submit();
                            });
                        }
                    </script>
                    @endif

                    {{-- MODE SISWA: Daftar Misi --}}
                    @if(!Auth::user()->isTeacher() && $module->tasks->count() > 0)
                    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl"></div>

                        <div class="relative z-10">
                            <h3 class="text-2xl font-extrabold mb-2">Tantangan Koding Tersedia!</h3>
                            <p class="text-indigo-100 mb-6 max-w-2xl">
                                Uji pemahamanmu dengan menyelesaikan {{ $module->tasks->count() }} misi di bawah ini.
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($module->tasks as $taskItem)
                                <a href="{{ route('workspace.show', $taskItem->id) }}" class="group bg-white/10 backdrop-blur-sm border border-white/20 p-4 rounded-xl hover:bg-white hover:text-indigo-600 transition-all duration-300 flex items-center justify-between">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-white/20 group-hover:bg-indigo-100 group-hover:text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                {{ $loop->iteration }}
                                            </div>
                                            <span class="font-bold text-lg">{{ $taskItem->title }}</span>
                                        </div>

                                        {{-- LABEL DEADLINE SISWA --}}
                                        @if($taskItem->deadline)
                                        <div class="text-xs font-medium text-indigo-100 group-hover:text-red-500 ml-11 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Batas: {{ $taskItem->deadline->format('d M, H:i') }}
                                        </div>
                                        @endif
                                    </div>
                                    <svg class="w-6 h-6 opacity-70 group-hover:opacity-100 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>