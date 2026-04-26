<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->module->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="multipleChoiceGame({{ json_encode($task->content) }}, '{{ route('workspace.submit', $task->id) }}', '{{ csrf_token() }}')">
            
            {{-- Header Tugas --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-10 mb-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-pink-50 dark:bg-pink-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-4 relative z-10">
                    {{ $task->title }}
                </h1>
                <p class="text-lg text-gray-500 dark:text-gray-400 relative z-10">
                    {{ $task->instruction }}
                </p>
            </div>

            {{-- Game Area --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-6">
                
                {{-- Status Bar --}}
                <div class="flex items-center justify-between px-8 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <div class="font-semibold text-gray-600 dark:text-gray-300 flex items-center gap-2">
                        <span>Soal</span> 
                        <span x-text="currentIndex + 1" class="text-pink-600 dark:text-pink-400 font-bold bg-pink-100 dark:bg-pink-900/40 px-2 py-0.5 rounded-md"></span> 
                        <span>dari <span x-text="totalQuestions"></span></span>
                    </div>
                    
                    {{-- Progress Bar Mini --}}
                    <div class="hidden sm:flex flex-grow max-w-xs mx-6 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-pink-500 transition-all duration-300" :style="'width: ' + ((currentIndex) / totalQuestions * 100) + '%'"></div>
                    </div>

                    <div class="font-semibold text-gray-600 dark:text-gray-300">
                        Terjawab: <span x-text="Object.keys(answers).length" class="text-emerald-600 dark:text-emerald-400 font-bold"></span>
                    </div>
                </div>

                <div class="p-8 md:p-10 min-h-[400px] flex flex-col">
                    <template x-if="currentQuestion">
                        <div class="flex-grow flex flex-col">
                            {{-- Pertanyaan --}}
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white mb-8 leading-relaxed">
                                <span x-text="currentQuestion.text"></span>
                            </h3>

                            {{-- Tombol Pilihan --}}
                            <div class="space-y-3 mb-8 flex-grow">
                                <template x-for="(opt, idx) in currentQuestion.options" :key="idx">
                                    <button @click="selectAnswer(idx)"
                                        class="w-full p-5 rounded-2xl border-2 text-left font-semibold transition-all duration-200 focus:outline-none flex items-center gap-4 group"
                                        :class="answers[currentIndex] === idx 
                                            ? 'border-pink-500 bg-pink-50 text-pink-700 dark:bg-pink-900/30 dark:border-pink-500 dark:text-pink-300 shadow-md transform scale-[1.01]' 
                                            : 'border-gray-200 bg-white text-gray-700 hover:border-pink-300 hover:bg-pink-50/50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-pink-800 dark:hover:bg-pink-900/10'">
                                        
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-lg shrink-0 transition-colors"
                                            :class="answers[currentIndex] === idx ? 'bg-pink-500 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-pink-100 group-hover:text-pink-600 dark:bg-gray-700 dark:text-gray-400'">
                                            <span x-text="String.fromCharCode(65 + idx)"></span>
                                        </div>
                                        <span class="text-lg" x-text="opt"></span>
                                    </button>
                                </template>
                            </div>

                            {{-- Tombol Navigasi Bawah --}}
                            <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700 mt-auto">
                                <button type="button" @click="prevQuestion()" :disabled="currentIndex === 0"
                                    class="px-6 py-2.5 font-bold rounded-xl transition flex items-center text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 disabled:opacity-30 disabled:cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    Sebelumnya
                                </button>
                                
                                <button type="button" @click="nextQuestion()" x-show="currentIndex < totalQuestions - 1"
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center">
                                    Selanjutnya
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>

                                <button type="button" @click="submitResults()" x-show="currentIndex === totalQuestions - 1" :disabled="isSubmitting || Object.keys(answers).length < totalQuestions"
                                    class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:shadow-none">
                                    <span x-text="isSubmitting ? 'Menyimpan...' : 'Selesaikan & Lihat Hasil'"></span>
                                    <svg x-show="!isSubmitting" class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <svg x-show="isSubmitting" class="animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </button>
                            </div>
                            
                            <div x-show="currentIndex === totalQuestions - 1 && Object.keys(answers).length < totalQuestions" class="text-right mt-2 text-xs text-rose-500 font-semibold animate-pulse">
                                Harap jawab semua soal sebelum menyelesaikan.
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Grid Navigasi Soal Mini --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h4 class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-4 uppercase tracking-wider">Navigasi Soal</h4>
                <div class="flex flex-wrap gap-2">
                    <template x-for="i in totalQuestions" :key="i">
                        <button @click="jumpToQuestion(i - 1)"
                            class="w-10 h-10 rounded-xl font-bold text-sm flex items-center justify-center transition-colors border-2"
                            :class="{
                                'border-pink-500 bg-pink-500 text-white shadow-md': currentIndex === i - 1,
                                'border-emerald-500 bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400': currentIndex !== i - 1 && answers[i - 1] !== undefined,
                                'border-gray-200 bg-gray-50 text-gray-500 hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400': currentIndex !== i - 1 && answers[i - 1] === undefined
                            }">
                            <span x-text="i"></span>
                        </button>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <script>
        function multipleChoiceGame(content, submitUrl, csrfToken) {
            return {
                questions: content?.questions || [],
                totalQuestions: (content?.questions || []).length,
                
                currentIndex: 0,
                currentQuestion: null,
                
                answers: {}, // Simpan jawaban siswa (key: question index, value: option index)

                isSubmitting: false,

                init() {
                    if (this.totalQuestions > 0) {
                        this.currentQuestion = this.questions[0];
                    }
                },

                selectAnswer(optIndex) {
                    this.answers[this.currentIndex] = optIndex;
                    
                    // Auto-next delay effect
                    if (this.currentIndex < this.totalQuestions - 1) {
                        setTimeout(() => {
                            this.nextQuestion();
                        }, 400);
                    }
                },

                nextQuestion() {
                    if (this.currentIndex < this.totalQuestions - 1) {
                        this.currentIndex++;
                        this.currentQuestion = this.questions[this.currentIndex];
                    }
                },
                
                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        this.currentQuestion = this.questions[this.currentIndex];
                    }
                },

                jumpToQuestion(index) {
                    if(index >= 0 && index < this.totalQuestions) {
                        this.currentIndex = index;
                        this.currentQuestion = this.questions[this.currentIndex];
                    }
                },

                submitResults() {
                    if (this.isSubmitting) return;
                    if (Object.keys(this.answers).length < this.totalQuestions) {
                        alert('Harap jawab semua soal terlebih dahulu!');
                        return;
                    }
                    
                    this.isSubmitting = true;

                    fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            answer_data: this.answers
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menyimpan jawaban.');
                        this.isSubmitting = false;
                    });
                }
            };
        }
    </script>
</x-student-layout>
