<x-student-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Aktivitas Belajar</h1>
                <p class="text-gray-500">Riwayat pengumpulan tugas dan umpan balik dari guru.</p>
            </div>

            <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
                @if($submissions->count() > 0)
                <div class="divide-y divide-gray-100">
                    @foreach($submissions as $sub)
                    <div class="p-6 hover:bg-gray-50 transition duration-150">
                        <div class="flex flex-col sm:flex-row justify-between gap-4">
                            <div class="flex gap-4">
                                <div class="flex-shrink-0 mt-1">
                                    @if($sub->status == 'graded')
                                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-10 h-10 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">
                                        {{ $sub->task->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mb-2">
                                        Modul: {{ $sub->task->module->title ?? 'Umum' }} &bull;
                                        {{ $sub->updated_at->isoFormat('D MMMM Y, HH:mm') }}
                                    </p>

                                    @if($sub->feedback)
                                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 text-sm text-indigo-800 mt-2">
                                        <span class="font-bold">Komentar Guru:</span> "{{ $sub->feedback }}"
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right flex flex-col items-end justify-center">
                                @if($sub->status == 'graded')
                                <div class="text-3xl font-extrabold text-green-600">{{ $sub->score }}</div>
                                <div class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nilai Akhir</div>
                                @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">
                                    Menunggu Penilaian
                                </span>
                                @endif

                                <a href="{{ route('workspace.show', $sub->task_id) }}" class="mt-3 text-sm text-indigo-600 hover:underline font-medium">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-12 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <p>Kamu belum mengumpulkan tugas apapun.</p>
                    <a href="{{ route('student.tasks') }}" class="text-indigo-600 font-bold hover:underline mt-2 inline-block">Mulai Kerjakan Tugas</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-student-layout>