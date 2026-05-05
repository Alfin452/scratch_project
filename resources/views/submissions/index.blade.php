<x-app-layout>
    <x-slot name="header">Penilaian Tugas</x-slot>
    <x-slot name="subHeader">{{ $task->title }}</x-slot>

    <div class="max-w-7xl mx-auto">

        {{-- Back Button + Task Badge --}}
        <div class="flex items-center gap-4 mb-6">
            @if($task->module_id)
            <a href="{{ route('modules.show', $task->module_id) }}" class="flex items-center gap-1.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Modul
            </a>
            @else
            <a href="{{ route('submissions.gradebook') }}" class="flex items-center gap-1.5 text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Rekapitulasi
            </a>
            @endif

            <span class="ml-auto inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold
                {{ $task->type === 'drag_and_drop' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400' : 
                   ($task->type === 'decomposition' ? 'bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400') }}">
                {{ $task->type === 'drag_and_drop' ? '🔀 Drag & Drop' : ($task->type === 'decomposition' ? '🧩 Pecah Masalah' : '💻 Scratch') }}
            </span>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Submission</p>
                <p class="text-3xl font-extrabold text-gray-800 dark:text-white">{{ $submissions->count() }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Sudah Dinilai</p>
                <p class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $submissions->where('status', 'graded')->count() }}</p>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Menunggu Nilai</p>
                <p class="text-3xl font-extrabold text-amber-500 dark:text-amber-400">{{ $submissions->where('status', 'submitted')->count() }}</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Nama Siswa</th>
                            <th class="px-6 py-4">Waktu Pengumpulan</th>
                            <th class="px-6 py-4">
                                {{ in_array($task->type, ['drag_and_drop', 'decomposition']) ? 'Jawaban' : 'File Project' }}
                            </th>
                            <th class="px-6 py-4">Status & Nilai</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($submissions as $sub)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            {{-- Nama Siswa --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ substr($sub->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-white">{{ $sub->user->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sub->user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Waktu --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">
                                <p>{{ $sub->updated_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">{{ $sub->updated_at->format('H:i') }} · {{ $sub->updated_at->diffForHumans() }}</p>
                            </td>

                            {{-- File / Jawaban --}}
                            <td class="px-6 py-4">
                                @if($task->type === 'drag_and_drop' || $task->type === 'decomposition')
                                    @if($sub->answer_data && count($sub->answer_data) > 0)
                                    <button onclick="openAnswerModal({{ $sub->id }}, '{{ $task->type }}')"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg {{ $task->type === 'decomposition' ? 'bg-sky-50 dark:bg-sky-900/20 text-sky-700 dark:text-sky-400 border-sky-200 dark:border-sky-800' : 'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800' }} border text-xs font-semibold hover:opacity-80 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        Lihat Jawaban
                                    </button>

                                    {{-- Hidden data for modal --}}
                                    <script id="answer-data-{{ $sub->id }}" type="application/json">@json($sub->answer_data)</script>
                                    @else
                                    <span class="text-xs text-gray-400 italic">Belum ada jawaban</span>
                                    @endif
                                @else
                                @if($task->type === 'scratch')
                                    @if($sub->project_file_path)
                                    <a href="{{ route('submissions.download', $sub->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800 text-xs font-semibold hover:bg-indigo-100 dark:hover:bg-indigo-900/40 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download .sb3
                                    </a>
                                    @else
                                    <span class="text-xs text-red-400 italic">File hilang</span>
                                    @endif
                                @endif
                            </td>

                            {{-- Status & Nilai --}}
                            <td class="px-6 py-4">
                                @if($sub->status === 'graded')
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400">✓ Dinilai</span>
                                    <span class="text-2xl font-extrabold text-gray-800 dark:text-white">{{ $sub->score }}</span>
                                    <span class="text-xs text-gray-400">/100</span>
                                </div>
                                @if($sub->feedback)
                                <p class="text-xs text-gray-500 dark:text-gray-400 italic mt-1 line-clamp-1">"{{ $sub->feedback }}"</p>
                                @endif
                                @else
                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">⏳ Menunggu</span>
                                @endif
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-center">
                                <button
                                    onclick="openGradeModal({{ $sub->id }}, '{{ addslashes($sub->user->name) }}', {{ $sub->score ?? 'null' }}, `{{ addslashes($sub->feedback ?? '') }}`)"
                                    class="px-4 py-2 rounded-lg text-xs font-bold text-white shadow-sm transition
                                        {{ $sub->status === 'graded' ? 'bg-gray-500 hover:bg-gray-600' : 'bg-indigo-600 hover:bg-indigo-700' }}">
                                    {{ $sub->status === 'graded' ? 'Edit Nilai' : 'Beri Nilai' }}
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M12 12h.01M12 16h.01"/></svg>
                                    <p class="font-semibold">Belum ada siswa yang mengumpulkan tugas ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ======================================================== --}}
    {{-- MODAL: LIHAT JAWABAN DRAG & DROP --}}
    {{-- ======================================================== --}}
    <div id="answerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeAnswerModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-xl max-h-[80vh] flex flex-col overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3 p-5 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                    <div class="w-9 h-9 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/40 rounded-xl text-emerald-700 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Jawaban Siswa</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400" id="answerModalStudent"></p>
                    </div>
                    <button onclick="closeAnswerModal()" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">×</button>
                </div>
                <div class="overflow-y-auto p-5 space-y-4 flex-1" id="answerModalContent"></div>
            </div>
        </div>
    </div>

    </div>



    {{-- ======================================================== --}}
    {{-- MODAL: PENILAIAN --}}
    {{-- ======================================================== --}}
    <div id="gradeModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" onclick="closeGradeModal()"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="flex items-center gap-3 p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="w-9 h-9 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/40 rounded-xl text-indigo-700 dark:text-indigo-400">
                        ✏️
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Beri Nilai</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Siswa: <span id="studentName" class="font-semibold text-indigo-600 dark:text-indigo-400"></span></p>
                    </div>
                    <button onclick="closeGradeModal()" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">×</button>
                </div>
                <form id="gradeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-5 space-y-4">
                        <div>
                            <label for="score" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nilai (0 – 100) <span class="text-red-500">*</span></label>
                            <input type="number" name="score" id="score" min="0" max="100" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 text-2xl font-bold shadow-sm"
                                placeholder="Contoh: 85">
                        </div>
                        <div>
                            <label for="feedback" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Pesan / Feedback untuk Siswa (Opsional)</label>
                            <textarea name="feedback" id="feedback" rows="4"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                placeholder="Contoh: Urutan algoritma sudah tepat, terus semangat belajar!"></textarea>
                            <p class="mt-1 text-xs text-gray-400">Pesan ini akan tersampaikan ke siswa di halaman aktivitasnya.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                        <button type="button" onclick="closeGradeModal()" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-600/20 transition">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // === MODAL JAWABAN ===
        function openAnswerModal(submissionId, type) {
            const raw = document.getElementById('answer-data-' + submissionId);
            if (!raw) return;
            const data = JSON.parse(raw.textContent);

            const content = document.getElementById('answerModalContent');
            content.innerHTML = '';

            if (type === 'decomposition') {
                // Render Decomposition Data
                const decomp = data.decomposition || [];
                const focus = data.focus || {};

                // Bagian 1: Hasil Dekomposisi
                let decompHtml = decomp.map(d => `<div class="flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-700"><span class="text-xl">${d.icon}</span><span class="text-xs font-bold text-gray-700 dark:text-gray-300">${d.name}</span></div>`).join('');
                
                content.innerHTML += `
                    <div class="space-y-4">
                        <section>
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-sky-500 mb-3">1. Hasil Dekomposisi (${decomp.length} sub-tugas)</h4>
                            <div class="grid grid-cols-2 gap-2">${decompHtml}</div>
                        </section>
                        <hr class="border-gray-100 dark:border-gray-700">
                        <section>
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-sky-500 mb-3">2. Fokus & Algoritma</h4>
                            <div class="p-4 bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 rounded-2xl">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="text-3xl">${focus.icon || '📌'}</span>
                                    <span class="font-black text-gray-900 dark:text-white capitalize">${focus.name || 'Pilihan'}</span>
                                </div>
                                <ol class="space-y-2">
                                    ${(focus.algorithm || []).map((step, idx) => `
                                        <li class="flex items-start gap-3">
                                            <span class="flex-shrink-0 w-5 h-5 flex items-center justify-center rounded-full bg-white dark:bg-gray-800 text-sky-600 font-bold text-[10px] border border-sky-100 dark:border-sky-700">${idx+1}</span>
                                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-tight">${step}</p>
                                        </li>
                                    `).join('')}
                                </ol>
                            </div>
                        </section>
                    </div>
                `;
            } else {
                // Render Drag & Drop Data
                data.forEach((activity, i) => {
                    const correctClass = activity.correct
                        ? 'bg-emerald-50 dark:bg-emerald-900/20 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400'
                        : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-700 dark:text-red-400';
                    const badge = activity.correct ? '✓ Benar' : '✗ Perlu Ditinjau';

                    let stepsHtml = (activity.answer || []).map((step, j) => `
                        <li class="flex items-start gap-2 text-sm">
                            <span class="flex-shrink-0 w-5 h-5 mt-0.5 flex items-center justify-center rounded-full ${activity.correct ? 'bg-emerald-200 dark:bg-emerald-800 text-emerald-700 dark:text-emerald-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400'} text-xs font-bold">${j+1}</span>
                            <span class="text-gray-700 dark:text-gray-300">${step}</span>
                        </li>
                    `).join('');

                    content.innerHTML += `
                        <div class="border rounded-xl overflow-hidden border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 p-3 bg-gray-50 dark:bg-gray-700/50">
                                <span class="text-xl">${activity.icon || '📌'}</span>
                                <span class="font-bold text-gray-800 dark:text-white text-sm">${activity.name}</span>
                                <span class="ml-auto text-xs font-bold px-2.5 py-1 rounded-full border ${correctClass}">${badge}</span>
                            </div>
                            <div class="p-3">
                                <ol class="space-y-1.5">${stepsHtml}</ol>
                            </div>
                        </div>
                    `;
                });
            }

            document.getElementById('answerModal').classList.remove('hidden');
        }

        function closeAnswerModal() {
            document.getElementById('answerModal').classList.add('hidden');
        }

        // === MODAL PENILAIAN ===
        function openGradeModal(submissionId, studentName, currentScore, currentFeedback) {
            const form = document.getElementById('gradeForm');
            form.action = `/submissions/${submissionId}/grade`;
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('score').value = currentScore !== null ? currentScore : '';
            document.getElementById('feedback').value = currentFeedback || '';
            document.getElementById('gradeModal').classList.remove('hidden');
        }

        function closeGradeModal() {
            document.getElementById('gradeModal').classList.add('hidden');
        }
    </script>
</x-app-layout>