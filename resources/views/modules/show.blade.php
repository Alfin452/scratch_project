<x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'">

    {{-- ========================================== --}}
    {{-- TAMPILAN GURU (ADMIN MODE) --}}
    {{-- ========================================== --}}
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

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg border border-gray-100 dark:border-gray-700 overflow-hidden mb-8">
                <div class="p-8 md:p-10 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 text-xs font-bold tracking-wide uppercase mb-3">
                            BAB {{ $module->order }}
                        </span>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $module->title }}</h1>
                        <p class="text-gray-500 dark:text-gray-400">{{ $module->description }}</p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('modules.edit', $module->id) }}" class="px-5 py-2.5 bg-yellow-500 rotate-0 hover:-rotate-2 transition hover:bg-yellow-600 text-white rounded-xl text-sm font-bold shadow-md">Edit Bab</a>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                        <h3 class="font-extrabold text-xl text-gray-800 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Kurikulum Bab
                        </h3>
                        <div class="flex gap-3">
                            <a href="{{ route('sub_modules.create', $module->id) }}" class="px-4 py-2 bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 hover:bg-emerald-200 text-sm font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                                + Subbab (Materi)
                            </a>
                            <a href="{{ route('tasks.create', $module->id) }}" class="px-4 py-2 bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 hover:bg-indigo-200 text-sm font-bold rounded-xl transition flex items-center gap-1 shadow-sm">
                                + Evaluasi (Tugas)
                            </a>
                        </div>
                    </div>

                    @if($curriculum->count() > 0)
                        <div class="relative border-l-2 border-indigo-100 dark:border-indigo-900 ml-4 lg:ml-8 space-y-8 pb-4">
                            @foreach($curriculum as $item)
                                <div class="relative pl-8 sm:pl-12">
                                    {{-- Titik Garis (Timeline Dot) --}}
                                    <div class="absolute -left-[17px] top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center font-bold text-xs shadow-sm
                                        {{ $item->item_type == 'submodule' ? 'bg-emerald-100 text-emerald-600 border-emerald-500' : 'bg-indigo-100 text-indigo-600 border-indigo-500' }}">
                                        {{ $item->order }}
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-2xl border border-gray-200 dark:border-gray-600 p-5 hover:border-indigo-300 dark:hover:border-indigo-500 transition-colors shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                        <div class="flex items-start gap-4">
                                            <div class="mt-1 w-12 h-12 flex items-center justify-center rounded-xl shrink-0
                                                {{ $item->item_type == 'submodule' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' : 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400' }}">
                                                @if($item->item_type == 'submodule')
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path></svg>
                                                @else
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-xs font-black uppercase tracking-wider {{ $item->item_type == 'submodule' ? 'text-emerald-500' : 'text-indigo-500' }}">
                                                        {{ $item->item_type == 'submodule' ? 'Materi Bacaan' : 'Tugas Praktik' }}
                                                    </span>
                                                    @if($item->item_type == 'task' && $item->deadline)
                                                        <span class="px-2 py-0.5 bg-red-100 text-red-600 text-[10px] rounded-md font-bold">Due: {{ $item->deadline->format('d M') }}</span>
                                                    @endif
                                                </div>
                                                <h4 class="font-extrabold text-gray-900 dark:text-white text-lg">{{ $item->title }}</h4>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                                            @if($item->item_type == 'task')
                                                <a href="{{ route('submissions.index', $item->id) }}" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 text-indigo-600 dark:text-indigo-400 font-bold text-sm rounded-xl transition">Nilai Siswa</a>
                                            @endif
                                            
                                            <a href="{{ $item->item_type == 'submodule' ? route('sub_modules.edit', $item->id) : route('tasks.edit', $item->id) }}" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-400 hover:text-yellow-500 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                            
                                            <form action="{{ $item->item_type == 'submodule' ? route('sub_modules.destroy', $item->id) : route('tasks.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus item kurikulum ini?');" class="inline-block">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white dark:bg-gray-800 text-gray-400 hover:text-red-500 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 px-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl bg-gray-50 dark:bg-gray-800/50">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Kosong</h3>
                            <p class="mt-1 text-sm text-gray-500">Bab ini belum memiliki materi atau tugas.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- TAMPILAN SISWA (LEARNING MODE) --}}
    {{-- ========================================== --}}
    @else

    <div class="py-2 lg:py-2 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex mb-8 gsap-fade-down opacity-0 -translate-y-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition-colors">
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
                            <a href="{{ route('modules.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white md:ml-2 transition-colors">Materi</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">{{ Str::limit($module->title, 25) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8">

                {{-- SIDEBAR: DAFTAR MATERI (STICKY) --}}
                <div class="w-full lg:w-1/4">
                    <div class="sticky top-24 space-y-4 gsap-sidebar opacity-0 -translate-x-4">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-800 dark:text-white text-xs uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                    Navigasi Materi
                                </h3>
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
                                            <span class="line-clamp-2 leading-snug">{{ $item->title }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>

                {{-- KONTEN UTAMA --}}
                <div class="w-full lg:w-3/4 space-y-8">

                    {{-- KARTU MATERI --}}
                    <div class="gsap-content opacity-0 translate-y-8 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden relative">

                        {{-- Decorative Background --}}
                        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

                        {{-- Header Materi --}}
                        <div class="relative p-8 md:p-10 border-b border-gray-100 dark:border-gray-700">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 text-xs font-bold tracking-wide uppercase mb-4">
                                BAB {{ $module->order }}
                            </span>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4 relative z-10">
                                {{ $module->title }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 leading-relaxed max-w-3xl relative z-10">
                                {{ $module->description }}
                            </p>
                        </div>

                        {{-- Isi Konten --}}
                        <div class="p-8 md:p-12">
                            <div class="prose prose-lg prose-indigo max-w-none dark:prose-invert prose-headings:font-bold prose-a:text-indigo-600 hover:prose-a:text-indigo-500">
                                {!! $module->content !!}
                            </div>
                        </div>

                        {{-- Footer Navigasi --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="w-full md:w-auto">
                                @if($previous)
                                <a href="{{ route('modules.show', $previous->id) }}" class="group flex items-center p-3 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md transition-all duration-300 border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
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
                                {{-- KONTEN ASLI (Tersembunyi sampai timer selesai) --}}
                                <div id="next-action-unlocked" class="hidden">
                                    @if($next)
                                    <a href="{{ route('modules.show', $next->id) }}" class="group flex items-center justify-end p-3 rounded-xl hover:bg-white dark:hover:bg-gray-800 hover:shadow-md transition-all duration-300 border border-transparent hover:border-gray-100 dark:hover:border-gray-700">
                                        <div class="text-right">
                                            <div class="text-xs text-gray-400 uppercase font-bold">Selanjutnya</div>
                                            <div class="font-semibold text-gray-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ Str::limit($next->title, 20) }}</div>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center ml-3 group-hover:scale-110 transition-transform">
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

                                {{-- TOMBOL TERKUNCI --}}
                                <div id="next-action-locked" class="flex">
                                    <button disabled class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 font-bold rounded-xl cursor-not-allowed transition-all">
                                        <svg class="w-5 h-5 mr-2 animate-spin text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span id="reading-timer-text">Membaca... (00:00)</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TUGAS / MISI --}}
                    @if($module->tasks->count() > 0)
                    <div class="gsap-task-section opacity-0 translate-y-8 bg-gradient-to-br from-indigo-600 to-purple-700 dark:from-indigo-900 dark:to-purple-900 rounded-3xl shadow-xl p-8 text-white relative overflow-hidden">

                        {{-- Background Decoration --}}
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-purple-500 opacity-20 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10">
                            <h3 class="text-2xl font-extrabold mb-2 flex items-center gap-2">
                                <svg class="w-7 h-7 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                Tantangan Tersedia!
                            </h3>
                            <p class="text-indigo-100 mb-8 max-w-2xl">
                                Uji pemahamanmu dengan menyelesaikan {{ $module->tasks->count() }} misi di bawah ini. Selesaikan untuk mendapatkan XP tambahan!
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($module->tasks as $taskItem)
                                <a href="{{ route('workspace.show', $taskItem->id) }}" class="gsap-task-card opacity-0 translate-y-4 group bg-white/10 backdrop-blur-md border border-white/20 p-5 rounded-2xl hover:bg-white hover:text-indigo-600 transition-all duration-300 flex items-center justify-between shadow-lg hover:shadow-xl hover:-translate-y-1">
                                    <div class="flex flex-col gap-1.5 flex-1 min-w-0">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-white/20 group-hover:bg-indigo-100 group-hover:text-indigo-600 flex items-center justify-center font-bold text-sm transition-colors">
                                                {{ $loop->iteration }}
                                            </div>
                                            <span class="font-bold text-lg truncate">{{ $taskItem->title }}</span>
                                        </div>

                                        @if($taskItem->deadline)
                                        <div class="text-xs font-medium text-indigo-100 group-hover:text-red-500 ml-11 flex items-center transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Batas: {{ $taskItem->deadline->format('d M, H:i') }}
                                        </div>
                                        @else
                                        <div class="text-xs font-medium text-indigo-200 group-hover:text-indigo-400 ml-11 flex items-center transition-colors">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Mulai Kerjakan
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="p-2 rounded-full bg-white/10 group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    </div>
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

    {{-- Script GSAP untuk Siswa & Reading Timer --}}
    @php
        $wordCount = str_word_count(strip_tags($module->content));
        // Hitung target detik: 150 kata per menit. Minimal 60 detik mutlak.
        $readingTimeSeconds = max(60, ceil(($wordCount / 150) * 60));
    @endphp
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.registerPlugin(ScrollTrigger);

            // 1. Breadcrumb Fade Down
            gsap.to(".gsap-fade-down", { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" });

            // 2. Sidebar Slide In
            gsap.to(".gsap-sidebar", { opacity: 1, x: 0, duration: 0.8, delay: 0.2, ease: "power3.out" });

            // 3. Main Content Fade Up
            gsap.to(".gsap-content", { opacity: 1, y: 0, duration: 0.8, delay: 0.4, ease: "power3.out" });

            // 4. Task Section & Cards
            const taskSection = document.querySelector(".gsap-task-section");
            if (taskSection) {
                gsap.to(taskSection, { scrollTrigger: { trigger: taskSection, start: "top 85%" }, opacity: 1, y: 0, duration: 0.8, ease: "power3.out" });
                gsap.to(".gsap-task-card", { scrollTrigger: { trigger: taskSection, start: "top 80%" }, opacity: 1, y: 0, stagger: 0.1, duration: 0.6, ease: "back.out(1.5)" });
            }

            // ==========================================
            // READING TIMER LOGIC
            // ==========================================
            const moduleId = {{ $module->id }};
            const targetSeconds = parseInt('{{ $readingTimeSeconds }}');
            const storageKey = `read_timer_module_${moduleId}`;
            
            // Ambil sisa waktu dari local storage. Jika tidak ada, gunakan target waktu asli.
            let savedTime = localStorage.getItem(storageKey);
            let timeRemaining = savedTime !== null ? parseInt(savedTime) : targetSeconds;

            const lockedEl = document.getElementById('next-action-locked');
            const unlockedEl = document.getElementById('next-action-unlocked');
            const timerText = document.getElementById('reading-timer-text');

            function updateTimerDisplay() {
                if(timeRemaining <= 0) {
                    // Waktu habis, tampilkan tombol asli
                    lockedEl.classList.add('hidden');
                    lockedEl.classList.remove('flex');
                    unlockedEl.classList.remove('hidden');
                    
                    // Supaya ada sedikit efek saat memunculkan
                    gsap.fromTo(unlockedEl, {opacity: 0, x: -10}, {opacity: 1, x: 0, duration: 0.5});
                    return;
                }

                // Format menit dan detik
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerText.textContent = `Membaca... (${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')})`;
            }

            // Jalankan perdana
            updateTimerDisplay();

            if(timeRemaining > 0) {
                const interval = setInterval(() => {
                    timeRemaining--;
                    localStorage.setItem(storageKey, timeRemaining);
                    updateTimerDisplay();

                    if(timeRemaining <= 0) {
                        clearInterval(interval);
                    }
                }, 1000);
            }
        });
    </script>

    @endif

    <style>
        /* Fix for CKEditor image wrapper in Tailwind Prose */
        .prose figure.image {
            display: block;
            margin-bottom: 1.5rem;
            margin-top: 1.5rem;
            max-width: 100%;
            margin-left: auto;
            margin-right: auto;
        }
        .prose figure.image img {
            width: 100%; /* Mengikuti lebar dari figure container yang diresize */
            height: auto;
            margin: 0 auto;
            display: block;
        }
        .prose figure.image figcaption {
            display: block;
            text-align: center !important;
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            font-style: italic;
        }

        /* Pastikan format image style bawaan resize CKEditor bekerja */
        .prose figure.image.image-style-align-left { margin-left: 0; margin-right: auto; }
        .prose figure.image.image-style-align-right { margin-left: auto; margin-right: 0; }
        .prose figure.image.image-style-align-center { margin-left: auto; margin-right: auto; }
        
        /* Fix alignment classes output by CKEditor */
        .prose .text-align-left { text-align: left !important; }
        .prose .text-align-center { text-align: center !important; }
        .prose .text-align-right { text-align: right !important; }
        .prose .text-align-justify { text-align: justify !important; }
        
        /* Dukungan highlight dan warna font */
        .prose mark.marker-yellow { background-color: var(--ck-highlight-marker-yellow); }
        .prose mark.marker-green { background-color: var(--ck-highlight-marker-green); }
        .prose mark.marker-pink { background-color: var(--ck-highlight-marker-pink); }
        .prose mark.marker-blue { background-color: var(--ck-highlight-marker-blue); }
        .prose span.pen-red { color: var(--ck-highlight-pen-red); background-color: transparent; }
        .prose span.pen-green { color: var(--ck-highlight-pen-green); background-color: transparent; }

        /* Dukungan Ukuran Font Bawaan CKEditor (text-tiny, text-huge, dsb) */
        .prose .text-tiny { font-size: 0.7em !important; }
        .prose .text-small { font-size: 0.85em !important; }
        .prose .text-big { font-size: 1.5em !important; }
        .prose .text-huge { font-size: 2em !important; }

        /* Dukungan Spasi Paragraf CKEditor (Menggunakan Style Plugin) */
        .prose .spasi-normal { line-height: normal !important; }
        .prose .spasi-1-15 { line-height: 1.15 !important; }
        .prose .spasi-1-5 { line-height: 1.5 !important; }
        .prose .spasi-2 { line-height: 2 !important; }
    </style>
</x-dynamic-component>