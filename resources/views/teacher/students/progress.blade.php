<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Progress Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Total Siswa</h4>
                    <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ $students->count() }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Total Materi</h4>
                    <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalSubModules }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-2">Total Aktivitas (Tugas)</h4>
                    <p class="text-3xl font-extrabold text-violet-600 dark:text-violet-400">{{ $totalTasks }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Laporan Progress Belajar</h3>
                    
                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('teacher.students.progress') }}" class="flex flex-col xl:flex-row gap-3">
                        <div class="relative w-full xl:w-[450px]">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-12 py-3 pr-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" placeholder="Cari nama siswa...">
                        </div>
                        <select name="classroom_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                            <option value="">Semua Kelas</option>
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}" {{ request('classroom_id') == $classroom->id ? 'selected' : '' }}>{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                        <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full px-4 py-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Pasif" {{ request('status') == 'Pasif' ? 'selected' : '' }}>Pasif</option>
                            <option value="Belum Ada Aktivitas" {{ request('status') == 'Belum Ada Aktivitas' ? 'selected' : '' }}>Baru / Belum Aktif</option>
                        </select>
                        <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl border border-indigo-700 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'classroom_id', 'status']))
                            <a href="{{ route('teacher.students.progress') }}" class="px-4 py-3 flex items-center justify-center text-sm font-medium text-gray-700 bg-white rounded-xl border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 transition" title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">No</th>
                                <th scope="col" class="px-6 py-4">Nama Siswa</th>
                                <th scope="col" class="px-6 py-4">Progress Materi</th>
                                <th scope="col" class="px-6 py-4">Aktivitas (Tugas)</th>
                                <th scope="col" class="px-6 py-4">Aktivitas Terakhir</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-indigo-600 dark:text-indigo-400">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $student->classroom ? $student->classroom->name : '' }}</div>
                                </td>
                                
                                {{-- Progress Materi --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 max-w-[100px]">
                                            @php $materiPercent = $totalSubModules > 0 ? ($student->read_materials / $totalSubModules) * 100 : 0; @endphp
                                            <div class="bg-emerald-500 h-2.5 rounded-full" style="width: {{ $materiPercent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold">{{ $student->read_materials }} / {{ $totalSubModules }}</span>
                                    </div>
                                </td>
                                
                                {{-- Aktivitas (Tugas) --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 max-w-[100px]">
                                            @php $taskPercent = $totalTasks > 0 ? ($student->completed_tasks / $totalTasks) * 100 : 0; @endphp
                                            <div class="bg-violet-500 h-2.5 rounded-full" style="width: {{ $taskPercent }}%"></div>
                                        </div>
                                        <span class="text-xs font-bold">{{ $student->completed_tasks }} / {{ $totalTasks }}</span>
                                    </div>
                                </td>

                                {{-- Aktivitas Terakhir --}}
                                <td class="px-6 py-4">
                                    @if($student->last_activity)
                                        <span title="{{ $student->last_activity->format('d M Y H:i') }}">
                                            {{ $student->last_activity->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 italic">Belum mulai</span>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    @if($student->status === 'Aktif')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">Aktif</span>
                                    @elseif($student->status === 'Pasif')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400 border border-orange-200 dark:border-orange-800">Pasif</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400 border border-gray-200 dark:border-gray-600">Baru</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
