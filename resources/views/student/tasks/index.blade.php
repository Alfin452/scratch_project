<x-student-layout>

    {{-- GSAP Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    {{-- Hero Banner --}}
    <div class="gsap-hero opacity-0 -translate-y-4 bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-900 dark:to-purple-900 pt-12 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xKSIvPjwvc3ZnPg==')] opacity-20"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-2">
                Misi & Tantangan
            </h1>
            <p class="text-indigo-100 max-w-2xl mx-auto text-lg">
                Selesaikan tantangan koding di bawah ini untuk menguji kemampuanmu dan dapatkan nilai terbaik!
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 pb-12 relative z-20">

        @if($tasks->count() > 0)
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($tasks as $task)
            @php
            $submission = $task->submissions->first();
            $status = $submission ? $submission->status : 'pending';

            // Konfigurasi Tampilan Berdasarkan Status
            if (!$submission) {
            $borderClass = 'border-l-4 border-l-gray-300 dark:border-l-gray-600';
            $badgeClass = 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300';
            $statusText = 'Belum Dikerjakan';
            $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>';
            $btnClass = 'text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300';
            $btnText = 'Kerjakan';
            } elseif ($submission->status == 'submitted') {
            $borderClass = 'border-l-4 border-l-yellow-400';
            $badgeClass = 'bg-yellow-50 text-yellow-700 border border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-700';
            $statusText = 'Menunggu Nilai';
            $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>';
            $btnClass = 'text-yellow-600 hover:text-yellow-800 dark:text-yellow-400';
            $btnText = 'Lihat / Revisi';
            } else { // graded
            $borderClass = 'border-l-4 border-l-green-500';
            $badgeClass = 'bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-700';
            $statusText = 'Selesai';
            $icon = '<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>';
            $btnClass = 'text-green-600 hover:text-green-800 dark:text-green-400';
            $btnText = 'Lihat Nilai';
            }
            @endphp

            <div class="gsap-card opacity-0 translate-y-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 flex flex-col hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group {{ $borderClass }}">

                {{-- Decorative Circle on Hover --}}
                <div class="absolute top-0 right-0 -mr-10 -mt-10 w-32 h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                {{-- Header Kartu --}}
                <div class="flex justify-between items-start mb-4 relative z-10">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-300">
                        Bab {{ $task->module->order ?? '?' }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $badgeClass }}">
                        {!! $icon !!} {{ $statusText }}
                    </span>
                </div>

                {{-- Konten Utama --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1 relative z-10" title="{{ $task->title }}">
                    {{ $task->title }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-3 mb-6 flex-1 relative z-10">
                    {{ $task->instruction }}
                </p>

                {{-- Footer Kartu --}}
                <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between relative z-10">
                    <div>
                        @if($submission && $submission->score !== null)
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-400 uppercase font-bold">Nilai</span>
                            <span class="text-lg font-extrabold text-green-600 dark:text-green-400">{{ $submission->score }}</span>
                        </div>
                        @else
                        <div class="flex items-center text-xs text-gray-400 dark:text-gray-500">
                            @if($task->starter_project_path)
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            Starter File
                            @else
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Project Baru
                            @endif
                        </div>
                        @endif
                    </div>

                    <a href="{{ route('workspace.show', $task->id) }}" class="inline-flex items-center text-sm font-bold {{ $btnClass }} transition-colors group-hover:underline">
                        {{ $btnText }}
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="gsap-empty opacity-0 translate-y-4 text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Belum Ada Tugas</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Guru belum mempublikasikan tugas baru. Santai dulu sejenak!</p>
        </div>
        @endif
    </div>

    {{-- Script GSAP Animations --}}
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.registerPlugin(ScrollTrigger);

            // Animasi Hero Banner
            gsap.to(".gsap-hero", {
                opacity: 1,
                y: 0,
                duration: 1,
                ease: "power3.out"
            });

            // Animasi Kartu (Stagger Effect)
            if (document.querySelector('.gsap-card')) {
                gsap.to(".gsap-card", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.1,
                    delay: 0.3,
                    ease: "back.out(1.2)"
                });
            }

            // Animasi Empty State
            if (document.querySelector('.gsap-empty')) {
                gsap.to(".gsap-empty", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: 0.3,
                    ease: "power3.out"
                });
            }
        });
    </script>

</x-student-layout>