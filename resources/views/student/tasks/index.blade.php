<x-student-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8 text-center">
                <h1 class="text-3xl font-extrabold text-gray-900">Daftar Misi & Tugas</h1>
                <p class="mt-2 text-gray-500">Selesaikan semua tantangan untuk meningkatkan skill kodingmu.</p>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($tasks as $task)
                @php
                $submission = $task->submissions->first();
                $status = $submission ? $submission->status : 'pending'; // pending disini artinya belum dikerjakan

                // Tentukan warna border dan badge berdasarkan status
                if (!$submission) {
                $borderColor = 'border-gray-200';
                $badgeColor = 'bg-gray-100 text-gray-600';
                $statusText = 'Belum Dikerjakan';
                } elseif ($submission->status == 'submitted') {
                $borderColor = 'border-yellow-300 ring-2 ring-yellow-100';
                $badgeColor = 'bg-yellow-100 text-yellow-700';
                $statusText = 'Menunggu Nilai';
                } else {
                $borderColor = 'border-green-300';
                $badgeColor = 'bg-green-100 text-green-700';
                $statusText = 'Selesai';
                }
                @endphp

                <div class="bg-white rounded-xl shadow-sm border {{ $borderColor }} p-6 flex flex-col hover:shadow-md transition duration-200">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-xs font-bold uppercase tracking-wider text-indigo-500 bg-indigo-50 px-2 py-1 rounded">
                            Bab {{ $task->module->order ?? '?' }}
                        </span>
                        <span class="text-xs font-bold px-2 py-1 rounded {{ $badgeColor }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $task->title }}</h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">
                        {{ $task->instruction }}
                    </p>

                    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                        @if($submission && $submission->score)
                        <div class="text-sm font-bold text-green-600">
                            Nilai: {{ $submission->score }}/100
                        </div>
                        @else
                        <div class="text-xs text-gray-400">
                            {{ $task->starter_project_path ? 'Ada File Starter' : 'Project Kosong' }}
                        </div>
                        @endif

                        <a href="{{ route('workspace.show', $task->id) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                            {{ $submission ? 'Lihat / Revisi' : 'Kerjakan Sekarang' }} &rarr;
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            @if($tasks->isEmpty())
            <div class="text-center py-12 text-gray-500">
                Belum ada tugas yang tersedia saat ini.
            </div>
            @endif
        </div>
    </div>
</x-student-layout>