<!DOCTYPE html>
<html lang="id" x-data="dragDropWorkspace()" x-init="init()" :class="{'dark': darkMode}">
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
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'Inter', sans-serif; }

        /* Activity Cards */
        .activity-card {
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .activity-card:hover:not(.disabled) {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15);
        }
        .activity-card.selected {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px -8px rgba(0,0,0,0.2);
        }
        .activity-card.disabled {
            opacity: 0.4;
            cursor: not-allowed;
            filter: grayscale(0.5);
        }

        /* Step Items */
        .step-item {
            user-select: none;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .step-item:hover {
            transform: translateX(4px);
        }
        .step-item.dragging {
            opacity: 0.3;
            transform: scale(0.95);
        }

        /* Drop Zones */
        .drop-zone { transition: all 0.3s ease; }
        .drop-zone.drag-over {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-color: #10b981;
            box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.08);
        }
        .dark .drop-zone.drag-over {
            background: rgba(16, 185, 129, 0.08);
            border-color: #10b981;
        }

        /* Gradient backgrounds */
        .grad-1 { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .grad-2 { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .grad-3 { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .grad-4 { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
        .grad-5 { background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); }
        .grad-6 { background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%); }
        .grad-7 { background: linear-gradient(135deg, #fccb90 0%, #d57eeb 100%); }
        .grad-8 { background: linear-gradient(135deg, #e0c3fc 0%, #8ec5fc 100%); }

        /* Confetti keyframe */
        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0; }
        }
        .confetti-piece {
            position: fixed;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 2px;
            animation: confetti-fall 3s ease-out forwards;
            pointer-events: none;
            z-index: 100;
        }

        /* Progress bar animation */
        .progress-fill {
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Number badge bounce */
        @keyframes badge-pop {
            0% { transform: scale(0); }
            80% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .badge-pop { animation: badge-pop 0.4s cubic-bezier(0.4, 0, 0.2, 1); }

        .dark body { background: #0f172a; }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 min-h-screen text-gray-800 dark:text-gray-200">

{{-- ================================================================ --}}
{{-- HEADER --}}
{{-- ================================================================ --}}
<header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200/60 dark:border-gray-800 sticky top-0 z-40">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-3">
        <div class="flex items-center gap-3">
            {{-- Back --}}
            @php
            $backRoute = $task->module_id
                ? route('modules.show', $task->module_id)
                : (Auth::user()->isTeacher() ? route('independent-tasks.index') : route('student.tasks'));
            @endphp
            <a href="{{ $backRoute }}" class="flex items-center justify-center w-9 h-9 rounded-xl text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 dark:hover:text-indigo-400 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>

            {{-- Title --}}
            <div class="flex-1 min-w-0">
                <h1 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white truncate leading-tight">{{ $task->title }}</h1>
                <p class="text-xs text-gray-400 dark:text-gray-500 truncate">{{ $task->instruction }}</p>
            </div>

            {{-- Step Progress --}}
            <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                <template x-for="(label, i) in stepLabels" :key="i">
                    <div class="flex items-center gap-1.5">
                        <div class="flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold transition-all duration-500"
                            :class="{
                                'bg-indigo-600 text-white shadow-md shadow-indigo-600/30': step === i+1,
                                'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400': step > i+1,
                                'bg-gray-100 dark:bg-gray-800 text-gray-400': step < i+1
                            }">
                            <span x-show="step > i+1" class="text-xs">✓</span>
                            <span x-show="step <= i+1" x-text="i+1" class="text-xs"></span>
                            <span x-text="label" class="hidden lg:inline"></span>
                        </div>
                        <svg x-show="i < stepLabels.length - 1" class="w-3 h-3 text-gray-300 dark:text-gray-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </template>
            </div>
        </div>

        {{-- Mobile Progress Bar --}}
        <div class="sm:hidden mt-2">
            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-1">
                <span x-text="stepLabels[step-1]" class="font-semibold"></span>
                <span x-text="`Langkah ${step} dari ${stepLabels.length}`"></span>
            </div>
            <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-800 rounded-full overflow-hidden">
                <div class="progress-fill h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" :style="`width: ${(step / stepLabels.length) * 100}%`"></div>
            </div>
        </div>
    </div>
</header>

{{-- ================================================================ --}}
{{-- MAIN --}}
{{-- ================================================================ --}}
<main class="max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-10">

    {{-- ==== STEP 1: PILIH KEGIATAN ==== --}}
    <div x-show="step === 1" x-cloak>
        <div class="gsap-step1">
            {{-- Title Card --}}
            <div class="text-center mb-8 gsap-title">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-xs font-bold mb-4">
                    <span>Langkah 1: Pilih <span x-text="pickCount"></span> kegiatan</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mb-2">Pilih Kegiatan</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">Klik kartu kegiatan yang ingin kamu kerjakan</p>
            </div>

            {{-- Counter --}}
            <div class="flex justify-center mb-6 gsap-counter">
                <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-white dark:bg-gray-800 rounded-2xl shadow-lg shadow-gray-200/50 dark:shadow-black/20 border border-gray-100 dark:border-gray-700">
                    <span class="text-sm font-semibold text-gray-500 dark:text-gray-400">Dipilih:</span>
                    <div class="flex items-center gap-1">
                        <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400" x-text="selectedActivities.length"></span>
                        <span class="text-gray-400 dark:text-gray-500 font-bold">/</span>
                        <span class="text-2xl font-black text-gray-300 dark:text-gray-600" x-text="pickCount"></span>
                    </div>
                </div>
            </div>

            {{-- Activity Grid --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5 mb-8">
                <template x-for="(activity, idx) in activities" :key="idx">
                    <div @click="toggleActivity(idx)"
                        class="activity-card gsap-card flex flex-col items-center justify-center p-5 sm:p-6 rounded-2xl text-white font-bold text-sm text-center min-h-[120px] sm:min-h-[140px] shadow-lg select-none relative overflow-hidden"
                        :class="[
                            getGradientClass(idx),
                            isActivitySelected(idx) ? 'selected ring-4 ring-white/50 dark:ring-white/30' : '',
                            (selectedActivities.length >= pickCount && !isActivitySelected(idx)) ? 'disabled' : ''
                        ]">
                        {{-- Decorative circles --}}
                        <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                        <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-white/10 rounded-full"></div>

                        <span class="text-4xl sm:text-5xl mb-2 relative z-10 drop-shadow-sm" x-text="activity.icon"></span>
                        <span x-text="activity.name" class="relative z-10 text-xs sm:text-sm leading-tight drop-shadow-sm"></span>

                        {{-- Selected Checkmark --}}
                        <div x-show="isActivitySelected(idx)" x-transition
                            class="absolute top-2 right-2 w-6 h-6 bg-white/30 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Selected Preview --}}
            <div class="gsap-preview bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 p-5 mb-6 shadow-sm">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-3">Kegiatan yang dipilih:</p>
                <div x-show="selectedActivities.length === 0" class="text-sm text-gray-300 dark:text-gray-600 italic py-2">
                    Belum ada kegiatan dipilih. Klik kartu di atas!
                </div>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(aIdx, i) in selectedActivities" :key="aIdx">
                        <span class="gsap-chip inline-flex items-center gap-1.5 pl-2 pr-1 py-1.5 rounded-xl bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm">
                            <span x-text="activities[aIdx].icon"></span>
                            <span x-text="activities[aIdx].name"></span>
                            <button @click="toggleActivity(aIdx)" class="ml-1 w-5 h-5 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition text-xs">✕</button>
                        </span>
                    </template>
                </div>
            </div>

            {{-- Continue Button --}}
            <div class="flex justify-center gsap-btn">
                <button @click="goToSolve()"
                    :disabled="selectedActivities.length < pickCount"
                    class="group relative inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold shadow-xl shadow-indigo-600/25 hover:shadow-indigo-600/40 transition-all duration-300 disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none disabled:hover:bg-indigo-600 transform hover:-translate-y-0.5">
                    Lanjut
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ==== STEP 2: SUSUN LANGKAH ==== --}}
    <div x-show="step === 2" x-cloak>
        <template x-if="currentSolvingActivity !== null">
            <div class="gsap-step2">
                {{-- Sub-progress --}}
                <div class="flex items-center justify-between mb-5">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-full text-xs font-bold">
                        Mengerjakan kegiatan <span x-text="currentSubStep + 1"></span> dari <span x-text="selectedActivities.length"></span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-full text-xs font-bold">
                        Dipilih: <span x-text="selectedActivities.length"></span> dari <span x-text="pickCount"></span>
                    </div>
                </div>

                {{-- Activity Title --}}
                <div class="text-center mb-6 gsap-solve-title">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-3 shadow-lg"
                        :class="getGradientClass(selectedActivities[currentSubStep])">
                        <span class="text-3xl" x-text="currentSolvingActivity.icon"></span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white mb-1" x-text="currentSolvingActivity.name"></h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Susun langkah-langkah agar menjadi algoritma yang benar</p>
                </div>

                {{-- Two Column Layout --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

                    {{-- LEFT: Pool --}}
                    <div class="gsap-pool">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                                <span class="text-sm">🌀</span>
                            </div>
                            <h3 class="font-extrabold text-gray-700 dark:text-gray-300 text-sm">Langkah Acak</h3>
                            <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400" x-text="`${poolSteps.length} tersisa`"></span>
                        </div>
                        <div id="pool-container"
                            class="drop-zone min-h-[200px] space-y-2.5 p-4 rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 bg-white/50 dark:bg-gray-800/30 backdrop-blur-sm"
                            @dragover.prevent="onDragOver($event, 'pool')"
                            @dragleave="onDragLeave($event)"
                            @drop.prevent="onDrop($event, 'pool')">

                            <template x-if="poolSteps.length === 0">
                                <div class="flex flex-col items-center justify-center py-10 text-gray-300 dark:text-gray-600">
                                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-sm font-semibold">Semua langkah dipilih!</p>
                                </div>
                            </template>

                            <template x-for="(step, idx) in poolSteps" :key="step.id">
                                <div class="step-item flex items-center gap-3 p-3.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:border-indigo-300 dark:hover:border-indigo-600 cursor-grab active:cursor-grabbing"
                                    draggable="true"
                                    @dragstart="onDragStart($event, step, 'pool', idx)"
                                    @dragend="onDragEnd($event)">
                                    <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-700 flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1" x-text="step.text"></span>
                                    <button @click="moveToAnswer(step, idx)"
                                        class="flex-shrink-0 px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-sm hover:shadow-md transition-all transform hover:scale-105 active:scale-95">
                                        Pilih →
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- RIGHT: Answer --}}
                    <div class="gsap-answer">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="w-8 h-8 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <span class="text-sm">⭐</span>
                            </div>
                            <h3 class="font-extrabold text-gray-700 dark:text-gray-300 text-sm">Urutan Jawaban</h3>
                            <span class="ml-auto text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-800 text-gray-400" x-text="`${answerSteps.length} / ${totalStepsForCurrent}`"></span>
                        </div>
                        <div id="answer-container"
                            class="drop-zone min-h-[200px] space-y-2.5 p-4 rounded-2xl border-2 border-dashed border-emerald-200 dark:border-emerald-900/50 bg-emerald-50/30 dark:bg-emerald-900/10 backdrop-blur-sm"
                            @dragover.prevent="onDragOver($event, 'answer')"
                            @dragleave="onDragLeave($event)"
                            @drop.prevent="onDrop($event, 'answer')">

                            <template x-if="answerSteps.length === 0">
                                <div class="flex flex-col items-center justify-center py-10 text-gray-300 dark:text-gray-600">
                                    <svg class="w-10 h-10 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                    <p class="text-sm font-semibold">Pilih langkah dari kiri</p>
                                </div>
                            </template>

                            <template x-for="(step, idx) in answerSteps" :key="step.id">
                                <div class="step-item flex items-center gap-3 p-3.5 bg-white dark:bg-gray-800 rounded-xl border border-emerald-100 dark:border-emerald-800/50 shadow-sm hover:shadow-md cursor-grab active:cursor-grabbing"
                                    draggable="true"
                                    @dragstart="onDragStart($event, step, 'answer', idx)"
                                    @dragend="onDragEnd($event)">
                                    <span class="badge-pop flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xs font-black shadow-sm" x-text="idx + 1"></span>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1" x-text="step.text"></span>
                                    <div class="flex items-center gap-0.5 flex-shrink-0">
                                        <button @click="moveUp(idx)" x-show="idx > 0"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                        </button>
                                        <button @click="moveDown(idx)" x-show="idx < answerSteps.length - 1"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <button @click="removeFromAnswer(step, idx)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Result Banner --}}
                <div x-show="checkResult !== null" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    class="mx-auto max-w-md mb-6 p-4 rounded-2xl text-sm font-bold border flex items-center gap-3 shadow-sm"
                    :class="checkResult?.correct ? 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800' : 'bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-400 border-rose-100 dark:border-rose-800'">
                    <span class="text-xl" x-text="checkResult?.correct ? '🎉' : '❌'"></span>
                    <span x-text="checkResult?.message"></span>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 gsap-actions">
                    <button @click="backToSelect()"
                        class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                        ← Kembali
                    </button>
                    <div class="flex gap-3">
                        <button @click="resetCurrent()"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 transition-all">
                            Ulangi
                        </button>
                        <button @click="checkAnswer()"
                            :disabled="answerSteps.length < totalStepsForCurrent"
                            class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-sm font-extrabold shadow-lg shadow-amber-500/20 transition-all disabled:opacity-30 disabled:cursor-not-allowed transform hover:-translate-y-0.5">
                            Cek Jawaban
                        </button>
                        <button @click="finishCurrentActivity()"
                            :disabled="answerSteps.length < totalStepsForCurrent"
                            class="group relative inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/30 transition-all disabled:opacity-30 disabled:cursor-not-allowed disabled:shadow-none transform hover:-translate-y-0.5">
                            <span x-text="currentSubStep < selectedActivities.length - 1 ? 'Kegiatan Berikutnya' : 'Lihat Hasil'"></span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- ==== STEP 3: HASIL ==== --}}
    <div x-show="step === 3" x-cloak>
        <div class="gsap-step3">
            {{-- Title --}}
            <div class="text-center mb-8 gsap-result-title">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-xl shadow-indigo-600/25 mb-4">
                    <span class="text-4xl">📋</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-gray-900 dark:text-white mb-1">Hasil Aktivitas</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $task->title }}</p>
            </div>

            {{-- Result Cards --}}
            <div class="space-y-4 mb-8">
                <template x-for="(result, i) in finalResults" :key="i">
                    <div class="gsap-result-card bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 p-4 border-b border-gray-100 dark:border-gray-700/50">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl shadow-sm"
                                :class="getGradientClass(findActivityIndex(result.name))">
                                <span class="text-xl" x-text="result.icon"></span>
                            </div>
                            <h3 class="font-bold text-gray-800 dark:text-white text-sm" x-text="result.name"></h3>
                        </div>
                        <div class="p-4">
                            <ol class="space-y-2">
                                <template x-for="(step, j) in result.answer" :key="j">
                                    <li class="flex items-start gap-2.5 text-sm">
                                        <span class="flex-shrink-0 w-6 h-6 mt-0.5 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-xs font-black shadow-sm" x-text="j + 1"></span>
                                        <span class="text-gray-700 dark:text-gray-300 pt-0.5" x-text="step"></span>
                                    </li>
                                </template>
                            </ol>
                        </div>
                    </div>
                </template>
            </div>

            @if($submission && $submission->status === 'graded')
            {{-- Sudah Dinilai --}}
            <div class="gsap-grade mb-6 p-6 rounded-2xl bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-indigo-900/20 dark:via-purple-900/20 dark:to-pink-900/20 border border-indigo-200 dark:border-indigo-800 shadow-sm">
                <p class="text-xs font-black uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-3">Nilai dari Guru</p>
                <div class="flex items-center gap-4">
                    <div class="flex items-baseline gap-1">
                        <span class="text-5xl font-black text-indigo-600 dark:text-indigo-400">{{ $submission->score }}</span>
                        <span class="text-lg text-gray-400 font-bold">/100</span>
                    </div>
                    @if($submission->feedback)
                    <div class="flex-1 ml-3 p-4 bg-white/70 dark:bg-gray-800/70 backdrop-blur-sm rounded-xl border border-white/50 dark:border-gray-700">
                        <p class="text-xs font-bold text-gray-400 mb-1">Feedback Guru:</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">"{{ $submission->feedback }}"</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Submit Area --}}
            @php $isSubmitted = $submission && in_array($submission->status, ['submitted', 'graded']); @endphp

            <div x-show="!submitted && !{{ $isSubmitted ? 'true' : 'false' }}"
                class="gsap-submit flex flex-col sm:flex-row items-center gap-4 p-5 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">Kumpulkan Jawaban</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Guru akan mereview dan memberikan nilai pada jawabanmu</p>
                    </div>
                </div>
                <button @click="submitAnswers()" :disabled="submitting"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-extrabold shadow-lg shadow-indigo-600/20 transition-all disabled:opacity-50 transform hover:-translate-y-0.5">
                    <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span x-text="submitting ? 'Mengumpulkan...' : 'Kumpulkan'"></span>
                </button>
            </div>

            <div x-show="submitted || {{ $isSubmitted ? 'true' : 'false' }}"
                class="gsap-submitted flex flex-col sm:flex-row items-center gap-4 p-5 bg-emerald-50 dark:bg-emerald-900/15 rounded-2xl border border-emerald-200 dark:border-emerald-800 shadow-sm">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-10 h-10 flex items-center justify-center bg-emerald-200 dark:bg-emerald-900/40 rounded-xl flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">
                            {{ $isSubmitted && $submission?->status === 'graded' ? 'Jawaban sudah dinilai oleh guru!' : 'Jawaban berhasil dikumpulkan!' }}
                        </p>
                        <p class="text-xs text-emerald-600/80 dark:text-emerald-500/80 mt-0.5">
                            {{ $isSubmitted && $submission?->status === 'graded' ? 'Cek nilaimu di atas.' : 'Tunggu penilaian dari guru ya 😊' }}
                        </p>
                    </div>
                </div>

                <a href="{{ $nextUrl }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-extrabold shadow-lg shadow-emerald-600/20 transition-all transform hover:-translate-y-0.5">
                    <span>{{ $nextTask ? 'Tugas Berikutnya' : 'Selesai Modul' }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            {{-- Play Again --}}
            <div class="mt-6 flex justify-center gsap-play-again">
                <button @click="playAgain()"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Main Lagi
                </button>
            </div>
        </div>
    </div>

</main>

<script>
function dragDropWorkspace() {
    const taskContent = @json($task->content);
    const existingSubmission = @json($submission?->answer_data ?? null);
    const submitUrl = "{{ route('workspace.submit', $task->id) }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    return {
        step: 1,
        stepLabels: ['Pilih Kegiatan', 'Susun Langkah', 'Selesai'],
        darkMode: false,
        activities: [],
        pickCount: 2,
        selectedActivities: [],
        currentSubStep: 0,
        poolSteps: [],
        answerSteps: [],
        totalStepsForCurrent: 0,
        checkResult: null,
        finalResults: [],
        submitted: false,
        submitting: false,
        dragging: null,
        draggingFrom: null,

        init() {
            this.darkMode = document.documentElement.classList.contains('dark') || localStorage.getItem('color-theme') === 'dark';
            this.pickCount = taskContent.pick_count ?? 2;
            this.activities = (taskContent.activities ?? []).map(a => ({
                icon: a.icon, name: a.name, correctSteps: a.steps,
            }));

            if (existingSubmission && existingSubmission.length > 0) {
                this.finalResults = existingSubmission;
                this.selectedActivities = existingSubmission.map(r => this.activities.findIndex(a => a.name === r.name)).filter(i => i >= 0);
                this.step = 3;
                this.submitted = true;
            }

            this.$nextTick(() => this.animateStep1());
        },

        getGradientClass(idx) {
            return `grad-${(idx % 8) + 1}`;
        },

        isActivitySelected(idx) { return this.selectedActivities.includes(idx); },

        toggleActivity(idx) {
            if (this.isActivitySelected(idx)) {
                this.selectedActivities = this.selectedActivities.filter(i => i !== idx);
            } else if (this.selectedActivities.length < this.pickCount) {
                this.selectedActivities.push(idx);
            }
        },

        goToSolve() {
            if (this.selectedActivities.length < this.pickCount) return;
            this.currentSubStep = 0;
            this.finalResults = [];
            this.loadActivity(this.selectedActivities[0]);
            this.step = 2;
            this.$nextTick(() => this.animateStep2());
        },

        backToSelect() {
            this.step = 1;
            this.$nextTick(() => this.animateStep1());
        },

        loadActivity(activityIdx) {
            const activity = this.activities[activityIdx];
            this.totalStepsForCurrent = activity.correctSteps.length;
            const steps = activity.correctSteps.map((text, i) => ({ id: i, text }));
            this.poolSteps = this.shuffle([...steps]);
            this.answerSteps = [];
            this.checkResult = null;
        },

        get currentSolvingActivity() {
            if (this.selectedActivities.length === 0) return null;
            return this.activities[this.selectedActivities[this.currentSubStep]] ?? null;
        },

        findActivityIndex(name) {
            return this.activities.findIndex(a => a.name === name);
        },

        shuffle(arr) {
            for (let i = arr.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [arr[i], arr[j]] = [arr[j], arr[i]];
            }
            return arr;
        },

        moveToAnswer(step, idx) {
            this.poolSteps.splice(idx, 1);
            this.answerSteps.push(step);
            this.checkResult = null;
        },

        removeFromAnswer(step, idx) {
            this.answerSteps.splice(idx, 1);
            this.poolSteps.push(step);
            this.checkResult = null;
        },

        moveUp(idx) {
            if (idx <= 0) return;
            [this.answerSteps[idx], this.answerSteps[idx-1]] = [this.answerSteps[idx-1], this.answerSteps[idx]];
            this.answerSteps = [...this.answerSteps];
            this.checkResult = null;
        },

        moveDown(idx) {
            if (idx >= this.answerSteps.length - 1) return;
            [this.answerSteps[idx], this.answerSteps[idx+1]] = [this.answerSteps[idx+1], this.answerSteps[idx]];
            this.answerSteps = [...this.answerSteps];
            this.checkResult = null;
        },

        resetCurrent() {
            this.loadActivity(this.selectedActivities[this.currentSubStep]);
        },

        checkAnswer() {
            const activity = this.currentSolvingActivity;
            const userOrder = this.answerSteps.map(s => s.text);
            const correctOrder = activity.correctSteps;

            let isCorrect = userOrder.length === correctOrder.length;
            if (isCorrect) {
                for (let i = 0; i < correctOrder.length; i++) {
                    if (userOrder[i] !== correctOrder[i]) { isCorrect = false; break; }
                }
            }

            this.checkResult = {
                correct: isCorrect,
                message: isCorrect 
                    ? 'Luar biasa! Urutan algoritmamu sudah benar.' 
                    : 'Ayo coba lagi! Urutan langkahnya belum tepat nih.'
            };

            if (isCorrect) this.showConfetti();
        },

        finishCurrentActivity() {
            const actIdx = this.selectedActivities[this.currentSubStep];
            const activity = this.activities[actIdx];
            this.finalResults.push({
                name: activity.name,
                icon: activity.icon,
                answer: this.answerSteps.map(s => s.text),
            });

            if (this.currentSubStep < this.selectedActivities.length - 1) {
                this.currentSubStep++;
                this.loadActivity(this.selectedActivities[this.currentSubStep]);
                this.$nextTick(() => this.animateStep2());
            } else {
                this.step = 3;
                this.$nextTick(() => this.animateStep3());
            }
        },

        async submitAnswers() {
            this.submitting = true;
            try {
                const res = await fetch(submitUrl, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': csrfToken, 
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ 
                        answer_data: this.finalResults // Kirim array langsung, jangan di-stringify lagi
                    }),
                });

                if (res.ok) {
                    this.submitted = true;
                    this.showConfetti();
                } else {
                    const error = await res.json();
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Gagal Mengumpulkan', 
                        text: error.message || 'Coba lagi beberapa saat lagi.', 
                        confirmButtonColor: '#6366f1' 
                    });
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Koneksi Error', text: 'Periksa koneksi internetmu.', confirmButtonColor: '#6366f1' });
            } finally { this.submitting = false; }
        },

        playAgain() {
            this.step = 1;
            this.selectedActivities = [];
            this.finalResults = [];
            this.submitted = false;
            this.currentSubStep = 0;
            this.$nextTick(() => this.animateStep1());
        },

        // DRAG & DROP
        onDragStart(e, step, from, idx) {
            this.dragging = step; this.draggingFrom = from;
            e.currentTarget.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        },
        onDragEnd(e) {
            e.currentTarget.classList.remove('dragging');
            this.dragging = null; this.draggingFrom = null;
            document.querySelectorAll('.drag-over').forEach(el => el.classList.remove('drag-over'));
        },
        onDragOver(e, zone) { e.preventDefault(); e.currentTarget.classList.add('drag-over'); },
        onDragLeave(e) { e.currentTarget.classList.remove('drag-over'); },
        onDrop(e, zone) {
            e.currentTarget.classList.remove('drag-over');
            if (!this.dragging) return;
            if (this.draggingFrom === 'pool' && zone === 'answer') {
                const idx = this.poolSteps.findIndex(s => s.id === this.dragging.id);
                if (idx >= 0) { this.poolSteps.splice(idx, 1); this.answerSteps.push(this.dragging); }
            } else if (this.draggingFrom === 'answer' && zone === 'pool') {
                const idx = this.answerSteps.findIndex(s => s.id === this.dragging.id);
                if (idx >= 0) { this.answerSteps.splice(idx, 1); this.poolSteps.push(this.dragging); }
            }
            this.dragging = null; this.draggingFrom = null;
        },

        // ANIMATIONS — using fromTo to guarantee end-state visibility
        animateStep1() {
            if (typeof gsap === 'undefined') return;
            gsap.fromTo('.gsap-title', { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: 'power3.out' });
            gsap.fromTo('.gsap-counter', { y: 10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, delay: 0.15, ease: 'power3.out' });
            gsap.fromTo('.gsap-card', { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, stagger: 0.06, delay: 0.2, ease: 'back.out(1.4)' });
            gsap.fromTo('.gsap-preview', { y: 15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, delay: 0.5, ease: 'power3.out' });
            gsap.fromTo('.gsap-btn', { y: 10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4, delay: 0.6, ease: 'power3.out' });
        },
        animateStep2() {
            if (typeof gsap === 'undefined') return;
            gsap.fromTo('.gsap-solve-title', { y: -15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, ease: 'power3.out' });
            gsap.fromTo('.gsap-pool', { x: -25, opacity: 0 }, { x: 0, opacity: 1, duration: 0.5, delay: 0.15, ease: 'power3.out' });
            gsap.fromTo('.gsap-answer', { x: 25, opacity: 0 }, { x: 0, opacity: 1, duration: 0.5, delay: 0.15, ease: 'power3.out' });
            gsap.fromTo('.gsap-actions', { y: 10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4, delay: 0.35, ease: 'power3.out' });
        },
        animateStep3() {
            if (typeof gsap === 'undefined') return;
            gsap.fromTo('.gsap-result-title', { y: -20, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: 'power3.out' });
            gsap.fromTo('.gsap-result-card', { y: 25, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, stagger: 0.1, delay: 0.2, ease: 'back.out(1.2)' });
            gsap.fromTo('.gsap-submit, .gsap-submitted', { y: 15, opacity: 0 }, { y: 0, opacity: 1, duration: 0.5, delay: 0.5, ease: 'power3.out' });
            gsap.fromTo('.gsap-play-again', { y: 10, opacity: 0 }, { y: 0, opacity: 1, duration: 0.4, delay: 0.6, ease: 'power3.out' });
        },

        showConfetti() {
            const colors = ['#6366f1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981', '#06b6d4'];
            for (let i = 0; i < 40; i++) {
                const piece = document.createElement('div');
                piece.className = 'confetti-piece';
                piece.style.left = Math.random() * 100 + 'vw';
                piece.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                piece.style.animationDuration = (2 + Math.random() * 2) + 's';
                piece.style.animationDelay = Math.random() * 0.5 + 's';
                piece.style.width = (6 + Math.random() * 8) + 'px';
                piece.style.height = (6 + Math.random() * 8) + 'px';
                document.body.appendChild(piece);
                setTimeout(() => piece.remove(), 4000);
            }
        },
    };
}
</script>
</body>
</html>
