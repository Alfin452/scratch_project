<x-app-layout>
    <x-slot name="header">Tambah Soal</x-slot>
    <x-slot name="subHeader">Buat soal baru untuk modul: {{ $module->title }}</x-slot>

    <div class="max-w-4xl mx-auto">

        {{-- Breadcrumb --}}
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1">
                <li>
                    <a href="{{ route('modules.show', $module->id) }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        {{ Str::limit($module->title, 25) }}
                    </a>
                </li>
                <li><span class="mx-2 text-gray-400">/</span></li>
                <li><span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tambah Soal</span></li>
            </ol>
        </nav>

        @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl text-red-700 dark:bg-red-900/20 dark:text-red-400">
            <p class="font-bold mb-1">Gagal Menyimpan!</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif

        {{-- Step 1: Pilih Tipe Soal --}}
        <div x-data="taskCreator()" x-cloak>

            {{-- Tipe Selector (jika belum dipilih) --}}
            <div x-show="!type" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <button @click="type='scratch'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/40 rounded-2xl mb-4 group-hover:bg-indigo-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-indigo-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Soal Scratch</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa mengerjakan di editor Scratch dan mengumpulkan file .sb3</p>
                </button>

                <button @click="type='drag_and_drop'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-emerald-100 dark:bg-emerald-900/40 rounded-2xl mb-4 group-hover:bg-emerald-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-emerald-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Susun Algoritma</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa memilih kegiatan lalu menyusun langkah-langkahnya menggunakan drag & drop</p>
                </button>

                <button @click="type='decomposition'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-sky-500 dark:hover:border-sky-500 hover:shadow-xl hover:shadow-sky-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-sky-100 dark:bg-sky-900/40 rounded-2xl mb-4 group-hover:bg-sky-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-sky-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Pecah Masalah <span class="text-xs font-semibold bg-sky-100 text-sky-700 dark:bg-sky-900/50 dark:text-sky-400 px-2 py-0.5 rounded-full ml-1">Baru!</span></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa belajar dekomposisi dengan memecah masalah besar menjadi bagian kecil.</p>
                </button>
            </div>

            {{-- === FORM SOAL SCRATCH === --}}
            <div x-show="type==='scratch'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-indigo-50 dark:bg-indigo-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-indigo-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Scratch</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Siswa mengerjakan di editor Scratch</p>
                    </div>
                    <button @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-indigo-600 transition underline">Ganti Tipe</button>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        <input type="hidden" name="type" value="scratch">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="title_scratch" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Tugas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title_scratch" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="Contoh: Latihan 1 – Kucing Berjalan">
                            </div>

                            <div class="md:col-span-2">
                                <label for="instruction_scratch" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi Pengerjaan <span class="text-red-500">*</span></label>
                                <textarea name="instruction" id="instruction_scratch" rows="4" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="Jelaskan langkah-langkah yang harus dilakukan siswa...">{{ old('instruction') }}</textarea>
                            </div>

                            <div>
                                <label for="deadline_scratch" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_scratch"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>

                            <div>
                                <label for="order_scratch" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" id="order_scratch" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">File Starter Project (Opsional)</label>
                            <label for="starter_file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:border-gray-600 transition">
                                <div class="flex flex-col items-center">
                                    <svg class="w-7 h-7 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                                    <p class="text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">Klik untuk upload</span></p>
                                    <p class="text-xs text-gray-400">File Scratch (.sb3)</p>
                                </div>
                                <input id="starter_file" name="starter_file" type="file" class="hidden" accept=".sb3"
                                    onchange="this.parentElement.querySelector('p').textContent = this.files[0]?.name || 'Klik untuk upload'">
                            </label>
                        </div>

                        <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 hover:dark:bg-gray-600 transition">Batal</a>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-lg hover:shadow-indigo-500/30 transition">Simpan Tugas</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === FORM SOAL DRAG & DROP === --}}
            <div x-show="type==='drag_and_drop'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-emerald-50 dark:bg-emerald-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-emerald-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Susun Algoritma (Drag & Drop)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Siswa memilih kegiatan dan menyusun langkah yang benar</p>
                    </div>
                    <button @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-emerald-600 transition underline">Ganti Tipe</button>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="drag_and_drop">

                        {{-- Info Dasar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="title_dd" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Latihan <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title_dd" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="Contoh: Aktivitas 1 – Cari Algoritma di Sekitarmu">
                            </div>
                            <div class="md:col-span-2">
                                <label for="instruction_dd" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi untuk Siswa <span class="text-red-500">*</span></label>
                                <textarea name="instruction" id="instruction_dd" rows="3" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="Pilih dua kegiatan sehari-hari, lalu susun langkah-langkahnya agar menjadi algoritma yang benar.">{{ old('instruction') }}</textarea>
                            </div>
                            <div>
                                <label for="deadline_dd" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_dd"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>
                            <div>
                                <label for="order_dd" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" id="order_dd" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm">
                            </div>
                            <div>
                                <label for="pick_count" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Kegiatan yang Harus Dipilih Siswa <span class="text-red-500">*</span></label>
                                <input type="number" name="pick_count" id="pick_count" x-model="pickCount" min="1" required value="{{ old('pick_count', 2) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm"
                                    placeholder="Contoh: 2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Siswa wajib memilih sebanyak angka ini</p>
                            </div>
                        </div>

                        {{-- Daftar Kegiatan --}}
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Daftar Kegiatan <span class="text-red-500">*</span></h4>
                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="`${activities.length} kegiatan ditambahkan`"></span>
                            </div>

                            {{-- Note: pick_count harus <= jumlah kegiatan --}}
                            <template x-if="pickCount > activities.length && activities.length > 0">
                                <div class="mb-3 p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                                    ⚠️ Jumlah pilihan (<span x-text="pickCount"></span>) melebihi jumlah kegiatan (<span x-text="activities.length"></span>). Harap tambah kegiatan atau kurangi jumlah pilihan.
                                </div>
                            </template>

                            <div class="space-y-4">
                                <template x-for="(activity, aIdx) in activities" :key="aIdx">
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                                        {{-- Header Kegiatan --}}
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50">
                                            <span class="flex items-center justify-center w-7 h-7 rounded-full bg-emerald-600 text-white text-xs font-bold flex-shrink-0" x-text="aIdx + 1"></span>

                                            {{-- Pilih Ikon --}}
                                            <div class="flex-shrink-0 relative">
                                                <button type="button" @click.stop.prevent="activity.showIconPicker = !activity.showIconPicker"
                                                    class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-emerald-500 bg-white dark:bg-gray-800 text-xl transition"
                                                    :title="'Pilih ikon: ' + activity.icon">
                                                    <span x-text="activity.icon"></span>
                                                </button>
                                                {{-- Icon Picker Dropdown --}}
                                                <div x-show="activity.showIconPicker" @click.outside="activity.showIconPicker = false"
                                                    class="absolute z-20 mt-1 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl grid grid-cols-6 gap-1 w-60">
                                                    <template x-for="(ico, iIdx) in availableIcons" :key="`dd_ico_${aIdx}_${iIdx}`">
                                                        <button type="button" @click="activity.icon = ico; activity.showIconPicker = false"
                                                            class="flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-xl transition"
                                                            :class="activity.icon === ico ? 'bg-emerald-100 dark:bg-emerald-900/40' : ''"
                                                            x-text="ico"></button>
                                                    </template>
                                                </div>
                                                <input type="hidden" :name="`activities[${aIdx}][icon]`" :value="activity.icon">
                                            </div>

                                            <input type="text" :name="`activities[${aIdx}][name]`" x-model="activity.name" required
                                                class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm font-semibold"
                                                :placeholder="`Nama kegiatan ${aIdx + 1}, cth: Berangkat ke Sekolah`">

                                            <button type="button" @click="activities.splice(aIdx, 1)"
                                                class="flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition flex-shrink-0">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>

                                        {{-- Langkah-langkah --}}
                                        <div class="p-4 space-y-2">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-2">Langkah-langkah (Urutan yang Benar)</p>

                                            <template x-for="(step, sIdx) in activity.steps" :key="`dd_step_${aIdx}_${sIdx}`">
                                                <div class="flex items-center gap-2">
                                                    <span class="flex-shrink-0 w-6 h-6 flex items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-xs font-bold" x-text="sIdx + 1"></span>
                                                    <input type="text" :name="`activities[${aIdx}][steps][${sIdx}]`" x-model="activity.steps[sIdx]" required
                                                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm"
                                                        :placeholder="`Langkah ${sIdx + 1}...`">
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

                                {{-- Tombol Tambah Kegiatan --}}
                                <button type="button" @click="addActivity()"
                                    class="w-full flex items-center justify-center gap-2 p-4 border-2 border-dashed border-emerald-300 dark:border-emerald-700 rounded-2xl text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-500 transition font-semibold text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Tambah Kegiatan
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                            <button type="submit"
                                :disabled="pickCount > activities.length"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Latihan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            {{-- === FORM SOAL PECAH MASALAH (DECOMPOSITION) === --}}
            <div x-show="type==='decomposition'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-sky-50 dark:bg-sky-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-sky-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Pecah Masalah (Decomposition)</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Siswa belajar memecah masalah besar menjadi beberapa bagian kecil</p>
                    </div>
                    <button type="button" @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-sky-600 transition underline">Ganti Tipe</button>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="decomposition">

                        {{-- Info Dasar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="title_dec" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Aktivitas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title_dec" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                    placeholder="Contoh: Aktivitas 2 – Pecah Masalah 'Acara Kelas'">
                            </div>
                            <div class="md:col-span-2">
                                <label for="instruction_dec" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Latar Belakang / Instruksi Awal <span class="text-red-500">*</span></label>
                                <textarea name="instruction" id="instruction_dec" rows="3" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                    placeholder="Jelaskan skenario masalah utamanya di sini...">{{ old('instruction') }}</textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label for="main_description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Masalah Utama (The Big Problem) <span class="text-red-500">*</span></label>
                                <input type="text" name="main_description" id="main_description" required value="{{ old('main_description') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                    placeholder="Contoh: Mengadakan acara makan bersama kelas">
                            </div>
                            <div>
                                <label for="deadline_dec" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_dec"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>
                            <div>
                                <label for="order_dec" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" id="order_dec" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                            </div>
                            <div>
                                <label for="min_decomposition" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Minimal Pilihan Sub-tugas <span class="text-red-500">*</span></label>
                                <input type="number" name="min_decomposition" id="min_decomposition" x-model="minDecomposition" min="1" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Siswa minimal harus memilih sebanyak ini</p>
                            </div>
                        </div>

                        {{-- Daftar Sub-tugas --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Daftar Sub-tugas / Bagian Kecil <span class="text-red-500">*</span></h4>
                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="activities.length + ' pilihan tersedia'"></span>
                            </div>

                            <template x-if="minDecomposition > activities.length && activities.length > 0">
                                <div class="p-3 bg-amber-50 border border-amber-300 rounded-xl text-xs text-amber-800 dark:bg-amber-900/20 dark:text-amber-400">
                                    ⚠️ Minimal pilihan (<span x-text="minDecomposition"></span>) melebihi jumlah sub-tugas (<span x-text="activities.length"></span>).
                                </div>
                            </template>

                            <template x-for="(activity, aIdx) in activities" :key="aIdx">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-visible">
                                    {{-- Header Sub-tugas --}}
                                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-t-2xl">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-sky-600 text-white text-xs font-bold" x-text="aIdx + 1"></span>

                                        <div class="flex-shrink-0 relative">
                                            <button type="button" @click.stop.prevent="activity.showIconPicker = !activity.showIconPicker"
                                                class="flex items-center justify-center w-10 h-10 rounded-xl border-2 border-gray-300 dark:border-gray-600 hover:border-sky-500 bg-white dark:bg-gray-800 text-xl transition">
                                                <span x-text="activity.icon"></span>
                                            </button>
                                            <div x-show="activity.showIconPicker" @click.outside="activity.showIconPicker = false"
                                                class="absolute z-20 mt-1 p-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-xl grid grid-cols-6 gap-1 w-60">
                                                <template x-for="(ico, iIdx) in availableIcons" :key="`dec_ico_${aIdx}_${iIdx}`">
                                                    <button type="button" @click="activity.icon = ico; activity.showIconPicker = false"
                                                        class="flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-xl transition"
                                                        :class="activity.icon === ico ? 'bg-sky-100 dark:bg-sky-900/40' : ''"
                                                        x-text="ico"></button>
                                                </template>
                                            </div>
                                            <input type="hidden" :name="'activities['+aIdx+'][icon]'" :value="activity.icon">
                                        </div>

                                        <input type="text" :name="'activities['+aIdx+'][name]'" x-model="activity.name" required
                                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm text-sm font-semibold"
                                            placeholder="Nama sub-tugas, cth: Menentukan tanggal">

                                        <button type="button" @click="activities.splice(aIdx, 1)"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    {{-- Algoritma untuk Sub-tugas ini --}}
                                    <div class="p-4 space-y-2">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Algoritma untuk Sub-tugas ini</p>
                                        <template x-for="(step, sIdx) in activity.steps" :key="`dec_step_${aIdx}_${sIdx}`">
                                            <div class="flex items-center gap-2">
                                                <input type="text" :name="'activities['+aIdx+'][steps]['+sIdx+']'" x-model="activity.steps[sIdx]" required
                                                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-sky-500 focus:ring-sky-500 shadow-sm text-xs"
                                                    placeholder="Langkah pengerjaan...">
                                                <button type="button" @click="activity.steps.splice(sIdx, 1)" x-show="activity.steps.length > 1"
                                                    class="flex-shrink-0 w-6 h-6 flex items-center justify-center text-red-400 hover:text-red-600 transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="activity.steps.push('')"
                                            class="flex items-center gap-1.5 text-xs font-semibold text-sky-600 dark:text-sky-400 hover:text-sky-700 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                            Tambah Langkah
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

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                            <button type="submit" :disabled="minDecomposition > activities.length"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-sky-600 rounded-xl hover:bg-sky-700 shadow-lg hover:shadow-sky-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Aktivitas
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function taskCreator() {
            return {
                type: null,
                pickCount: 2,
                minDecomposition: 3,
                activities: [],
                availableIcons: ['🏃','🎒','🏫','💻','🍜','📱','🔌','📧','🛒','🍳','🚿','📚','✈️','🚗','🚌','🏊','⚽','🎮','🎵','📷','🧹','🛏️','🍎','💊','📝','🔦','🧺','🧴','📅','🍽️','📝','🛋️','🎨'],

                addActivity() {
                    const icons = this.availableIcons;
                    const icon = icons[this.activities.length % icons.length];
                    this.activities.push({
                        name: '',
                        icon: icon,
                        steps: ['', '', ''],
                        showIconPicker: false
                    });
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Flatpickr untuk semua input deadline
            const deadlineInputs = ['#deadline_scratch', '#deadline_dd', '#deadline_dec'];
            deadlineInputs.forEach(sel => {
                const el = document.querySelector(sel);
                if (el) {
                    flatpickr(el, {
                        enableTime: true,
                        dateFormat: 'Y-m-d H:i',
                        altInput: true,
                        altFormat: 'j F Y, H:i',
                        time_24hr: true,
                        minDate: 'today',
                        locale: 'id'
                    });
                }
            });
        });
    </script>
</x-app-layout>