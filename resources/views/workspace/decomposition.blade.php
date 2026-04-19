<!DOCTYPE html>
<html lang="id" x-data="decompositionWorkspace()" x-init="init()" :class="{'dark': darkMode}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $task->title }} – Workspace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }
        .animate-fadeIn { animation: fadeIn 0.4s ease-out forwards; }
        .animate-scaleIn { animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.9); } to { opacity: 1; transform: scale(1); } }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased min-h-screen flex flex-col transition-colors duration-300">
    {{-- Header --}}
    <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ $task->module_id ? route('modules.show', $task->module_id) : route('student.tasks') }}" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-sm font-bold text-gray-900 dark:text-white">{{ $task->title }}</h1>
                    <p class="text-xs text-sky-600 dark:text-sky-400 font-semibold tracking-wide uppercase">Pecah Masalah</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button @click="darkMode = !darkMode" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                    <span x-show="!darkMode"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg></span>
                    <span x-show="darkMode" x-cloak><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-x-hidden">
    @php
        $isSubmitted = isset($submission) && $submission;
        $answerData = $isSubmitted ? (is_array($submission->answer_data) ? $submission->answer_data : json_decode($submission->answer_data, true)) : null;
        $content = $task->content;
        $activities = $content['activities'] ?? [];
        $mainDescription = $content['main_description'] ?? 'Masalah Utama';
        $minDecomposition = $content['min_decomposition'] ?? 3;
    @endphp

    <div class="max-w-6xl mx-auto px-4 py-8" x-cloak>
        
        {{-- Progress Header --}}
        <div class="mb-10 text-center max-w-2xl mx-auto gsap-header">
            <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">{{ $task->title }}</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-6">{{ $task->instruction }}</p>
            
            <div class="relative pt-1 px-10" x-show="step > 1 && !submitted">
                <div class="flex mb-2 items-center justify-between text-xs font-bold uppercase tracking-widest">
                    <div class="text-sky-600 dark:text-sky-400">Langkah <span x-text="step"></span> dari 4</div>
                    <div class="text-gray-400" x-text="stepName()"></div>
                </div>
                <div class="overflow-hidden h-2 mb-4 text-xs flex rounded-full bg-gray-200 dark:bg-gray-700">
                    <div :style="`width: ${(step/4) * 100}%`" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-sky-500 transition-all duration-500 ease-out"></div>
                </div>
            </div>
        </div>

        {{-- === STEP 1: INTRO === --}}
        <div x-show="step === 1 && !submitted" class="gsap-step-1 flex flex-col items-center">
            <div class="w-full max-w-3xl bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xl overflow-hidden">
                <div class="p-10 text-center">
                    <div class="w-20 h-20 bg-sky-100 dark:bg-sky-900/40 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <span class="text-4xl">🎯</span>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-4">Masalah Utama</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-10 italic">
                        "{{ $mainDescription }}"
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8">
                        Bayangkan masalah besar di atas. Tugasmu adalah memecah masalah besar ini menjadi beberapa bagian kecil, lalu memilih satu bagian untuk dibuat algoritmanya.
                    </p>
                    <button @click="nextStep()" 
                        class="px-10 py-4 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl shadow-lg shadow-sky-600/20 transform hover:-translate-y-1 transition-all active:scale-95 gsap-btn-start">
                        Mulai Perencanaan
                    </button>
                </div>
            </div>
        </div>

        {{-- === STEP 2: DECOMPOSE (PICK) === --}}
        <div x-show="step === 2 && !submitted" class="gsap-step-2">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 gsap-sub-title">Pecah Masalah Menjadi Bagian Kecil</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm gsap-sub-desc">Klik tombol <span class="font-bold text-sky-600">Tambah</span> pada tugas yang menurutmu bagian dari masalah utama. Pilih minimal {{ $minDecomposition }} tugas.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {{-- Pool --}}
                <div class="space-y-4 gsap-pool">
                    <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-gray-400 mb-4 px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        Tugas Tersedia
                    </h3>
                    <div class="grid grid-cols-1 gap-3">
                        <template x-for="(act, idx) in availableActivities" :key="idx">
                            <div class="group bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 hover:border-sky-500 transition shadow-sm flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl" x-text="act.icon"></span>
                                    <span class="font-bold text-gray-700 dark:text-gray-300" x-text="act.name"></span>
                                </div>
                                <button @click="addToSelectedDecomp(act)" class="px-3 py-1.5 text-xs font-black bg-sky-50 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 rounded-lg border border-sky-100 dark:border-sky-800 hover:bg-sky-600 hover:text-white transition">Tambah</button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Selected --}}
                <div class="bg-sky-50/50 dark:bg-sky-900/10 rounded-3xl border-2 border-dashed border-sky-200 dark:border-sky-800 p-6 min-h-[400px] gsap-selected">
                    <h3 class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-sky-500 mb-6 px-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Rencana Acara Kelas
                    </h3>
                    
                    <div class="space-y-3" x-show="selectedDecomp.length > 0">
                        <template x-for="(act, idx) in selectedDecomp" :key="idx">
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-sky-200 dark:border-sky-800 shadow-md flex items-center justify-between animate-fadeIn">
                                <div class="flex items-center gap-4">
                                    <span class="text-2xl" x-text="act.icon"></span>
                                    <span class="font-bold text-gray-800 dark:text-gray-200" x-text="act.name"></span>
                                </div>
                                <button @click="removeSelectedDecomp(idx)" class="text-rose-400 hover:text-rose-600 px-2 py-1 text-xs font-bold uppercase tracking-wider">Hapus</button>
                            </div>
                        </template>
                    </div>

                    <div x-show="selectedDecomp.length === 0" class="flex flex-col items-center justify-center h-[300px] text-gray-400 text-center">
                        <div class="bg-gray-100 dark:bg-gray-800 w-16 h-16 rounded-2xl flex items-center justify-center mb-4 opacity-50">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium">Belum ada tugas dipilih.<br>Pilih minimal {{ $minDecomposition }} tugas.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-12 gap-4 gsap-actions-2">
                <button @click="backStep()" class="px-8 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-500 hover:bg-gray-100 transition">Kembali</button>
                <button @click="nextStep()" :disabled="selectedDecomp.length < {{ $minDecomposition }}"
                    class="px-10 py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl shadow-lg shadow-sky-600/20 disabled:opacity-30 disabled:cursor-not-allowed transition transform hover:-translate-y-1 active:scale-95">
                    Lanjut
                </button>
            </div>
        </div>

        {{-- === STEP 3: FOCUS (PICK ONE) === --}}
        <div x-show="step === 3 && !submitted" class="gsap-step-3">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 gsap-sub-title">Pilih Satu Bagian untuk Dibuat Algoritmanya</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm gsap-sub-desc">Dari tugas yang sudah kamu pilih, klik satu tugas yang ingin kamu susun langkah-langkahnya.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto gsap-focus-grid">
                <template x-for="(act, idx) in selectedDecomp" :key="idx">
                    <button @click="selectedFocus = act" 
                        class="group relative flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-3xl border-2 transition-all duration-300 transform hover:-translate-y-1 shadow-lg"
                        :class="selectedFocus === act ? 'border-sky-500 ring-4 ring-sky-500/10' : 'border-gray-100 dark:border-gray-700 hover:border-sky-200'">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 dark:bg-gray-700/50 flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform"
                             :class="selectedFocus === act ? 'bg-sky-50 dark:bg-sky-900/40 text-sky-600' : ''">
                            <span x-text="act.icon"></span>
                        </div>
                        <span class="font-black text-gray-900 dark:text-white text-center" x-text="act.name"></span>
                        
                        <div x-show="selectedFocus === act" class="absolute top-4 right-4 text-sky-500 animate-bounce">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        </div>
                    </button>
                </template>
            </div>

            <div class="flex justify-center mt-12 gap-4 gsap-actions-3">
                <button @click="backStep()" class="px-8 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-500 hover:bg-gray-100 transition">Kembali</button>
                <button @click="nextStep()" :disabled="!selectedFocus"
                    class="px-10 py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl shadow-lg shadow-sky-600/20 disabled:opacity-30 disabled:cursor-not-allowed transition transform hover:-translate-y-1 active:scale-95">
                    Lanjut ke Algoritma
                </button>
            </div>
        </div>

        {{-- === STEP 4: ALGORITHM (SOLVE) === --}}
        <div x-show="step === 4 && !submitted" class="gsap-step-4">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-black text-gray-900 dark:text-white mb-2 gsap-solve-title">Susun Algoritma</h2>
                <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
                    Susun langkah-langkah untuk <span class="px-2 py-0.5 bg-sky-100 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 rounded font-bold" x-text="`${selectedFocus?.icon} ${selectedFocus?.name}`"></span> agar menjadi algoritma yang benar.
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start mb-10">
                {{-- Steps Pool --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 p-6 shadow-xl gsap-solve-pool">
                    <h3 class="text-xs font-black uppercase tracking-widest text-sky-500 mb-6 flex items-center gap-2">
                        <span class="w-6 h-6 bg-sky-500 text-white rounded-lg flex items-center justify-center text-[10px]">1</span>
                        Langkah Acak
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(step, idx) in solverPool" :key="idx">
                            <div class="group p-4 bg-gray-50 dark:bg-gray-700/50 rounded-2xl border border-transparent hover:border-sky-300 hover:bg-white dark:hover:bg-gray-700 transition shadow-sm flex items-center justify-between cursor-default">
                                <span class="text-sm font-bold text-gray-800 dark:text-gray-200" x-text="step.text"></span>
                                <button @click="moveToAnswer(step, idx)" class="px-4 py-1.5 bg-white dark:bg-gray-800 text-[10px] font-black uppercase tracking-widest text-sky-600 dark:text-sky-400 border border-sky-100 dark:border-sky-700 rounded-lg hover:bg-sky-600 hover:text-white transition shadow-sm">Pilih</button>
                            </div>
                        </template>
                        <div x-show="solverPool.length === 0" class="py-10 text-center text-gray-300 text-xs font-bold uppercase tracking-widest">Semua langkah sudah dipilih</div>
                    </div>
                </div>

                {{-- Solution Space --}}
                <div class="bg-sky-600 dark:bg-sky-900/40 rounded-3xl p-6 shadow-2xl gsap-solve-answer min-h-[400px]">
                    <h3 class="text-xs font-black uppercase tracking-widest text-sky-200 mb-6 flex items-center gap-2">
                        <span class="w-6 h-6 bg-sky-200 text-sky-600 rounded-lg flex items-center justify-center text-[10px]">2</span>
                        Urutan Jawaban
                    </h3>
                    <div class="space-y-4">
                        <template x-for="(step, idx) in solverAnswer" :key="idx">
                            <div class="relative group bg-white dark:bg-gray-800 p-4 pl-12 rounded-2xl border border-white/20 shadow-xl animate-scaleIn">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 w-6 h-6 flex items-center justify-center rounded-full bg-sky-100 dark:bg-sky-900/40 text-sky-600 font-black text-xs" x-text="idx + 1"></span>
                                <p class="text-sm font-bold text-gray-800 dark:text-white" x-text="step.text"></p>
                                
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button @click="moveUp(idx)" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-sky-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/></svg></button>
                                    <button @click="moveDown(idx)" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 hover:text-sky-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg></button>
                                    <button @click="removeFromAnswer(step, idx)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-900/20 text-rose-400 hover:text-rose-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </div>
                        </template>

                        <div x-show="solverAnswer.length === 0" class="py-12 flex flex-col items-center justify-center text-sky-200/50 border-2 border-dashed border-sky-300/30 rounded-2xl">
                            <span class="text-xs font-bold uppercase tracking-widest">Klik langkah di sebelah kiri untuk mengisi urutan</span>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-between gap-4">
                        <button @click="resetSolver()" class="text-xs font-black text-sky-200 hover:text-white underline">Ulangi</button>
                        <button @click="checkAnswer()" :disabled="solverAnswer.length < totalSolverSteps"
                            class="px-6 py-2 bg-amber-400 hover:bg-amber-500 text-amber-900 font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-amber-400/20 transition disabled:opacity-30 disabled:cursor-not-allowed transform active:scale-95">
                            Cek Jawaban
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="checkResult !== null" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="mx-auto max-w-lg mb-8 p-5 rounded-2xl border-2 flex items-center gap-4 shadow-xl"
                :class="checkResult.correct ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 text-emerald-800 dark:text-emerald-400' : 'bg-rose-50 dark:bg-rose-900/20 border-rose-200 text-rose-800 dark:text-rose-400'">
                <span class="text-3xl" x-text="checkResult.correct ? '🎉' : '❌'"></span>
                <div>
                   <p class="font-black text-sm uppercase tracking-wider" x-text="checkResult.correct ? 'Mantap!' : 'Belum Tepat'"></p>
                   <p class="text-xs opacity-80" x-text="checkResult.message"></p>
                </div>
            </div>

            <div class="flex justify-center gap-4">
                <button @click="backStep()" class="px-8 py-3 rounded-2xl border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-500 hover:bg-gray-100 transition">Kembali</button>
                <button @click="submitActivity()" :disabled="solverAnswer.length < totalSolverSteps"
                    class="px-10 py-3 bg-sky-600 hover:bg-sky-700 text-white font-extrabold rounded-2xl shadow-lg shadow-sky-600/20 disabled:opacity-30 disabled:cursor-not-allowed transition transform hover:-translate-y-1 active:scale-95">
                    Kumpulkan Hasil
                </button>
            </div>
        </div>

        {{-- === SUBMITTED STATE === --}}
        <div x-show="submitted" class="gsap-submitted text-center py-20 animate-fadeIn">
            <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 rounded-[40px] shadow-2xl border border-gray-100 dark:border-gray-700 p-12">
                <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/40 rounded-full flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-4 tracking-tight">Jawaban Terkirim!</h2>
                <p class="text-gray-500 dark:text-gray-400 leading-relaxed mb-10">Kamu sudah berhasil memecah masalah dan menyusun algoritma dengan benar. Luar biasa!</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button @click="window.location.reload()" class="px-8 py-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-white font-black rounded-2xl transition active:scale-95">Main Lagi</button>
                    <a href="{{ $nextUrl }}" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-600/20 transition transform hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-2">
                        <span>Lanjut ke Aktivitas Berikutnya</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>
        </div>

    </main>

    <script>
        function decompositionWorkspace() {
            return {
                darkMode: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
                step: 1,
                submitted: {{ $isSubmitted ? 'true' : 'false' }},
                submitting: false,

                // Data
                activities: @json($activities),
                selectedDecomp: [], // Tahap 2
                selectedFocus: null, // Tahap 3
                
                // Solver (Tahap 4)
                solverPool: [],
                solverAnswer: [],
                totalSolverSteps: 0,
                checkResult: null,

                init() {
                    // Logic to load existing submission if needed
                    this.animateStep1();
                },

                get availableActivities() {
                    return this.activities.filter(a => !this.selectedDecomp.find(s => s.name === a.name));
                },

                stepName() {
                    switch(this.step) {
                        case 1: return 'Pembuka';
                        case 2: return 'Pecah Masalah';
                        case 3: return 'Pilih Fokus';
                        case 4: return 'Susun Algoritma';
                        default: return '';
                    }
                },

                nextStep() {
                    if (this.step < 4) {
                        this.step++;
                        this.$nextTick(() => {
                            if (this.step === 2) this.animateStep2();
                            if (this.step === 3) this.animateStep3();
                            if (this.step === 4) {
                                this.setupSolver();
                                this.animateStep4();
                            }
                        });
                    }
                },

                backStep() {
                    if (this.step > 1) {
                        this.step--;
                        this.$nextTick(() => {
                            if (this.step === 1) this.animateStep1();
                            if (this.step === 2) this.animateStep2();
                            if (this.step === 3) this.animateStep3();
                        });
                    }
                },

                // Tahap 2 Logic
                addToSelectedDecomp(act) {
                    this.selectedDecomp.push({...act});
                    this.selectedFocus = null;
                },

                removeSelectedDecomp(idx) {
                    this.selectedDecomp.splice(idx, 1);
                    this.selectedFocus = null;
                },

                // Tahap 4 Logic (Solver)
                setupSolver() {
                    if (!this.selectedFocus) return;
                    const steps = this.selectedFocus.steps.map((text, i) => ({ id: i, text }));
                    this.totalSolverSteps = steps.length;
                    
                    // Shuffle
                    this.solverPool = [...steps].sort(() => Math.random() - 0.5);
                    this.solverAnswer = [];
                    this.checkResult = null;
                },

                moveToAnswer(step, idx) {
                    this.solverPool.splice(idx, 1);
                    this.solverAnswer.push(step);
                    this.checkResult = null;
                },

                removeFromAnswer(step, idx) {
                    this.solverAnswer.splice(idx, 1);
                    this.solverPool.push(step);
                    this.checkResult = null;
                },

                moveUp(idx) {
                    if (idx <= 0) return;
                    [this.solverAnswer[idx], this.solverAnswer[idx-1]] = [this.solverAnswer[idx-1], this.solverAnswer[idx]];
                    this.solverAnswer = [...this.solverAnswer];
                    this.checkResult = null;
                },

                moveDown(idx) {
                    if (idx >= this.solverAnswer.length - 1) return;
                    [this.solverAnswer[idx], this.solverAnswer[idx+1]] = [this.solverAnswer[idx+1], this.solverAnswer[idx]];
                    this.solverAnswer = [...this.solverAnswer];
                    this.checkResult = null;
                },

                resetSolver() {
                    this.setupSolver();
                },

                checkAnswer() {
                    const correctOrder = this.selectedFocus.steps;
                    const userOrder = this.solverAnswer.map(s => s.text);
                    
                    let correct = userOrder.length === correctOrder.length;
                    if (correct) {
                        for (let i = 0; i < correctOrder.length; i++) {
                            if (userOrder[i] !== correctOrder[i]) { correct = false; break; }
                        }
                    }

                    this.checkResult = {
                        correct: correct,
                        message: correct 
                            ? 'Langkah-langkah yang kamu susun sudah tepat!' 
                            : 'Urutan langkahnya belum benar, mari teliti lagi.'
                    };

                    if (correct) {
                        confetti({ particleCount: 150, spread: 70, origin: { y: 0.6 }, colors: ['#0ea5e9', '#10b981', '#ffffff'] });
                    }
                },

                async submitActivity() {
                    if (this.submitting) return;
                    this.submitting = true;

                    const payload = {
                        decomposition: this.selectedDecomp.map(a => ({ name: a.name, icon: a.icon })),
                        focus: {
                            name: this.selectedFocus.name,
                            icon: this.selectedFocus.icon,
                            algorithm: this.solverAnswer.map(s => s.text)
                        }
                    };

                    try {
                        const res = await fetch(`{{ route('workspace.submit', $task->id) }}`, {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ answer_data: payload })
                        });

                        if (res.ok) {
                            this.submitted = true;
                            confetti({ particleCount: 200, spread: 100, origin: { y: 0.6 } });
                            this.$nextTick(() => this.animateSubmitted());
                        } else {
                            const err = await res.json();
                            alert(err.message || 'Gagal mengumpulkan.');
                        }
                    } catch (e) {
                        alert('Terjadi kesalahan koneksi.');
                    } finally {
                        this.submitting = false;
                    }
                },

                // Animations
                animateStep1() {
                    gsap.fromTo('.gsap-step-1', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out' });
                },
                animateStep2() {
                    gsap.fromTo('.gsap-sub-title', { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.5 });
                    gsap.fromTo('.gsap-sub-desc', { opacity: 0, x: -20 }, { opacity: 1, x: 0, duration: 0.5, delay: 0.1 });
                    gsap.fromTo('.gsap-pool', { opacity: 0, scale: 0.95 }, { opacity: 1, scale: 1, duration: 0.6, delay: 0.2, ease: 'back.out(1.4)' });
                    gsap.fromTo('.gsap-selected', { opacity: 0, scale: 0.95 }, { opacity: 1, scale: 1, duration: 0.6, delay: 0.3, ease: 'back.out(1.4)' });
                },
                animateStep3() {
                    gsap.fromTo('.gsap-focus-grid button', { opacity: 0, y: 20 }, { opacity: 1, y: 0, duration: 0.5, stagger: 0.1, ease: 'back.out(1.4)' });
                },
                animateStep4() {
                    gsap.fromTo('.gsap-solve-title', { opacity: 0, y: -20 }, { opacity: 1, y: 0, duration: 0.5 });
                    gsap.fromTo('.gsap-solve-pool', { opacity: 0, x: -30 }, { opacity: 1, x: 0, duration: 0.6, delay: 0.2 });
                    gsap.fromTo('.gsap-solve-answer', { opacity: 0, x: 30 }, { opacity: 1, x: 0, duration: 0.6, delay: 0.2 });
                },
                animateSubmitted() {
                    gsap.fromTo('.gsap-submitted', { opacity: 0, scale: 0.8 }, { opacity: 1, scale: 1, duration: 0.8, ease: 'back.out(1.4)' });
                }
            };
        }
    </script>
</body>
</html>
