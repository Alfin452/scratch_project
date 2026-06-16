<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekapitulasi Nilai Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Matriks Nilai Semua Tugas</h3>
                    
                    {{-- Filter Form --}}
                    <form method="GET" action="{{ route('teacher.students.grades') }}" class="flex flex-col lg:flex-row gap-3">
                        <div class="relative w-full lg:w-[450px]">
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
                        <button type="submit" class="px-6 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl border border-indigo-700 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'classroom_id']))
                            <a href="{{ route('teacher.students.grades') }}" class="px-4 py-3 flex items-center justify-center text-sm font-medium text-gray-700 bg-white rounded-xl border border-gray-300 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700 transition" title="Reset Filter">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </a>
                        @endif
                    </form>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4 sticky left-0 z-10 bg-gray-50 dark:bg-gray-700 border-r border-gray-200 dark:border-gray-600">No</th>
                                <th scope="col" class="px-6 py-4 sticky left-12 z-10 bg-gray-50 dark:bg-gray-700 border-r border-gray-200 dark:border-gray-600">Nama Siswa</th>
                                @foreach($tasks as $task)
                                    <th scope="col" class="px-4 py-4 text-center border-r border-gray-200 dark:border-gray-600" title="{{ $task->title }}">
                                        Tugas M{{ $task->module->order ?? '-' }}-{{ $task->order }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $index => $student)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                <td class="px-6 py-4 sticky left-0 z-10 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 group-hover:bg-gray-50 dark:group-hover:bg-gray-600">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400 sticky left-12 z-10 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 group-hover:bg-gray-50 dark:group-hover:bg-gray-600">
                                    {{ $student->name }}
                                </td>
                                @foreach($tasks as $task)
                                    @php
                                        // Cari submission untuk task ini oleh siswa ini
                                        $sub = $student->submissions->where('task_id', $task->id)->first();
                                        $score = $sub ? $sub->score : null;
                                        
                                        $bgColor = '';
                                        if ($score === null) {
                                            $bgColor = 'text-gray-400';
                                        } elseif ($score >= 80) {
                                            $bgColor = 'text-emerald-600 font-bold dark:text-emerald-400';
                                        } elseif ($score >= 60) {
                                            $bgColor = 'text-yellow-600 font-bold dark:text-yellow-400';
                                        } else {
                                            $bgColor = 'text-red-600 font-bold dark:text-red-400';
                                        }
                                    @endphp
                                    <td class="px-4 py-4 text-center border-r border-gray-200 dark:border-gray-700 {{ $bgColor }}">
                                        {{ $score !== null ? $score : '-' }}
                                    </td>
                                @endforeach
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ count($tasks) + 2 }}" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data siswa.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
