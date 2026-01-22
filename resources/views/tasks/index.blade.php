@foreach($tasks as $task)
@php
$submission = $task->submissions->first();

// Cek Status Keterlambatan
$isLate = false;
$deadlineText = '';
$deadlineColor = 'text-gray-500';

if ($task->deadline) {
if ($submission) {
// Jika sudah kumpul, cek apakah kumpulnya telat
$isLate = $submission->created_at->gt($task->deadline);
} else {
// Jika belum kumpul, cek apakah sekarang sudah lewat deadline
$isLate = now()->gt($task->deadline);
}

// Format teks deadline
$deadlineText = $task->deadline->isoFormat('D MMM Y, HH:mm');

if ($isLate) {
$deadlineColor = 'text-red-600 font-bold';
} elseif (!$submission) {
// Belum kumpul & belum telat -> Tampilkan sisa waktu
$deadlineText .= ' (' . $task->deadline->diffForHumans() . ')';
}
}

// Tentukan Status Badge
if (!$submission) {
$statusBadge = $isLate
? '<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-[10px] font-bold">Terlewat</span>'
: '<span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-[10px] font-bold">Belum Dikerjakan</span>';
$borderColor = $isLate ? 'border-red-200' : 'border-gray-200';
} elseif ($submission->status == 'submitted') {
$statusBadge = '<span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-[10px] font-bold">Menunggu Nilai</span>';
$borderColor = 'border-yellow-300 ring-2 ring-yellow-50';
} else {
$statusBadge = '<span class="px-2 py-1 bg-green-100 text-green-700 rounded text-[10px] font-bold">Selesai</span>';
$borderColor = 'border-green-300';
}
@endphp

<div class="bg-white rounded-xl shadow-sm border {{ $borderColor }} p-6 flex flex-col hover:shadow-md transition duration-200 relative overflow-hidden">

    @if($isLate)
    <div class="absolute top-0 right-0 w-16 h-16">
        <div class="absolute transform rotate-45 bg-red-500 text-center text-white font-semibold py-1 left-[-35px] top-[32px] w-[170px] text-[10px] shadow-sm">
            {{ $submission ? 'Telat Kumpul' : 'Lewat Deadline' }}
        </div>
    </div>
    @endif

    <div class="flex justify-between items-start mb-3">
        <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-500 bg-indigo-50 px-2 py-1 rounded">
            {{ $task->module ? 'BAB ' . $task->module->order : 'MANDIRI' }}
        </span>
        {!! $statusBadge !!}
    </div>

    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $task->title }}</h3>

    @if($task->deadline)
    <div class="flex items-center gap-1 text-xs mb-3 {{ $deadlineColor }}">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Batas: {{ $deadlineText }}</span>
    </div>
    @endif

    <p class="text-sm text-gray-500 line-clamp-2 mb-4 flex-1">
        {{ $task->instruction }}
    </p>

    <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
        @if($submission && $submission->score !== null)
        <div class="text-sm font-bold text-green-600">
            Nilai: {{ $submission->score }}/100
        </div>
        @else
        <div class="text-xs text-gray-400">
            {{ $task->starter_project_path ? '📎 Ada File' : '' }}
        </div>
        @endif

        <a href="{{ route('workspace.show', $task->id) }}" class="inline-flex items-center text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
            {{ $submission ? 'Lihat / Revisi' : 'Kerjakan' }} &rarr;
        </a>
    </div>
</div>
@endforeach