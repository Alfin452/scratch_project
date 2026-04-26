<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->module->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-10 mb-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                
                <div class="text-center relative z-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400 rounded-full mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">Hasil Latihan</h1>
                    <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                        Skor akhir: <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ $submission->score }}</span> dari 100
                    </p>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                @php
                    $questions = $task->content['questions'] ?? [];
                    $answers = $submission->answer_data ?? [];
                @endphp

                @foreach($questions as $index => $q)
                    @php
                        $userAnswer = $answers[$index] ?? '-';
                        $correctAnswer = $q['answer'];
                        $isCorrect = $userAnswer === $correctAnswer;
                    @endphp

                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border-l-4 shadow-sm
                        {{ $isCorrect ? 'border-emerald-500' : 'border-rose-500' }}">
                        <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-4">
                            {{ $index + 1 }}. {{ $q['text'] }}
                        </h4>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-4">
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 block mb-1">Jawabanmu:</span>
                                <span class="font-bold {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $userAnswer }}
                                </span>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-xl border border-gray-100 dark:border-gray-600">
                                <span class="text-gray-500 dark:text-gray-400 block mb-1">Jawaban Benar:</span>
                                <span class="font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $correctAnswer }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800">
                            <span class="font-semibold text-blue-800 dark:text-blue-300">Alasan:</span>
                            <span class="text-blue-700 dark:text-blue-200 ml-1">{{ $q['explanation'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Navigasi Lanjut --}}
            <div class="flex justify-center mb-12">
                @if ($nextUrl && !$isCourseCompleted)
                    <a href="{{ $nextUrl }}"
                       class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-bold rounded-2xl shadow-xl shadow-blue-500/30 transition transform hover:-translate-y-1 flex items-center gap-3 group">
                        Berikutnya
                        <svg class="w-6 h-6 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white text-lg font-bold rounded-2xl shadow-xl shadow-emerald-500/30 transition transform hover:-translate-y-1 flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Kembali ke Dashboard
                    </a>
                @endif
            </div>

            {{-- FULLSCREEN CELEBRATION OVERLAY --}}
            <div x-data="{ showCelebration: {{ $isCourseCompleted ? 'true' : 'false' }} }">
                <div x-show="showCelebration" x-transition.opacity.duration.500ms
                    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/90 backdrop-blur-sm"
                    style="display: none;">
                    <div class="text-center relative max-w-2xl mx-auto px-6" @click.away="showCelebration = false">
                        
                        {{-- Confetti effect --}}
                        <div class="absolute inset-0 overflow-hidden pointer-events-none">
                            <div class="absolute top-10 left-10 w-4 h-4 rounded-full bg-pink-500 animate-ping"></div>
                            <div class="absolute top-20 right-20 w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></div>
                            <div class="absolute bottom-20 left-1/4 w-5 h-5 rounded-full bg-blue-500 animate-bounce"></div>
                            <div class="absolute top-1/3 right-1/3 w-4 h-4 rounded-full bg-yellow-400 animate-pulse"></div>
                        </div>

                        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 p-12 rounded-[3rem] shadow-2xl border-4 border-white/20 transform transition-all scale-100 relative overflow-hidden">
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-pink-500/20 rounded-full blur-xl"></div>

                            <div class="text-8xl mb-6">🎉</div>
                            <h2 class="text-4xl md:text-5xl font-black text-white mb-4 leading-tight">Selamat!</h2>
                            <p class="text-xl text-indigo-100 mb-8 font-medium">Kamu telah menyelesaikan <span class="text-white font-bold underline decoration-pink-400 decoration-4">semua modul dan tugas</span> yang ada di platform ini. Kamu luar biasa!</p>
                            
                            <button @click="showCelebration = false" class="px-8 py-4 bg-white text-indigo-700 font-black rounded-2xl shadow-xl hover:bg-indigo-50 hover:scale-105 transition-all w-full md:w-auto">
                                Tutup Ucapan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-student-layout>
