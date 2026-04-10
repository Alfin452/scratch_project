<x-student-layout>

    {{-- GSAP Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-4 gsap-header opacity-0 -translate-y-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Aktivitas Belajar</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Riwayat pengerjaan tugas dan hasil evaluasi guru.</p>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 flex gap-4 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        <span class="text-gray-600 dark:text-gray-300">Dinilai</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-orange-400"></span>
                        <span class="text-gray-600 dark:text-gray-300">Menunggu</span>
                    </div>
                </div>
            </div>

            @if($submissions->count() > 0)
            <div class="space-y-6">
                @foreach($submissions as $sub)
                @php
                $isGraded = $sub->status == 'graded';
                // Warna Icon Background
                $iconBg = $isGraded ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400';
                @endphp

                <div class="gsap-card opacity-0 translate-y-8 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-lg transition-all duration-300 group">
                    <div class="flex flex-col md:flex-row gap-6">

                        {{-- 1. Status Icon (Left) --}}
                        <div class="shrink-0">
                            <div class="w-16 h-16 rounded-2xl {{ $iconBg }} flex items-center justify-center text-2xl shadow-sm">
                                @if($isGraded)
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @else
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                @endif
                            </div>
                        </div>

                        {{-- 2. Main Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-2">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 transition-colors">
                                        <a href="{{ route('workspace.show', $sub->task_id) }}">
                                            {{ $sub->task->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Modul: {{ $sub->task->module->title ?? 'Umum' }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Dikirim pada {{ $sub->updated_at->isoFormat('D MMMM Y, HH:mm') }}
                                    </p>
                                </div>

                                {{-- Score / Status Badge --}}
                                <div class="mt-2 md:mt-0 text-left md:text-right">
                                    @if($isGraded)
                                    <div class="flex flex-col items-start md:items-end">
                                        <span class="text-3xl font-extrabold text-green-600 dark:text-green-400 tracking-tight">{{ $sub->score }}</span>
                                        <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Nilai Akhir</span>
                                    </div>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-50 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-100 dark:border-orange-800">
                                        Sedang Diperiksa
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Feedback Section (If Exists) --}}
                            @if($sub->feedback)
                            <div class="mt-5 relative">
                                <div class="absolute -top-2 left-6 w-4 h-4 bg-gray-50 dark:bg-gray-700/50 transform rotate-45 border-t border-l border-gray-200 dark:border-gray-600"></div>
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4 border border-gray-100 dark:border-gray-700">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center shrink-0">
                                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-1">Komentar Guru</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 italic">"{{ $sub->feedback }}"</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Action Button --}}
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                                <a href="{{ route('workspace.show', $sub->task_id) }}" class="inline-flex items-center text-sm font-semibold text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors">
                                    Lihat Detail Tugas
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            {{-- Empty State --}}
            <div class="gsap-empty opacity-0 translate-y-4 flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Belum Ada Aktivitas</h3>
                <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-sm">Kamu belum mengumpulkan tugas apapun. Yuk, selesaikan misi pertamamu!</p>
                <a href="{{ route('student.tasks') }}" class="mt-6 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition-all transform hover:-translate-y-1">
                    Mulai Kerjakan Tugas
                </a>
            </div>
            @endif

        </div>
    </div>

    {{-- Script GSAP Animations --}}
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.registerPlugin(ScrollTrigger);

            // Animasi Header
            gsap.to(".gsap-header", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: "power3.out"
            });

            // Animasi Kartu (Stagger)
            if (document.querySelector('.gsap-card')) {
                gsap.to(".gsap-card", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.15,
                    delay: 0.2,
                    ease: "power3.out"
                });
            }

            // Animasi Empty State
            if (document.querySelector('.gsap-empty')) {
                gsap.to(".gsap-empty", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    delay: 0.2,
                    ease: "power3.out"
                });
            }
        });
    </script>

</x-student-layout>