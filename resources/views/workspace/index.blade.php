<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Workspace - {{ $task->title }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            overflow: hidden;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 h-screen flex flex-col" x-data="{ sidebarOpen: true, activeTab: 'instruction' }">

    <header class="h-14 bg-indigo-900 text-white flex items-center justify-between px-4 shadow-md z-30 relative">
        <div class="flex items-center gap-4">
            <a href="{{ route('modules.show', $task->module_id) }}" class="text-indigo-300 hover:text-white transition flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Keluar
            </a>
            <div class="flex items-center gap-2">
                <span class="text-gray-400">|</span>
                <h1 class="font-bold text-lg truncate max-w-xs md:max-w-md">{{ $task->title }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-xs text-indigo-300 hidden md:block">
                {{ Auth::user()->name }}
            </div>
            <button onclick="submitProject()" class="px-4 py-1.5 bg-green-500 hover:bg-green-600 text-white font-bold rounded text-sm transition shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Kumpulkan
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">

        <aside
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
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

                <div x-show="activeTab === 'instruction'" x-cloak class="space-y-6">
                    <div class="prose prose-sm prose-indigo max-w-none">
                        <h3 class="text-gray-900 font-bold mb-2">Langkah Pengerjaan:</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $task->instruction }}</p>
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
                                <p class="text-xs text-indigo-600">Download file ini, lalu buka di Scratch (File > Load).</p>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $task->starter_project_path) }}" download class="block w-full text-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-lg transition shadow-sm">
                            Download .sb3
                        </a>
                    </div>
                    @endif
                </div>

                <div x-show="activeTab === 'status'" x-cloak>
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
                            <p class="text-sm text-gray-600 font-medium">Tugas sudah dikirim.</p>
                            <p class="text-xs text-gray-500">Menunggu guru memeriksa pekerjaanmu.</p>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex flex-col items-center justify-center h-64 text-center text-gray-400">
                        <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        <p class="text-sm">Belum ada pengumpulan.</p>
                        <button onclick="activeTab = 'instruction'" class="mt-2 text-indigo-500 hover:underline text-xs">Kembali ke Instruksi</button>
                    </div>
                    @endif
                </div>

            </div>

            <button
                @click="sidebarOpen = false"
                class="absolute top-1/2 -right-3 transform -translate-y-1/2 bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 shadow-md rounded-full p-1 z-50 hidden md:block"
                title="Sembunyikan Panel">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
        </aside>

        <main class="flex-1 bg-gray-100 relative w-full">

            <button
                x-show="!sidebarOpen"
                @click="sidebarOpen = true"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-x-4"
                x-transition:enter-end="opacity-100 translate-x-0"
                class="absolute top-4 left-4 z-40 bg-white/90 backdrop-blur border border-gray-200 text-indigo-600 hover:bg-indigo-50 shadow-lg px-3 py-2 rounded-lg flex items-center gap-2 text-sm font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                </svg>
                <span>Instruksi</span>
            </button>

            <div id="loader" class="absolute inset-0 flex items-center justify-center bg-gray-100 z-0">
                <div class="text-center">
                    <svg class="animate-spin h-10 w-10 text-indigo-600 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Memuat Scratch Engine...</p>
                </div>
            </div>

            <iframe
                id="scratch-frame"
                src="{{ asset('scratch/index.html') }}"
                class="w-full h-full border-none relative z-10 opacity-0 transition-opacity duration-1000"
                allowtransparency="true"
                allow="geolocation; microphone; camera"
                onload="document.getElementById('loader').style.display='none'; this.classList.remove('opacity-0');"></iframe>
        </main>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function submitProject() {
            Swal.fire({
                title: 'Kumpulkan Tugas',
                html: `
                    <div class="text-left">
                        <p class="text-sm text-gray-600 mb-2">Langkah-langkah:</p>
                        <ol class="text-xs text-gray-500 list-decimal list-inside mb-4 space-y-1">
                            <li>Di editor Scratch, klik <b>File</b> > <b>Save to your computer</b>.</li>
                            <li>Upload file <b>.sb3</b> tersebut di sini.</li>
                        </ol>
                        <input type="file" id="project_file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" accept=".sb3">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Kirim Tugas',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#4F46E5',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const fileInput = document.getElementById('project_file');
                    const file = fileInput.files[0];
                    if (!file) {
                        Swal.showValidationMessage('Pilih file .sb3 dulu!');
                        return false;
                    }
                    const formData = new FormData();
                    formData.append('project_file', file);

                    return fetch("{{ route('submissions.store', $task->id) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(response.statusText);
                            return response.json();
                        })
                        .catch(error => Swal.showValidationMessage(`Gagal: ${error}`));
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Berhasil!', 'Tugas dikirim.', 'success').then(() => {
                        // Reload halaman dan otomatis pindah ke tab Status
                        // Kita bisa pakai localStorage atau url param, tapi reload simpel sudah cukup
                        // Alpine akan reset ke default, jadi user harus klik tab status manual untuk melihat
                        location.reload();
                    });
                }
            })
        }
    </script>
</body>

</html>