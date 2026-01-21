<x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'">

    {{-- CSS Khusus untuk Workspace Full Screen --}}
    <style>
        /* Sembunyikan Navbar & Header bawaan Layout UTAMA saja */
        nav {
            display: none !important;
        }

        body>div>header {
            display: none !important;
        }

        /* Hanya sembunyikan header layout */

        .min-h-screen {
            height: 100vh;
            overflow: hidden;
        }

        footer {
            display: none !important;
        }

        /* Hilangkan scrollbar default */
        body {
            overflow: hidden;
        }
    </style>

    <div class="font-sans antialiased bg-gray-100 h-screen flex flex-col" x-data="{ sidebarOpen: true, activeTab: 'instruction' }">

        <div class="h-14 bg-indigo-900 text-white flex items-center justify-between px-4 shadow-md z-30 relative shrink-0">
            <div class="flex items-center gap-4">

                {{-- === LOGIKA TOMBOL KEMBALI === --}}
                @php
                $backRoute = '#';
                if ($task->module_id) {
                // Jika tugas bagian dari modul
                $backRoute = route('modules.show', $task->module_id);
                } else {
                // Jika tugas mandiri (cek role user)
                $backRoute = Auth::user()->isTeacher() ? route('independent-tasks.index') : route('student.tasks');
                }
                @endphp

                <a href="{{ $backRoute }}" class="text-indigo-300 hover:text-white transition flex items-center gap-2 text-sm font-medium px-3 py-1.5 hover:bg-indigo-800 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Keluar</span>
                </a>

                <div class="flex items-center gap-3 border-l border-indigo-700 pl-4">
                    <span class="bg-indigo-800 text-xs px-2 py-0.5 rounded text-indigo-200 uppercase tracking-wide">
                        {{ $task->module_id ? 'Bab ' . $task->module->order : 'Tugas Mandiri' }}
                    </span>
                    <h1 class="font-bold text-lg truncate max-w-xs md:max-w-md">{{ $task->title }}</h1>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-xs text-indigo-300 hidden md:block border-r border-indigo-700 pr-3 mr-1">
                    {{ Auth::user()->name }}
                </div>

                {{-- FORM UPLOAD TUGAS --}}
                <form id="submission-form" action="{{ route('workspace.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="project_file" id="project_file_input" accept=".sb3" class="hidden" onchange="document.getElementById('submission-form').submit()">

                    <button type="button" onclick="triggerUpload()" class="px-4 py-1.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded text-sm transition shadow-lg flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                        </svg>
                        {{ $submission ? 'Upload Ulang' : 'Kumpulkan' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="flex-1 flex overflow-hidden relative">

            <aside
                x-show="sidebarOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                class="w-80 bg-white border-r border-gray-200 flex flex-col z-20 shadow-2xl absolute md:relative h-full"
                style="min-width: 20rem;">

                <div class="flex border-b border-gray-200 bg-gray-50">
                    <button
                        @click="activeTab = 'instruction'"
                        class="flex-1 py-3 text-sm font-bold transition-colors duration-200"
                        :class="activeTab === 'instruction' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'">
                        Instruksi
                    </button>
                    <button
                        @click="activeTab = 'status'"
                        class="flex-1 py-3 text-sm font-bold transition-colors duration-200"
                        :class="activeTab === 'status' ? 'text-indigo-600 border-b-2 border-indigo-600 bg-white' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-100'">
                        Status
                        @if($submission)
                        <span class="ml-1 inline-block w-2 h-2 rounded-full {{ $submission->status == 'graded' ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                        @endif
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 relative">

                    <div x-show="activeTab === 'instruction'" class="space-y-6">
                        <div class="prose prose-sm prose-indigo max-w-none text-gray-600">
                            <h3 class="text-gray-900 font-bold mb-2">Tugas Kamu:</h3>
                            <p>{{ $task->instruction }}</p>
                        </div>

                        @if($task->starter_project_path)
                        <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="p-2 bg-indigo-100 rounded-lg text-indigo-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-indigo-900 text-sm">Starter Project</h4>
                                    <p class="text-xs text-indigo-600">
                                        1. Download file ini.<br>
                                        2. Di Scratch kanan, klik <b>File > Load from your computer</b>.
                                    </p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $task->starter_project_path) }}" download class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
                                Download .sb3
                            </a>
                        </div>
                        @endif
                    </div>

                    <div x-show="activeTab === 'status'" style="display: none;">
                        @if(isset($submission) && $submission)
                        <div class="p-5 rounded-xl border {{ $submission->status == 'graded' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold uppercase tracking-wider {{ $submission->status == 'graded' ? 'text-green-600' : 'text-yellow-600' }}">
                                    {{ $submission->status == 'graded' ? 'Selesai Dinilai' : 'Menunggu Penilaian' }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $submission->updated_at->format('d M H:i') }}</span>
                            </div>

                            @if($submission->score !== null)
                            <div class="text-center py-4 border-t border-b border-gray-200/50 my-3">
                                <span class="block text-4xl font-extrabold text-green-600">{{ $submission->score }}</span>
                                <span class="text-xs text-gray-400 uppercase font-bold tracking-widest">Nilai Akhir</span>
                            </div>
                            @if($submission->feedback)
                            <div class="bg-white p-3 rounded-lg border border-gray-100 mt-3">
                                <p class="text-xs text-gray-400 font-bold mb-1">Feedback Guru:</p>
                                <p class="text-sm text-gray-600 italic">"{{ $submission->feedback }}"</p>
                            </div>
                            @endif
                            @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-12 h-12 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center mb-2 animate-pulse">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 font-medium">Tugas Dikirim</p>
                                <p class="text-xs text-gray-500">Guru akan segera memeriksanya.</p>
                            </div>
                            @endif
                        </div>
                        @else
                        <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            <p class="text-sm">Belum ada tugas dikumpulkan.</p>
                        </div>
                        @endif
                    </div>

                </div>

                <button
                    @click="sidebarOpen = false"
                    class="absolute top-1/2 -right-3 transform -translate-y-1/2 bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 shadow-md rounded-full p-1 z-50 hidden md:block">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
            </aside>

            <main class="flex-1 bg-gray-100 relative w-full h-full">

                <button
                    x-show="!sidebarOpen"
                    @click="sidebarOpen = true"
                    class="absolute top-4 left-4 z-40 bg-white/90 backdrop-blur border border-gray-200 text-indigo-600 hover:bg-indigo-50 shadow-lg px-3 py-2 rounded-lg flex items-center gap-2 text-sm font-bold transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                    </svg>
                    <span>Instruksi</span>
                </button>

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
    </div>

    <script>
        function triggerUpload() {
            Swal.fire({
                title: 'Upload File .sb3',
                html: `
                    <div class="text-left text-sm text-gray-600">
                        <ol class="list-decimal pl-5 space-y-1">
                            <li>Di editor Scratch, klik <b>File > Save to your computer</b>.</li>
                            <li>File <b>.sb3</b> akan terdownload.</li>
                            <li>Upload file tersebut di sini.</li>
                        </ol>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Pilih File',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4F46E5',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('project_file_input').click();
                }
            })
        }
    </script>

</x-dynamic-component>