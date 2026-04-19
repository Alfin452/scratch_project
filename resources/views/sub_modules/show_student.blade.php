<x-student-layout>
    <div class="py-2 lg:py-2 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumb --}}
            <nav class="flex mb-8 gsap-fade-down opacity-0 -translate-y-4" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('modules.show', $module->id) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-white md:ml-2 transition-colors">Bab {{ $module->order }}: {{ Str::limit($module->title, 20) }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">{{ Str::limit($subModule->title, 25) }}</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8 justify-center" x-data="readingTimer(15)" x-init="startTimer()">

                {{-- KONTEN UTAMA --}}
                <div class="w-full lg:w-3/4 space-y-8">

                    {{-- KARTU MATERI --}}
                    <div class="gsap-content opacity-0 translate-y-8 bg-white dark:bg-gray-800 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden relative">

                        {{-- Decorative Background --}}
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

                        {{-- Header Materi --}}
                        <div class="relative p-8 md:p-10 border-b border-gray-100 dark:border-gray-700">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300 text-xs font-bold tracking-wide uppercase mb-4">
                                MATERI {{ $subModule->order }}
                            </span>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight relative z-10">
                                {{ $subModule->title }}
                            </h1>
                        </div>

                        {{-- Isi Konten --}}
                        <div class="p-8 md:p-12">
                            <div class="prose prose-lg prose-indigo max-w-none dark:prose-invert prose-headings:font-bold prose-a:text-indigo-600 hover:prose-a:text-indigo-500">
                                {!! $subModule->content !!}
                            </div>
                        </div>

                        {{-- Footer Navigasi --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-700 p-6 flex flex-col md:flex-row justify-between items-center gap-4">
                            
                            <a href="{{ route('modules.show', $module->id) }}" class="px-6 py-3 text-gray-600 dark:text-gray-300 font-bold hover:text-indigo-600 dark:hover:text-indigo-400 transition flex items-center justify-center w-full md:w-auto">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                Kurikulum Bab
                            </a>

                            <div class="w-full md:w-auto flex justify-end">
                                {{-- KONTEN ASLI (Tersembunyi sampai timer selesai jika mau timer, untuk sekarang disabled timer biar UX lebih flowing) --}}
                                @php
                                    $nextUrl = '';
                                    if ($nextItem) {
                                        $nextUrl = $nextItem->item_type === 'submodule' ? route('sub_modules.show_student', $nextItem->id) : route('workspace.show', $nextItem->id);
                                    } elseif ($nextModule) {
                                        $nextUrl = route('modules.show', $nextModule->id);
                                    } else {
                                        $nextUrl = route('dashboard');
                                    }
                                @endphp

                                <button id="btn-complete" @click="markCompleteAndProceed('{{ $nextUrl }}')" :disabled="!canProceed"
                                    :class="canProceed ? 'bg-emerald-500 hover:bg-emerald-600' : 'bg-gray-400 cursor-not-allowed'"
                                    class="inline-flex items-center px-8 py-3 text-white font-bold rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all transform w-full md:w-auto justify-center">
                                    
                                    <span x-show="!canProceed" x-text="`Selesaikan membaca (${timeLapse}s)`"></span>

                                    <div x-show="canProceed" class="flex items-center">
                                        <svg id="btn-spinner" class="w-5 h-5 mr-2 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <svg id="btn-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Tandai Selesai & Lanjut
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", (event) => {
            gsap.to(".gsap-fade-down", { opacity: 1, y: 0, duration: 0.8, ease: "power3.out" });
            gsap.to(".gsap-content", { opacity: 1, y: 0, duration: 0.8, delay: 0.2, ease: "power3.out" });
        });

        function readingTimer(duration) {
            return {
                timeLapse: duration,
                canProceed: false,
                startTimer() {
                    let interval = setInterval(() => {
                        if (this.timeLapse > 0) {
                            this.timeLapse--;
                        } else {
                            this.canProceed = true;
                            clearInterval(interval);
                        }
                    }, 1000);
                },
                markCompleteAndProceed(nextUrl) {
                    if (!this.canProceed) return;
                    
                    const btn = document.getElementById('btn-complete');
                    const spinner = document.getElementById('btn-spinner');
                    const icon = document.getElementById('btn-icon');
                    
                    btn.disabled = true;
                    icon.classList.add('hidden');
                    spinner.classList.remove('hidden');

                    fetch('{{ route('sub_modules.complete', $subModule->id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({})
                    }).then(response => response.json())
                    .then(data => {
                        window.location.href = nextUrl;
                    }).catch(error => {
                        console.error('Error:', error);
                        window.location.href = nextUrl; // Tetap lanjut walau error biar tidak blocking UX
                    });
                }
            }
        }
    </script>

    <style>
        .prose figure.image { display: block; margin-bottom: 1.5rem; margin-top: 1.5rem; max-width: 100%; margin-left: auto; margin-right: auto; }
        .prose figure.image img { width: 100%; height: auto; margin: 0 auto; display: block; }
        .prose figure.image figcaption { display: block; text-align: center !important; color: #6b7280; font-size: 0.875rem; margin-top: 0.5rem; font-style: italic; }
        .prose figure.image.image-style-align-left { margin-left: 0; margin-right: auto; }
        .prose figure.image.image-style-align-right { margin-left: auto; margin-right: 0; }
        .prose figure.image.image-style-align-center { margin-left: auto; margin-right: auto; }
        .prose .text-align-left { text-align: left !important; }
        .prose .text-align-center { text-align: center !important; }
        .prose .text-align-right { text-align: right !important; }
        .prose .text-align-justify { text-align: justify !important; }
        .prose mark.marker-yellow { background-color: var(--ck-highlight-marker-yellow); }
        .prose mark.marker-green { background-color: var(--ck-highlight-marker-green); }
        .prose mark.marker-pink { background-color: var(--ck-highlight-marker-pink); }
        .prose mark.marker-blue { background-color: var(--ck-highlight-marker-blue); }
        .prose span.pen-red { color: var(--ck-highlight-pen-red); background-color: transparent; }
        .prose span.pen-green { color: var(--ck-highlight-pen-green); background-color: transparent; }
        .prose .text-tiny { font-size: 0.7em !important; }
        .prose .text-small { font-size: 0.85em !important; }
        .prose .text-big { font-size: 1.5em !important; }
        .prose .text-huge { font-size: 2em !important; }
        .prose .spasi-normal { line-height: normal !important; }
        .prose .spasi-1-15 { line-height: 1.15 !important; }
        .prose .spasi-1-5 { line-height: 1.5 !important; }
        .prose .spasi-2 { line-height: 2 !important; }
    </style>
</x-student-layout>
