<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $task->title }} - Workspace | {{ config('app.name', 'AlgoLearn') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- GSAP & SweetAlert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Dark Mode Init --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        /* Paksa Full Screen tanpa scrollbar di body */
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.5);
            border-radius: 20px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 flex flex-col h-screen overflow-hidden"
    x-data="{ 
          sidebarOpen: true, 
          activeTab: 'instruction',
          toggleSidebar() { this.sidebarOpen = !this.sidebarOpen; }
      }">

    {{-- ======================================================================== --}}
    {{-- 1. HEADER (Top Bar) --}}
    {{-- ======================================================================== --}}
    <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 z-40 shrink-0 relative shadow-sm">

        <div class="flex items-center gap-4 min-w-0">
            {{-- Tombol Kembali --}}
            @php
            $backRoute = $task->module_id
            ? route('modules.show', $task->module_id)
            : (Auth::user()->isTeacher() ? route('independent-tasks.index') : route('student.tasks'));
            @endphp
            <a href="{{ $backRoute }}" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 transition-colors" title="Keluar Workspace">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>

            {{-- Divider --}}
            <div class="h-6 w-px bg-gray-300 dark:bg-gray-600"></div>

            {{-- Info Tugas --}}
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-bold text-gray-800 dark:text-white truncate">
                        {{ $task->title }}
                    </h1>
                    <span class="hidden md:inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300">
                        {{ $task->module_id ? 'Bab ' . $task->module->order : 'Mandiri' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Right Side Actions --}}
        <div class="flex items-center gap-4">
            {{-- Toggle Sidebar Button (Desktop) --}}
            <button @click="toggleSidebar()"
                class="hidden md:flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors"
                :title="sidebarOpen ? 'Tutup Sidebar' : 'Buka Sidebar'">
                <svg class="w-5 h-5 transition-transform duration-300" :class="{'rotate-180': !sidebarOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
                <span x-text="sidebarOpen ? 'Full Screen' : 'Show Guide'"></span>
            </button>

            {{-- Form Upload --}}
            <form id="submission-form" action="{{ route('workspace.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="project_file" id="project_file_input" accept=".sb3" class="hidden" onchange="document.getElementById('submission-form').submit()">

                <button type="button" onclick="triggerUpload()"
                    class="relative inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg hover:shadow-green-500/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>{{ $submission ? 'Update Project' : 'Kumpulkan' }}</span>
                </button>
            </form>
        </div>
    </header>

    {{-- ======================================================================== --}}
    {{-- 2. MAIN WORKSPACE AREA --}}
    {{-- ======================================================================== --}}
    <div class="flex-1 flex overflow-hidden relative">

        {{-- SIDEBAR --}}
        <aside
            class="bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col z-30 transition-all duration-300 ease-in-out absolute md:relative h-full shadow-xl md:shadow-none"
            :class="sidebarOpen ? 'w-80 translate-x-0' : 'w-0 -translate-x-full md:translate-x-0 md:w-0 overflow-hidden border-none'">
            {{-- Tabs Header --}}
            <div class="flex border-b border-gray-100 dark:border-gray-700 shrink-0" x-show="sidebarOpen">
                <button @click="activeTab = 'instruction'"
                    class="flex-1 py-3 text-sm font-bold transition-colors relative"
                    :class="activeTab === 'instruction' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    Instruksi
                    <div x-show="activeTab === 'instruction'" class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400"></div>
                </button>
                <button @click="activeTab = 'status'"
                    class="flex-1 py-3 text-sm font-bold transition-colors relative"
                    :class="activeTab === 'status' ? 'text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/20' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'">
                    Status
                    @if($submission)
                    <span class="absolute top-3 right-6 w-2 h-2 rounded-full {{ $submission->status == 'graded' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                    @endif
                    <div x-show="activeTab === 'status'" class="absolute bottom-0 left-0 w-full h-0.5 bg-indigo-600 dark:bg-indigo-400"></div>
                </button>
            </div>

            {{-- Sidebar Content --}}
            <div class="flex-1 overflow-y-auto p-5 custom-scrollbar" x-show="sidebarOpen">

                {{-- KONTEN INSTRUKSI --}}
                <div x-show="activeTab === 'instruction'" class="space-y-6 animate-fadeIn">
                    <div class="prose prose-sm prose-indigo dark:prose-invert max-w-none">
                        <h3 class="text-gray-900 dark:text-white font-bold text-base mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Misi Kamu:
                        </h3>
                        <div class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
                            {{ $task->instruction }}
                        </div>
                    </div>

                    @if($task->starter_project_path)
                    <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-xl">
                        <h4 class="font-bold text-indigo-900 dark:text-indigo-300 text-xs uppercase mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Bahan Praktik
                        </h4>
                        <p class="text-xs text-indigo-600 dark:text-indigo-400 mb-3">
                            Download file starter ini, lalu buka di Scratch (File > Load from your computer).
                        </p>
                        <a href="{{ asset('storage/' . $task->starter_project_path) }}" download class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                            Download .sb3
                        </a>
                    </div>
                    @endif
                </div>

                {{-- KONTEN STATUS --}}
                <div x-show="activeTab === 'status'" class="animate-fadeIn" style="display: none;">
                    @if(isset($submission) && $submission)
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-3 {{ $submission->status == 'graded' ? 'bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-900/30 dark:text-yellow-400 animate-pulse' }}">
                            @if($submission->status == 'graded')
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @else
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            @endif
                        </div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">
                            {{ $submission->status == 'graded' ? 'Tugas Dinilai' : 'Menunggu Penilaian' }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-6">
                            Dikirim: {{ $submission->updated_at->diffForHumans() }}
                        </p>

                        @if($submission->score !== null)
                        <div class="bg-white dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl p-4 mb-4 shadow-sm">
                            <span class="block text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Nilai Kamu</span>
                            <span class="text-4xl font-black text-green-600 dark:text-green-400">{{ $submission->score }}</span>
                        </div>
                        @endif

                        @if($submission->feedback)
                        <div class="text-left bg-indigo-50 dark:bg-indigo-900/20 p-3 rounded-xl border border-indigo-100 dark:border-indigo-800">
                            <p class="text-[10px] font-bold text-indigo-500 uppercase mb-1">Feedback Guru</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $submission->feedback }}"</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="text-sm font-medium">Belum ada tugas dikumpulkan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </aside>

        {{-- EDITOR AREA (IFRAME) --}}
        <main class="flex-1 bg-gray-100 dark:bg-gray-900 relative w-full h-full">

            {{-- Floating Button to Open Sidebar (Mobile / When Closed) --}}
            <button x-show="!sidebarOpen" @click="sidebarOpen = true"
                class="absolute top-4 left-4 z-40 bg-white dark:bg-gray-800 text-indigo-600 dark:text-indigo-400 p-2 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                title="Buka Panel">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Scratch Iframe --}}
            <iframe
                id="scratch-frame"
                src="{{ asset('scratch/index.html') }}"
                class="w-full h-full border-none"
                allowtransparency="true"
                allowfullscreen="true"
                allow="geolocation; microphone; camera">
            </iframe>
        </main>

    </div>

    {{-- Script Upload --}}
    <script>
        function triggerUpload() {
            Swal.fire({
                title: 'Upload Project Scratch',
                html: `
                    <div class="text-left text-sm text-gray-600 dark:text-gray-300">
                        <p class="mb-3 font-medium">Langkah-langkah:</p>
                        <ol class="list-decimal pl-5 space-y-2 mb-4">
                            <li>Di editor Scratch (sebelah kanan), klik menu <b>File</b>.</li>
                            <li>Pilih <b>Save to your computer</b>.</li>
                            <li>File <b>.sb3</b> akan terdownload.</li>
                            <li>Upload file tersebut di sini.</li>
                        </ol>
                    </div>
                `,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Pilih File .sb3',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('project_file_input').click();
                }
            })
        }

        // GSAP Header Animation
        gsap.from("header", {
            y: -20,
            opacity: 0,
            duration: 0.8,
            ease: "power3.out"
        });
    </script>

</body>

</html>