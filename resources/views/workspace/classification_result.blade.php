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

            <div class="flex justify-center">
                <a href="{{ $nextUrl }}" class="px-8 py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center">
                    Lanjut Belajar
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>

        </div>
    </div>
</x-student-layout>
