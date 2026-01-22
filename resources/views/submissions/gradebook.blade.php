<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekapitulasi Penilaian') }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-2">

            <div class="mb-6">
                <p class="text-gray-500 dark:text-gray-400">Pilih tugas di bawah ini untuk mulai memeriksa dan memberi nilai kepada siswa.</p>
            </div>

            <div class="grid grid-cols-1 gap-8">

                {{-- BAGIAN 1: TUGAS MANDIRI / BANK SOAL (NEW) --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500 border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">
                            <span class="bg-purple-100 text-purple-700 text-xs font-bold px-2 py-1 rounded uppercase tracking-wider">Bank Soal</span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tugas Mandiri / Ujian</h3>
                        </div>

                        @if($independentTasks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($independentTasks as $task)
                            <a href="{{ route('submissions.index', $task->id) }}" class="block group relative bg-purple-50 dark:bg-purple-900/10 hover:bg-purple-100 dark:hover:bg-purple-900/30 border border-purple-200 dark:border-purple-600 rounded-xl p-5 transition-all duration-200 hover:shadow-md hover:border-purple-400">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm group-hover:bg-purple-600 group-hover:text-white transition-colors text-purple-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    @if($task->submissions_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        {{ $task->submissions_count }} Pengumpulan
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                        Belum ada
                                    </span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1 group-hover:text-purple-700 dark:group-hover:text-purple-400 transition">{{ $task->title }}</h4>
                                <p class="text-xs text-gray-500 line-clamp-2">Klik untuk memberi nilai tugas mandiri ini.</p>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-400 italic">Belum ada tugas mandiri yang dibuat.</p>
                        @endif
                    </div>
                </div>

                {{-- BAGIAN 2: TUGAS PER MODUL (EXISTING) --}}
                @foreach($modules as $module)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">
                            <span class="bg-indigo-100 text-indigo-700 text-xs font-bold px-2 py-1 rounded">BAB {{ $module->order }}</span>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $module->title }}</h3>
                        </div>

                        @if($module->tasks->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($module->tasks as $task)
                            <a href="{{ route('submissions.index', $task->id) }}" class="block group relative bg-gray-50 dark:bg-gray-700/50 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 border border-gray-200 dark:border-gray-600 rounded-xl p-5 transition-all duration-200 hover:shadow-md hover:border-indigo-300">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors text-indigo-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    @if($task->submissions_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                        {{ $task->submissions_count }} Pengumpulan
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-300">
                                        Belum ada
                                    </span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-1 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition">{{ $task->title }}</h4>
                                <p class="text-xs text-gray-500 line-clamp-2">Klik untuk melihat detail pengumpulan dan memberi nilai.</p>
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-sm text-gray-400 italic">Tidak ada tugas praktik di modul ini.</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>