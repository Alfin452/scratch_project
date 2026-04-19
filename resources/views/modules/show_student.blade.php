<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Materi Alfin452') }} - {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Modul --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg mb-8 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                <div class="p-8 md:p-10 relative z-10 border-b border-gray-100 dark:border-gray-700">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300 text-xs font-bold tracking-wide uppercase mb-4">
                        BAB {{ $module->order }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4">
                        {{ $module->title }}
                    </h1>
                    <p class="text-lg text-gray-500 dark:text-gray-400">
                        {{ $module->description }}
                    </p>
                </div>
            </div>

            {{-- Timeline Kurikulum --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-lg p-8 md:p-10">
                <h3 class="font-extrabold text-xl text-gray-800 dark:text-white flex items-center gap-2 mb-8">
                    <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Jalur Pembelajaran
                </h3>

                @if($curriculum->count() > 0)
                    @php 
                        $isUnlocked = true; // Item pertama selalu terbuka. Jika ini jadi false, sisa list terkunci.
                        $firstIncompleteIndex = -1;
                    @endphp

                    <div class="relative border-l-2 border-indigo-100 dark:border-indigo-900 ml-4 lg:ml-8 space-y-8 pb-4">
                        @foreach($curriculum as $index => $item)
                            @php
                                $status = 'locked';
                                $isCompleted = false;

                                if ($item->item_type == 'submodule') {
                                    $isCompleted = isset($progress[$item->id]);
                                } else {
                                    // task is completed if it has a submission
                                    $isCompleted = isset($submissions[$item->id]);
                                }

                                if ($isUnlocked) {
                                    $status = $isCompleted ? 'completed' : 'available';
                                }

                                // Jika item ini belum komplit, item SELANJUTNYA akan terkunci (linear progression)
                                if (!$isCompleted && $isUnlocked) {
                                    if ($firstIncompleteIndex === -1) {
                                        $firstIncompleteIndex = $index;
                                    }
                                    $isUnlocked = false; 
                                }

                                // Route berdasarkan tipe
                                $route = $item->item_type == 'submodule' 
                                            ? route('sub_modules.show_student', $item->id) 
                                            : route('workspace.show', $item->id);
                            @endphp

                            <div class="relative pl-8 sm:pl-12 group transition-all duration-300 {{ $status === 'locked' ? 'opacity-60' : '' }}">
                                {{-- Titik Garis (Timeline Dot) --}}
                                <div class="absolute -left-[17px] top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center font-bold text-xs shadow-sm transition-colors duration-300
                                    @if($status === 'completed') bg-emerald-500 text-white border-emerald-500
                                    @elseif($status === 'available') bg-indigo-500 text-white border-indigo-500
                                    @else bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-gray-300 dark:border-gray-600
                                    @endif">
                                    @if($status === 'completed')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    @elseif($status === 'locked')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                                    @endif
                                </div>

                                {{-- Card Content --}}
                                @if($status === 'locked')
                                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 flex flex-col sm:flex-row items-center gap-4 filter grayscale-[0.5]">
                                @else
                                <a href="{{ $route }}" class="block bg-white dark:bg-gray-700/50 rounded-2xl border border-gray-200 dark:border-gray-600 p-5 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md transition-all duration-300 flex flex-col sm:flex-row items-center gap-4 hover:-translate-y-1">
                                @endif
                                
                                    <div class="flex items-center gap-4 flex-1 w-full relative">
                                        <div class="mt-1 w-12 h-12 flex items-center justify-center rounded-xl shrink-0
                                            @if($status === 'completed') bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400
                                            @elseif($status === 'available') bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400
                                            @else bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400
                                            @endif">
                                            @if($item->item_type == 'submodule')
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path></svg>
                                            @else
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"></path></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-xs font-black uppercase tracking-wider 
                                                    @if($status === 'completed') text-emerald-500
                                                    @elseif($status === 'available') text-indigo-500
                                                    @else text-gray-500
                                                    @endif">
                                                    {{ $item->item_type == 'submodule' ? 'Materi Bacaan' : 'Tugas Praktik' }}
                                                </span>
                                                @if($status === 'available')
                                                    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 dark:bg-indigo-900/60 dark:text-indigo-300 text-[10px] rounded-md font-bold animate-pulse">BERIKUTNYA</span>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">{{ $item->title }}</h4>
                                        </div>
                                    </div>

                                @if($status === 'locked')
                                </div>
                                @else
                                    <div class="shrink-0 mt-3 sm:mt-0 text-gray-400 group-hover:text-indigo-500 transition-colors">
                                        <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </div>
                                </a>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol Navigasi Bawah --}}
                    <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4">
                        @if($previous)
                            <a href="{{ route('modules.show', $previous->id) }}" class="px-6 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition font-bold text-gray-700 dark:text-gray-300 flex items-center justify-center w-full sm:w-auto">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                Bab Sebelumnya
                            </a>
                        @else
                            <div></div>
                        @endif

                        @if($firstIncompleteIndex === -1 && $next)
                            {{-- Semua udh kelar, bisa ke modul next --}}
                            <a href="{{ route('modules.show', $next->id) }}" class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl transition font-bold shadow-lg shadow-emerald-500/30 flex items-center justify-center w-full sm:w-auto">
                                Lanjut ke Bab Berikutnya
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @elseif($firstIncompleteIndex !== -1)
                            {{-- Masih ada yg belum diselesaikan, arahkan ke the first incomplete item --}}
                            @php
                                $nextTarget = $curriculum[$firstIncompleteIndex];
                                $nextRoute = $nextTarget->item_type == 'submodule' 
                                            ? route('sub_modules.show_student', $nextTarget->id) 
                                            : route('workspace.show', $nextTarget->id);
                            @endphp
                            <a href="{{ $nextRoute }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition font-bold shadow-lg shadow-indigo-500/30 flex items-center justify-center w-full sm:w-auto">
                                Lanjutkan Pembelajaran
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                            </a>
                        @else
                            {{-- Bab Terakhir dan Selesai --}}
                            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-purple-500 to-indigo-600 text-white rounded-xl hover:from-purple-600 hover:to-indigo-700 transition font-bold shadow-lg flex items-center justify-center w-full sm:w-auto">
                                Kembali ke Dashboard
                            </a>
                        @endif
                    </div>

                @else
                    <div class="text-center py-12 px-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-3xl">
                        <svg class="mx-auto h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Belum Ada Materi</h3>
                        <p class="mt-2 text-sm text-gray-500">Materi untuk bab ini sedang dalam proses penyusunan.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-student-layout>
