<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rekapitulasi Nilai Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[100rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Matriks Nilai Semua Tugas</h3>
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
