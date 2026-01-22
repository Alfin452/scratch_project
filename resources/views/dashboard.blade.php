@php
// Logika Sapaan Berdasarkan Waktu
$hour = date('H');
if ($hour >= 5 && $hour < 11) {
    $greeting='Selamat Pagi' ;
    } elseif ($hour>= 11 && $hour < 15) {
        $greeting='Selamat Siang' ;
        } elseif ($hour>= 15 && $hour < 18) {
            $greeting='Selamat Sore' ;
            } else {
            $greeting='Selamat Malam' ;
            }
            @endphp

            <x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'">
            <x-slot name="header">
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard Overview') }}
                </h2>
            </x-slot>

            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-900 dark:to-violet-900 shadow-xl">
                        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 rounded-full bg-black/10 blur-3xl"></div>

                        <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
                            <div class="text-center md:text-left space-y-2">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/20 backdrop-blur-sm border border-white/10 text-white text-xs font-bold uppercase tracking-wider mb-2">
                                    <span>{{ Auth::user()->role === 'teacher' ? 'Instructor Access' : 'Student Workspace' }}</span>
                                    <span class="w-1 h-1 rounded-full bg-white"></span>
                                    <span>{{ now()->format('d M Y') }}</span>
                                </div>
                                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">
                                    {{ $greeting }}, <span class="text-yellow-300">{{ Auth::user()->name }}</span>
                                </h1>
                                <p class="text-indigo-100 max-w-xl text-lg font-medium leading-relaxed">
                                    {{ Auth::user()->isTeacher() 
                                ? 'Pantau perkembangan siswa dan kelola materi pembelajaran Anda hari ini.' 
                                : 'Lanjutkan perjalanan kodingmu. Setiap baris kode membawamu lebih dekat ke tujuan!' }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if(Auth::user()->isTeacher())
                                <a href="{{ route('submissions.gradebook') }}" class="group relative inline-flex items-center px-8 py-3.5 bg-white text-indigo-600 font-bold rounded-full shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                                    <span class="relative z-10 flex items-center">
                                        <svg class="w-5 h-5 mr-2 transition-transform group-hover:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        Periksa Tugas
                                    </span>
                                    <div class="absolute inset-0 bg-indigo-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </a>
                                @else
                                <a href="{{ route('modules.index') }}" class="group relative inline-flex items-center px-8 py-3.5 bg-yellow-400 text-yellow-900 font-bold rounded-full shadow-lg hover:shadow-yellow-400/50 transition-all duration-300 hover:-translate-y-1">
                                    <span class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mulai Belajar
                                    </span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->isTeacher())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 relative overflow-hidden group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Siswa</p>
                                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white mt-2">{{ $totalStudents }}</h3>
                                </div>
                                <div class="p-3 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 text-xs font-medium text-green-500 flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Aktif Belajar
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Modul</p>
                                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white mt-2">{{ $totalModules }}</h3>
                                </div>
                                <div class="p-3 rounded-xl bg-purple-50 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 text-xs font-medium text-purple-500">Materi Terpublikasi</div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow duration-300 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Soal</p>
                                    <h3 class="text-3xl font-extrabold text-gray-800 dark:text-white mt-2">{{ $totalTasks }}</h3>
                                </div>
                                <div class="p-3 rounded-xl bg-pink-50 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 text-xs font-medium text-gray-400">Tantangan tersedia</div>
                        </div>

                        <div class="bg-gradient-to-br from-orange-400 to-red-500 p-6 rounded-2xl shadow-lg text-white hover:shadow-orange-500/30 transition-all duration-300 transform hover:-translate-y-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs font-bold text-orange-100 uppercase tracking-wider">Perlu Dinilai</p>
                                    <h3 class="text-3xl font-extrabold mt-2">{{ $pendingGrading }}</h3>
                                </div>
                                <div class="p-3 rounded-xl bg-white/20 text-white backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            @if($pendingGrading > 0)
                            <a href="{{ route('submissions.gradebook') }}" class="mt-4 inline-flex items-center text-xs font-bold bg-white/20 px-3 py-1.5 rounded-lg hover:bg-white/30 transition">
                                Cek Sekarang <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                            @else
                            <p class="mt-4 text-xs text-orange-100 flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg> Semua tugas ternilai</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Aktivitas Terbaru</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Pengumpulan tugas siswa terkini</p>
                            </div>
                            <a href="{{ route('submissions.gradebook') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 transition flex items-center">
                                Lihat Semua <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tugas</th>
                                        <th class="px-8 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-8 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse($recentSubmissions as $sub)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                        <td class="px-8 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-bold text-sm border border-indigo-200">
                                                    {{ substr($sub->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $sub->user->name }}</div>
                                                    <div class="text-xs text-gray-400">{{ $sub->updated_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4">
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $sub->task->title }}</div>
                                        </td>
                                        <td class="px-8 py-4">
                                            @if($sub->status == 'graded')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Selesai
                                            </span>
                                            @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 animate-pulse">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span> Menunggu
                                            </span>
                                            @endif
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <a href="{{ route('submissions.index', $sub->task_id) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 font-medium text-sm hover:underline">
                                                Review
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-12 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <p class="font-medium">Belum ada pengumpulan tugas saat ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    @if(!Auth::user()->isTeacher())
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                        <div class="space-y-8">
                            <div class="relative bg-gray-900 rounded-3xl p-6 text-white shadow-2xl overflow-hidden border border-gray-800">
                                <div class="absolute top-0 right-0 w-48 h-48 bg-purple-500/20 rounded-full blur-3xl -mr-16 -mt-16"></div>
                                <div class="absolute bottom-0 left-0 w-32 h-32 bg-indigo-500/20 rounded-full blur-2xl -ml-10 -mb-10"></div>

                                <div class="relative z-10">
                                    <div class="flex justify-between items-start mb-6">
                                        <div>
                                            <p class="text-gray-400 text-xs font-bold uppercase tracking-widest">Player Stats</p>
                                            <h3 class="text-lg font-bold">Total Experience</h3>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/20">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <span class="text-5xl font-extrabold tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-gray-400">{{ number_format($totalScore) }}</span>
                                        <span class="text-sm font-bold text-gray-500 ml-1">XP</span>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex justify-between text-xs font-bold text-gray-400">
                                            <span>Progress Level</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-800 rounded-full h-2.5 overflow-hidden border border-gray-700">
                                            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 h-2.5 rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <p class="text-right text-[10px] text-gray-500 mt-1">{{ $completedTasks }} / {{ $totalTasksAvailable }} Misi Selesai</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                                <h4 class="font-bold text-gray-800 dark:text-white mb-5 flex items-center">
                                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </span>
                                    Cara Belajar
                                </h4>
                                <div class="space-y-6 relative before:absolute before:inset-y-0 before:left-4 before:w-0.5 before:bg-gray-100 dark:before:bg-gray-700">
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-1 w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-blue-500 text-white flex items-center justify-center font-bold text-xs shadow-sm z-10">1</div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Pelajari Materi</p>
                                        <p class="text-xs text-gray-500 leading-relaxed mt-1">Baca modul yang tersedia di setiap bab untuk memahami konsep.</p>
                                    </div>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-1 w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-purple-500 text-white flex items-center justify-center font-bold text-xs shadow-sm z-10">2</div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Kerjakan Tantangan</p>
                                        <p class="text-xs text-gray-500 leading-relaxed mt-1">Unduh file latihan dan selesaikan tantangan kodingnya.</p>
                                    </div>
                                    <div class="relative pl-10">
                                        <div class="absolute left-0 top-1 w-8 h-8 rounded-full border-2 border-white dark:border-gray-800 bg-green-500 text-white flex items-center justify-center font-bold text-xs shadow-sm z-10">3</div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-gray-200">Dapatkan Nilai</p>
                                        <p class="text-xs text-gray-500 leading-relaxed mt-1">Upload hasil kerjamu (.sb3) dan tunggu review guru.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="font-bold text-xl text-gray-800 dark:text-white">Jalur Pembelajaran</h3>
                                <span class="text-xs font-bold text-gray-500 bg-gray-100 dark:bg-gray-700 px-3 py-1.5 rounded-full">{{ $modules->count() }} Modul Tersedia</span>
                            </div>

                            <div class="space-y-4">
                                @foreach($modules as $mod)
                                <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-300">
                                    <div class="flex flex-col sm:flex-row gap-5">
                                        <div class="shrink-0">
                                            <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 flex flex-col items-center justify-center font-bold border border-indigo-100 dark:border-indigo-800 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                                <span class="text-[10px] uppercase tracking-wide opacity-70">BAB</span>
                                                <span class="text-2xl">{{ $mod->order }}</span>
                                            </div>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white truncate pr-4 group-hover:text-indigo-600 transition-colors">{{ $mod->title }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mb-4">{{ $mod->description }}</p>

                                            @if($mod->tasks->count() > 0)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($mod->tasks as $t)
                                                @php
                                                $sub = $t->submissions->first();
                                                $badgeClass = $sub
                                                ? ($sub->status == 'graded'
                                                ? 'bg-green-50 text-green-700 border-green-200'
                                                : 'bg-yellow-50 text-yellow-700 border-yellow-200')
                                                : 'bg-gray-50 text-gray-500 border-gray-200';
                                                $icon = $sub ? ($sub->status == 'graded' ? '★' : '•') : '○';
                                                @endphp
                                                <div class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $badgeClass }} flex items-center transition-colors">
                                                    <span class="mr-1.5 text-xs">{{ $icon }}</span> Latihan {{ $loop->iteration }}
                                                </div>
                                                @endforeach
                                            </div>
                                            @else
                                            <div class="text-xs text-gray-400 italic">Belum ada latihan.</div>
                                            @endif
                                        </div>

                                        <div class="shrink-0 flex items-center">
                                            <a href="{{ route('modules.show', $mod->id) }}" class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 text-gray-400 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300 transform group-hover:translate-x-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            </x-dynamic-component>