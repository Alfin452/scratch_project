<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->module->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="classificationGame({{ json_encode($task->content) }}, '{{ route('workspace.submit', $task->id) }}', '{{ csrf_token() }}')">
            
            {{-- Header Tugas --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-10 mb-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-fuchsia-50 dark:bg-fuchsia-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                
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
                    <div class="font-semibold text-gray-600 dark:text-gray-300">
                        Soal <span x-text="currentIndex + 1" class="text-fuchsia-600 dark:text-fuchsia-400 font-bold"></span> dari <span x-text="totalQuestions"></span>
                    </div>
                    <div class="font-semibold text-gray-600 dark:text-gray-300">
                        Skor Sementara: <span x-text="score" class="text-emerald-600 dark:text-emerald-400 font-bold text-lg"></span>
                    </div>
                </div>

                <div class="p-8 md:p-10">
                    <template x-if="currentQuestion">
                        <div>
                            {{-- Indikator Kegiatan --}}
                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 text-xs font-bold tracking-wide mb-4">
                                Kegiatan <span x-text="currentIndex + 1" class="ml-1"></span>
                            </div>

                            {{-- Pertanyaan --}}
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                                <span class="block text-sm text-gray-500 dark:text-gray-400 mb-2">Pernyataan:</span>
                                <span x-text="currentQuestion.text" class="text-2xl"></span>
                            </h3>

                            {{-- Tombol Pilihan --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8" x-show="!isAnswered">
                                <template x-for="(cat, idx) in categories" :key="idx">
                                    <button @click="selectAnswer(cat)"
                                        class="p-6 rounded-2xl border-2 text-lg font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg focus:outline-none flex items-center justify-center gap-3"
                                        :class="{
                                            'border-emerald-200 bg-emerald-50 text-emerald-700 hover:border-emerald-400 dark:bg-emerald-900/20 dark:border-emerald-800 dark:text-emerald-400 dark:hover:border-emerald-600': idx === 0,
                                            'border-rose-200 bg-rose-50 text-rose-700 hover:border-rose-400 dark:bg-rose-900/20 dark:border-rose-800 dark:text-rose-400 dark:hover:border-rose-600': idx === 1
                                        }">
                                        <svg x-show="idx === 0" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        <svg x-show="idx === 1" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        <span x-text="cat"></span>
                                    </button>
                                </template>
                            </div>

                            {{-- Hasil / Feedback --}}
                            <div x-show="isAnswered" x-transition.opacity class="mb-8 p-6 rounded-2xl border-2"
                                :class="isCorrect ? 'bg-emerald-50 border-emerald-200 dark:bg-emerald-900/20 dark:border-emerald-800' : 'bg-rose-50 border-rose-200 dark:bg-rose-900/20 dark:border-rose-800'">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 flex items-center justify-center rounded-full shrink-0"
                                        :class="isCorrect ? 'bg-emerald-100 text-emerald-600 dark:bg-emerald-800/50 dark:text-emerald-400' : 'bg-rose-100 text-rose-600 dark:bg-rose-800/50 dark:text-rose-400'">
                                        <svg x-show="isCorrect" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        <svg x-show="!isCorrect" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-lg mb-1" 
                                            :class="isCorrect ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400'"
                                            x-text="isCorrect ? 'Tepat!' : 'Belum Tepat.'"></h4>
                                        
                                        <p x-show="!isCorrect" class="text-sm font-semibold text-rose-600 dark:text-rose-400 mb-2">
                                            Jawaban yang benar adalah <span class="font-extrabold" x-text="currentQuestion.answer"></span>.
                                        </p>
                                        
                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed" 
                                            :class="isCorrect ? 'text-emerald-800 dark:text-emerald-300' : 'text-rose-800 dark:text-rose-300'"
                                            x-text="currentQuestion.explanation"></p>
                                    </div>
                                </div>
                            </div>

                            {{-- Tombol Navigasi Bawah --}}
                            <div class="flex items-center justify-between pt-6 border-t border-gray-100 dark:border-gray-700">
                                <button type="button" @click="resetKuis()" x-show="isAnswered && !isSubmitting"
                                    class="text-sm font-bold text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition">
                                    Ulang dari Awal
                                </button>
                                <div x-show="!isAnswered"></div> {{-- Spacer --}}

                                <button type="button" @click="nextQuestion()" x-show="isAnswered && !isSubmitting"
                                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition transform hover:-translate-y-0.5 flex items-center">
                                    <span x-text="currentIndex < totalQuestions - 1 ? 'Soal Berikutnya' : 'Selesai & Lihat Hasil'"></span>
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </button>

                                <div x-show="isSubmitting" class="px-8 py-3 bg-blue-400 text-white font-bold rounded-xl flex items-center cursor-wait">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Menyimpan Hasil...
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>

    <script>
        function classificationGame(content, submitUrl, csrfToken) {
            return {
                categories: content?.categories || ['Kategori 1', 'Kategori 2'],
                questions: content?.questions || [],
                totalQuestions: (content?.questions || []).length,
                
                currentIndex: 0,
                currentQuestion: null,
                score: 0,
                
                isAnswered: false,
                isCorrect: false,
                selectedAnswer: null,
                answersList: [], // Simpan jawaban siswa untuk disubmit

                isSubmitting: false,

                init() {
                    if (this.totalQuestions > 0) {
                        this.currentQuestion = this.questions[this.currentIndex];
                    }
                },

                selectAnswer(answer) {
                    if (this.isAnswered) return;
                    
                    this.selectedAnswer = answer;
                    this.isAnswered = true;
                    this.isCorrect = (answer === this.currentQuestion.answer);
                    
                    if (this.isCorrect) {
                        this.score++;
                    }

                    // Simpan jawaban untuk payload
                    this.answersList[this.currentIndex] = answer;
                },

                nextQuestion() {
                    if (this.currentIndex < this.totalQuestions - 1) {
                        this.currentIndex++;
                        this.currentQuestion = this.questions[this.currentIndex];
                        this.isAnswered = false;
                        this.isCorrect = false;
                        this.selectedAnswer = null;
                    } else {
                        this.submitResults();
                    }
                },

                resetKuis() {
                    if(!confirm('Apakah kamu yakin ingin mengulang dari awal? Skor saat ini akan hangus.')) return;
                    this.currentIndex = 0;
                    this.score = 0;
                    this.answersList = [];
                    this.currentQuestion = this.questions[0];
                    this.isAnswered = false;
                    this.isCorrect = false;
                    this.selectedAnswer = null;
                },

                submitResults() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            answer_data: this.answersList
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
