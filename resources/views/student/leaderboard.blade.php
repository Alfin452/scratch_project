<x-student-layout>

    {{-- GSAP Scripts --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="text-center mb-12 gsap-header opacity-0 -translate-y-4">
                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">Papan Peringkat</h1>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Bersainglah secara sehat dan jadilah juara koding minggu ini!</p>
            </div>

            {{-- PODIUM SECTION (TOP 3) --}}
            @if($students->count() >= 3)
            <div class="flex justify-center items-end gap-4 md:gap-8 mb-16 relative z-10">

                {{-- Juara 2 --}}
                <div class="flex flex-col items-center gsap-podium opacity-0 translate-y-8" style="transition-delay: 0.2s">
                    <div class="relative mb-3">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-gray-300 dark:border-gray-600 overflow-hidden shadow-lg">
                            <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center font-bold text-2xl text-gray-500 dark:text-gray-300">
                                {{ substr($students[1]->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white dark:border-gray-800">
                            #2
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-700 dark:text-gray-200 text-center max-w-[80px] truncate">{{ $students[1]->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold mb-2">{{ $students[1]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-20 md:w-24 h-24 md:h-32 bg-gradient-to-t from-gray-300 to-gray-200 dark:from-gray-700 dark:to-gray-600 rounded-t-xl flex items-end justify-center pb-4 shadow-inner">
                        <span class="text-4xl font-black text-gray-400/50 dark:text-gray-800/50">2</span>
                    </div>
                </div>

                {{-- Juara 1 --}}
                <div class="flex flex-col items-center z-10 -mb-2 gsap-podium opacity-0 translate-y-8">
                    <div class="relative mb-3">
                        <span class="absolute -top-8 left-1/2 transform -translate-x-1/2 text-4xl animate-bounce">👑</span>
                        <div class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-yellow-400 overflow-hidden shadow-xl shadow-yellow-400/20">
                            <div class="w-full h-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center font-bold text-3xl text-yellow-600 dark:text-yellow-400">
                                {{ substr($students[0]->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-yellow-500 text-white text-xs font-bold px-3 py-0.5 rounded-full border-2 border-white dark:border-gray-800">
                            #1
                        </div>
                    </div>
                    <div class="text-base font-bold text-gray-900 dark:text-white text-center max-w-[100px] truncate">{{ $students[0]->name }}</div>
                    <div class="text-sm text-yellow-600 dark:text-yellow-400 font-extrabold mb-2">{{ $students[0]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-24 md:w-28 h-32 md:h-40 bg-gradient-to-t from-yellow-400 to-yellow-300 rounded-t-xl flex items-end justify-center pb-4 shadow-lg shadow-yellow-400/20">
                        <span class="text-5xl font-black text-white/50">1</span>
                    </div>
                </div>

                {{-- Juara 3 --}}
                <div class="flex flex-col items-center gsap-podium opacity-0 translate-y-8" style="transition-delay: 0.4s">
                    <div class="relative mb-3">
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-4 border-orange-300 dark:border-orange-700 overflow-hidden shadow-lg">
                            <div class="w-full h-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center font-bold text-2xl text-orange-500 dark:text-orange-400">
                                {{ substr($students[2]->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full border-2 border-white dark:border-gray-800">
                            #3
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-700 dark:text-gray-200 text-center max-w-[80px] truncate">{{ $students[2]->name }}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-bold mb-2">{{ $students[2]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-20 md:w-24 h-16 md:h-24 bg-gradient-to-t from-orange-300 to-orange-200 dark:from-orange-700 dark:to-orange-600 rounded-t-xl flex items-end justify-center pb-4 shadow-inner">
                        <span class="text-4xl font-black text-orange-100/50 dark:text-orange-900/50">3</span>
                    </div>
                </div>

            </div>
            @endif

            {{-- LEADERBOARD TABLE --}}
            <div class="gsap-table opacity-0 translate-y-8 bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Peringkat</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-right">Total XP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($students as $index => $student)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors {{ Auth::id() == $student->id ? 'bg-indigo-50/60 dark:bg-indigo-900/20 border-l-4 border-indigo-500' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($index == 0)
                                    <span class="text-xl">🥇</span>
                                    @elseif($index == 1)
                                    <span class="text-xl">🥈</span>
                                    @elseif($index == 2)
                                    <span class="text-xl">🥉</span>
                                    @else
                                    <span class="font-bold text-gray-500 dark:text-gray-400 w-6 inline-block text-center text-sm">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-gray-600 dark:text-gray-300 font-bold mr-4 text-sm shadow-sm border border-gray-200 dark:border-gray-600">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white {{ Auth::id() == $student->id ? 'text-indigo-600 dark:text-indigo-400' : '' }}">
                                                {{ $student->name }}
                                                @if(Auth::id() == $student->id)
                                                <span class="ml-2 text-[10px] bg-indigo-100 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300 px-2 py-0.5 rounded-full font-bold uppercase tracking-wide">Kamu</span>
                                                @endif
                                            </span>
                                            <span class="text-xs text-gray-400 dark:text-gray-500">Siswa Level 1</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($student->submissions_sum_score ?? 0) }} XP</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

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

            // Animasi Podium (Berurutan)
            if (document.querySelector('.gsap-podium')) {
                gsap.to(".gsap-podium", {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    stagger: 0.2, // Jeda antar juara
                    ease: "back.out(1.5)"
                });
            }

            // Animasi Tabel
            gsap.to(".gsap-table", {
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay: 0.5,
                ease: "power3.out"
            });
        });
    </script>

</x-student-layout>