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
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2.5 bg-white dark:bg-gray-800 rounded-xl shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-colors text-indigo-600 border border-gray-100 dark:border-gray-700">
                                            @if($task->type === 'scratch')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @elseif($task->type === 'drag_drop')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5v-1a1.5 1.5 0 013 0v1m0 0V11m0-5.5a1.5 1.5 0 013 0v3m0 0V11"></path></svg>
                                            @elseif($task->type === 'classification')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                                            @elseif($task->type === 'multiple_choice')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                            @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300 mb-1">
                                                Tugas {{ $task->order }} • {{ str_replace('_', ' ', $task->type) }}
                                            </span>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 transition leading-tight line-clamp-1">{{ $task->title }}</h4>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-600">
                                    <p class="text-[11px] text-gray-500 font-medium">Klik untuk menilai</p>
                                    @if($task->submissions_count > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        {{ $task->submissions_count }} Siswa Mengumpulkan
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                        Belum ada
                                    </span>
                                    @endif
                                </div>
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