<x-app-layout>
    <x-slot name="header">Edit Soal</x-slot>
    <x-slot name="subHeader">{{ $task->title }}</x-slot>

    <div class="max-w-4xl mx-auto">

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-700 dark:bg-red-900/20 dark:text-red-400">
            <p class="font-bold mb-1">Gagal Menyimpan!</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        @if($task->type === 'decomposition')
        {{-- === EDIT FORM DECOMPOSITION === --}}
        <div x-data="editDecomposition({{ json_encode($task->content) }})" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-sky-50 dark:bg-sky-900/20">
                <div class="w-10 h-10 flex items-center justify-center bg-sky-600 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white">Edit Soal Pecah Masalah</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tipe soal tidak dapat diubah setelah dibuat</p>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Aktivitas <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required value="{{ old('title', $task->title) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Latar Belakang / Instruksi Awal <span class="text-red-500">*</span></label>
                            <textarea name="instruction" rows="3" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">{{ old('instruction', $task->instruction) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Masalah Utama (The Big Problem) <span class="text-red-500">*</span></label>
                            <input type="text" name="main_description" required value="{{ old('main_description', $task->content['main_description'] ?? '') }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                placeholder="Contoh: Mengadakan acara makan bersama kelas">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                            <input type="text" name="deadline" id="deadline_edit"
                                value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d H:i') : '') }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                placeholder="Pilih tanggal dan jam...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                            <input type="number" name="order" required value="{{ old('order', $task->order) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Minimal Pilihan Sub-tugas <span class="text-red-500">*</span></label>
                            <input type="number" name="min_decomposition" x-model="minDecomposition" min="1" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                        </div>
                    </div>

                    {{-- Daftar Sub-tugas --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Daftar Sub-tugas / Bagian Kecil</h4>
                            <span class="text-xs text-gray-500 font-medium" x-text="`${activities.length} pilihan tersedia`"></span>
                        </div>

                        <template x-if="minDecomposition > activities.length && activities.length > 0">
                            <div class="mb-3 p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                                ⚠️ Minimal pilihan (<span x-text="minDecomposition"></span>) melebihi jumlah sub-tugas yang tersedia (<span x-text="activities.length"></span>).
                            </div>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(activity, aIdx) in activities" :key="aIdx">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-visible">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-t-2xl">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-sky-600 text-white text-xs font-bold flex-shrink-0" x-text="aIdx + 1"></span>

                                        <div class="flex-shrink-0 relative">
                                            <button type="button" @click="activity.showIconPicker = !activity.showIconPicker"
                                                class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-sky-500 bg-white dark:bg-gray-800 text-xl transition">
                                                <span x-text="activity.icon"></span>
                                            </button>
                                            <div x-show="activity.showIconPicker" @click.outside="activity.showIconPicker = false"
                                                class="absolute z-20 mt-1 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl grid grid-cols-6 gap-1 w-60">
                                                <template x-for="ico in availableIcons" :key="ico">
                                                    <button type="button" @click="activity.icon = ico; activity.showIconPicker = false"
                                                        class="flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-xl transition"
                                                        :class="activity.icon === ico ? 'bg-sky-100 dark:bg-sky-900/40' : ''"
                                                        x-text="ico"></button>
                                                </template>
                                            </div>
                                            <input type="hidden" :name="`activities[${aIdx}][icon]`" :value="activity.icon">
                                        </div>

                                        <input type="text" :name="`activities[${aIdx}][name]`" x-model="activity.name" required
                                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm text-sm font-semibold">

                                        <button type="button" @click="activities.splice(aIdx, 1)"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    <div class="p-4 space-y-2">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Algoritma untuk Sub-tugas ini</p>
                                        <template x-for="(step, sIdx) in activity.steps" :key="sIdx">
                                            <div class="flex items-center gap-2">
                                                <input type="text" :name="`activities[${aIdx}][steps][${sIdx}]`" x-model="activity.steps[sIdx]" required
                                                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm text-xs">
                                                <button type="button" @click="activity.steps.splice(sIdx, 1)" x-show="activity.steps.length > 1"
                                                    class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="activity.steps.push('')"
                                            class="flex items-center gap-1.5 text-xs font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-700 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Langkah Algoritma
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="addActivity()"
                                class="w-full flex items-center justify-center gap-2 p-4 border-2 border-dashed border-sky-300 dark:border-sky-700 rounded-2xl text-sky-600 dark:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-900/20 hover:border-sky-500 transition font-semibold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Sub-tugas Baru
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('modules.show', $task->module_id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                        <button type="submit" :disabled="minDecomposition > activities.length"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-sky-600 rounded-xl hover:bg-sky-700 shadow-lg hover:shadow-sky-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Update Aktivitas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @elseif($task->type === 'drag_and_drop')
        {{-- === EDIT FORM DRAG & DROP === --}}
        <div x-data="editDragDrop({{ json_encode($task->content) }})" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20">
                <div class="w-10 h-10 flex items-center justify-center bg-emerald-600 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white">Edit Soal Susun Algoritma</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tipe soal tidak dapat diubah setelah dibuat</p>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Latihan <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required value="{{ old('title', $task->title) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi untuk Siswa <span class="text-red-500">*</span></label>
                            <textarea name="instruction" rows="3" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">{{ old('instruction', $task->instruction) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                            <input type="text" name="deadline" id="deadline_edit"
                                value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d H:i') : '') }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                placeholder="Pilih tanggal dan jam...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                            <input type="number" name="order" required value="{{ old('order', $task->order) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Kegiatan yang Harus Dipilih <span class="text-red-500">*</span></label>
                            <input type="number" name="pick_count" x-model="pickCount" min="1" required
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                        </div>
                    </div>

                    {{-- Daftar Kegiatan --}}
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Daftar Kegiatan</h4>
                            <span class="text-xs text-gray-500" x-text="`${activities.length} kegiatan`"></span>
                        </div>

                        <template x-if="pickCount > activities.length && activities.length > 0">
                            <div class="mb-3 p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                                ⚠️ Jumlah pilihan (<span x-text="pickCount"></span>) melebihi jumlah kegiatan (<span x-text="activities.length"></span>).
                            </div>
                        </template>

                        <div class="space-y-4">
                            <template x-for="(activity, aIdx) in activities" :key="aIdx">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white text-xs font-bold flex-shrink-0" x-text="aIdx + 1"></span>

                                        <div class="flex-shrink-0 relative">
                                            <button type="button" @click="activity.showIconPicker = !activity.showIconPicker"
                                                class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-emerald-500 bg-white dark:bg-gray-800 text-xl transition">
                                                <span x-text="activity.icon"></span>
                                            </button>
                                            <div x-show="activity.showIconPicker" @click.outside="activity.showIconPicker = false"
                                                class="absolute z-20 mt-1 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl grid grid-cols-6 gap-1 w-60">
                                                <template x-for="ico in availableIcons" :key="ico">
                                                    <button type="button" @click="activity.icon = ico; activity.showIconPicker = false"
                                                        class="flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-xl transition"
                                                        :class="activity.icon === ico ? 'bg-emerald-100 dark:bg-emerald-900/40' : ''"
                                                        x-text="ico"></button>
                                                </template>
                                            </div>
                                            <input type="hidden" :name="`activities[${aIdx}][icon]`" :value="activity.icon">
                                        </div>

                                        <input type="text" :name="`activities[${aIdx}][name]`" x-model="activity.name" required
                                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm font-semibold">

                                        <button type="button" @click="activities.splice(aIdx, 1)"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    <div class="p-4 space-y-2">
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Langkah-langkah (Urutan yang Benar)</p>
                                        <template x-for="(step, sIdx) in activity.steps" :key="sIdx">
                                            <div class="flex items-center gap-2">
                                                <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold" x-text="sIdx + 1"></span>
                                                <input type="text" :name="`activities[${aIdx}][steps][${sIdx}]`" x-model="activity.steps[sIdx]" required
                                                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
                                                <button type="button" @click="activity.steps.splice(sIdx, 1)" x-show="activity.steps.length > 2"
                                                    class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 rounded transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="activity.steps.push('')"
                                            class="mt-1 flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Langkah
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="addActivity()"
                                class="w-full flex items-center justify-center gap-2 p-4 border-2 border-dashed border-emerald-300 dark:border-emerald-700 rounded-2xl text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 transition font-semibold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Kegiatan
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('modules.show', $task->module_id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                        <button type="submit" :disabled="pickCount > activities.length"
                            class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Update Latihan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @else
        {{-- === EDIT FORM SCRATCH === --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900/20">
                <div class="w-10 h-10 flex items-center justify-center bg-indigo-600 rounded-xl">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white">Edit Soal Scratch</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Siswa mengerjakan di editor Scratch</p>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title', $task->title) }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi Pengerjaan <span class="text-red-500">*</span></label>
                        <textarea name="instruction" rows="4" required
                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">{{ old('instruction', $task->instruction) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                            <input type="text" name="deadline" id="deadline_edit"
                                value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d H:i') : '') }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                placeholder="Pilih tanggal dan jam...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                            <input type="number" name="order" required value="{{ old('order', $task->order) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">File Starter Project</label>
                        @if($task->starter_project_path)
                        <div class="mb-3 flex items-center p-3 text-sm text-blue-800 border border-blue-300 rounded-xl bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800">
                            <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
                            <span class="font-medium">File saat ini tersedia.</span>&nbsp;Upload baru untuk mengganti.
                        </div>
                        @endif
                        <label for="starter_file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600 transition">
                            <div class="flex flex-col items-center">
                                <svg class="w-7 h-7 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk ganti file</span> (Opsional)</p>
                                <p class="text-xs text-gray-400">File Scratch (.sb3)</p>
                            </div>
                            <input id="starter_file" name="starter_file" type="file" class="hidden" accept=".sb3">
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('modules.show', $task->module_id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transition">Update Tugas</button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script>
        function editDecomposition(content) {
            return {
                minDecomposition: content?.min_decomposition ?? 3,
                activities: (content?.activities ?? []).map(a => ({
                    ...a,
                    showIconPicker: false
                })),
                availableIcons: ['🏃','🎒','🏫','💻','🍜','📱','🔌','📧','🛒','🍳','🚿','📚','✈️','🚗','🚌','🏊','⚽','🎮','🎵','📷','🧹','🛏️','🍎','💊','📝','🔦','🧺','🧴','📅','🍽️','📝','🛋️','🎨'],
                
                addActivity() {
                    const icons = this.availableIcons;
                    this.activities.push({
                        name: '',
                        icon: icons[this.activities.length % icons.length],
                        steps: ['', ''],
                        showIconPicker: false
                    });
                }
            };
        }

        function editDragDrop(content) {
            return {
                pickCount: content?.pick_count ?? 2,
                activities: (content?.activities ?? []).map(a => ({
                    ...a,
                    showIconPicker: false
                })),
                availableIcons: ['🏃','🎒','🏫','💻','🍜','📱','🔌','📧','🛒','🍳','🚿','📚','✈️','🚗','🚌','🏊','⚽','🎮','🎵','📷','🧹','🛏️','🍎','💊','📝','🔦','🧺','🧴'],

                addActivity() {
                    const icons = this.availableIcons;
                    this.activities.push({
                        name: '',
                        icon: icons[this.activities.length % icons.length],
                        steps: ['', '', ''],
                        showIconPicker: false
                    });
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const el = document.querySelector('#deadline_edit');
            if (el) {
                flatpickr(el, {
                    enableTime: true,
                    dateFormat: 'Y-m-d H:i',
                    altInput: true,
                    altFormat: 'j F Y, H:i',
                    time_24hr: true,
                    locale: 'id'
                });
            }
        });
    </script>
</x-app-layout>