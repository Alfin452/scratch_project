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

            <x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'"> <x-slot name="header">
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard Overview') }}
                </h2>
            </x-slot>

            <div class="py-8">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

                    <div class="relative bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-2xl">
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-indigo-50 dark:bg-indigo-900/20 blur-3xl opacity-60 pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-64 h-64 rounded-full bg-blue-50 dark:bg-blue-900/20 blur-3xl opacity-60 pointer-events-none"></div>

                        <div class="relative p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300 rounded-full text-xs font-bold uppercase tracking-wider">
                                        {{ Auth::user()->role === 'teacher' ? 'Instructor Panel' : 'Student Area' }}
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ now()->format('l, d F Y') }}</span>
                                </div>
                                <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                                    {{ $greeting }}, <span class="text-indigo-600">{{ Auth::user()->name }}</span>!
                                </h1>
                                <p class="mt-2 text-gray-600 dark:text-gray-300 max-w-xl text-lg">
                                    {{ Auth::user()->isTeacher() 
                                ? 'Siap untuk mengelola kelas hari ini? Ada beberapa aktivitas siswa yang mungkin perlu perhatian Anda.' 
                                : 'Siap melanjutkan petualangan kodingmu? Mari selesaikan tantangan berikutnya!' }}
                                </p>
                            </div>

                            <div class="shrink-0">
                                @if(Auth::user()->isTeacher())
                                <a href="{{ route('submissions.gradebook') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-indigo-500/30 transition transform hover:-translate-y-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    Periksa Tugas
                                </a>
                                @else
                                <a href="{{ route('modules.index') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-purple-500/30 transition transform hover:-translate-y-1">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Lanjut Belajar
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->isTeacher())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-indigo-300 transition group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Siswa</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalStudents }}</p>
                                </div>
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-purple-300 transition group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Modul Materi</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalModules }}</p>
                                </div>
                                <div class="p-3 bg-purple-50 dark:bg-purple-900/30 rounded-xl text-purple-600 group-hover:bg-purple-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-pink-300 transition group">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Soal</p>
                                    <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $totalTasks }}</p>
                                </div>
                                <div class="p-3 bg-pink-50 dark:bg-pink-900/30 rounded-xl text-pink-600 group-hover:bg-pink-600 group-hover:text-white transition">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-6 rounded-2xl shadow-lg text-white transform hover:-translate-y-1 transition duration-300">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-yellow-100">Perlu Dinilai</p>
                                    <p class="text-3xl font-extrabold mt-1">{{ $pendingGrading }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-xl text-white">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                            </div>
                            @if($pendingGrading > 0)
                            <a href="{{ route('submissions.gradebook') }}" class="mt-4 inline-block text-xs font-bold bg-white/20 px-3 py-1 rounded hover:bg-white/30 transition">
                                Periksa Sekarang &rarr;
                            </a>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-700/30">
                            <h3 class="font-bold text-gray-800 dark:text-gray-200 text-lg">Aktivitas Pengumpulan Terbaru</h3>
                            <a href="{{ route('submissions.gradebook') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Lihat Semua</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-400 uppercase bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Siswa</th>
                                        <th class="px-6 py-4 font-semibold">Tugas</th>
                                        <th class="px-6 py-4 font-semibold">Waktu</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 font-semibold text-right">Opsi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                                    @forelse($recentSubmissions as $sub)
                                    <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs border border-gray-200">
                                                    {{ substr($sub->user->name, 0, 1) }}
                                                </div>
                                                <span class="font-medium text-gray-900 dark:text-white">{{ $sub->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $sub->task->title }}</td>
                                        <td class="px-6 py-4 text-xs">{{ $sub->updated_at->diffForHumans() }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $sub->status == 'graded' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-yellow-100 text-yellow-700 border border-yellow-200' }}">
                                                {{ $sub->status == 'graded' ? 'Selesai Dinilai' : 'Menunggu Review' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('submissions.index', $sub->task_id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium text-xs bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                                                Buka
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p>Belum ada aktivitas pengumpulan tugas.</p>
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

                        <div class="space-y-6">
                            <div class="relative bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-xl overflow-hidden">
                                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-white/10 rounded-full blur-xl"></div>
                                <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>

                                <div class="relative z-10">
                                    <h4 class="text-indigo-100 font-medium mb-1 text-sm uppercase tracking-wide">Total Skor XP</h4>
                                    <div class="flex items-baseline gap-2 mb-6">
                                        <span class="text-6xl font-extrabold tracking-tight">{{ $totalScore }}</span>
                                        <span class="text-xl font-medium opacity-80">pts</span>
                                    </div>

                                    <div class="bg-black/20 rounded-xl p-4 backdrop-blur-sm border border-white/10">
                                        <div class="flex justify-between text-xs font-bold mb-2 uppercase tracking-wide opacity-90">
                                            <span>Progress Misi</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-700/50 rounded-full h-3 overflow-hidden">
                                            <div class="bg-gradient-to-r from-green-400 to-emerald-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <div class="mt-2 text-right text-xs text-indigo-200">
                                            {{ $completedTasks }} dari {{ $totalTasksAvailable }} tugas selesai
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                                <h4 class="font-bold text-gray-800 dark:text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Panduan Belajar
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Baca Materi</p>
                                            <p class="text-xs text-gray-500">Pahami konsep dasar di setiap bab.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Praktik Scratch</p>
                                            <p class="text-xs text-gray-500">Download starter file & kerjakan tantangan.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-700 dark:text-gray-300">Upload & Nilai</p>
                                            <p class="text-xs text-gray-500">Kumpulkan file .sb3 dan tunggu nilai guru.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-bold text-xl text-gray-800 dark:text-white">Jalur Pembelajaran</h3>
                                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $modules->count() }} Modul Tersedia</span>
                            </div>

                            <div class="grid gap-4">
                                @foreach($modules as $mod)
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:border-indigo-300 hover:shadow-md transition duration-200 group">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-xs font-extrabold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md uppercase tracking-wide">BAB {{ $mod->order }}</span>
                                                @if($mod->tasks->count() > 0)
                                                <span class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                    </svg>
                                                    {{ $mod->tasks->count() }} Soal
                                                </span>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-lg text-gray-900 dark:text-white group-hover:text-indigo-600 transition">{{ $mod->title }}</h4>
                                            <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $mod->description }}</p>
                                        </div>

                                        <a href="{{ route('modules.show', $mod->id) }}" class="shrink-0 inline-flex items-center justify-center px-5 py-2.5 bg-gray-50 hover:bg-indigo-600 text-gray-700 hover:text-white text-sm font-bold rounded-xl transition-all duration-200">
                                            Buka Materi
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>
                                        </a>
                                    </div>

                                    @if($mod->tasks->count() > 0)
                                    <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-wrap gap-2">
                                        @foreach($mod->tasks as $t)
                                        @php
                                        $sub = $t->submissions->first();
                                        $statusColor = $sub
                                        ? ($sub->status == 'graded' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-yellow-100 text-yellow-700 border-yellow-200')
                                        : 'bg-gray-50 text-gray-400 border-gray-200';
                                        $icon = $sub ? '✓' : '○';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 border rounded text-[10px] font-bold uppercase tracking-wider {{ $statusColor }}" title="{{ $t->title }}">
                                            <span class="mr-1">{{ $icon }}</span> Latihan {{ $loop->iteration }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
            </x-dynamic-component>