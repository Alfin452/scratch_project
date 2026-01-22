<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Penilaian Tugas: ') }} {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-2">

            <div class="mb-4">
                {{-- PERBAIKAN: Cek apakah ada module_id --}}
                @if($task->module_id)
                <a href="{{ route('modules.show', $task->module_id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Modul
                </a>
                @else
                {{-- Jika Tugas Mandiri, kembali ke Gradebook / Bank Soal --}}
                <a href="{{ route('submissions.gradebook') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 font-medium flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Rekapitulasi
                </a>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">Waktu Pengumpulan</th>
                                    <th class="px-6 py-4">File Project</th>
                                    <th class="px-6 py-4">Status & Nilai</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($submissions as $sub)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold">
                                                {{ substr($sub->user->name, 0, 1) }}
                                            </div>
                                            {{ $sub->user->name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $sub->updated_at->format('d M Y, H:i') }}
                                        <div class="text-xs text-gray-400">{{ $sub->updated_at->diffForHumans() }}</div>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{-- Link Download Project --}}
                                        @if($sub->project_file_path)
                                        <a href="{{ route('submissions.download', $sub->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Download .sb3
                                        </a>
                                        @else
                                        <span class="text-red-500 text-xs italic">File Hilang/Rusak</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        @if($sub->status == 'graded')
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Selesai</span>
                                            <span class="font-bold text-lg text-gray-800 dark:text-white">{{ $sub->score }}</span>
                                        </div>
                                        @if($sub->feedback)
                                        <p class="text-xs text-gray-500 italic mt-1 truncate max-w-xs">"{{ $sub->feedback }}"</p>
                                        @endif
                                        @else
                                        <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Menunggu Penilaian</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <button
                                            onclick="openGradeModal({{ $sub->id }}, '{{ $sub->user->name }}', {{ $sub->score ?? 'null' }}, `{{ $sub->feedback ?? '' }}`)"
                                            class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded shadow transition">
                                            {{ $sub->status == 'graded' ? 'Edit Nilai' : 'Beri Nilai' }}
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada siswa yang mengumpulkan tugas ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PENILAIAN --}}
    <div id="gradeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeGradeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <form id="gradeForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                            Nilai Tugas: <span id="studentName" class="font-bold text-indigo-600"></span>
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="score" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Skor (0-100)</label>
                                <input type="number" name="score" id="score" min="0" max="100" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label for="feedback" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Feedback / Pesan untuk Siswa</label>
                                <textarea name="feedback" id="feedback" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Contoh: Logika loop sudah bagus, tapi perhatikan posisi blok..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Nilai
                        </button>
                        <button type="button" onclick="closeGradeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openGradeModal(submissionId, studentName, currentScore, currentFeedback) {
            // Set Action URL form secara dinamis
            const form = document.getElementById('gradeForm');
            form.action = `/submissions/${submissionId}/grade`;

            // Isi data ke dalam modal
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('score').value = currentScore !== null ? currentScore : '';
            document.getElementById('feedback').value = currentFeedback;

            // Tampilkan modal
            document.getElementById('gradeModal').classList.remove('hidden');
        }

        function closeGradeModal() {
            document.getElementById('gradeModal').classList.add('hidden');
        }
    </script>
</x-app-layout>