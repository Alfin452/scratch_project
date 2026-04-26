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

                <button @click="type='multiple_choice'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-pink-500 dark:hover:border-pink-500 hover:shadow-xl hover:shadow-pink-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-pink-100 dark:bg-pink-900/40 rounded-2xl mb-4 group-hover:bg-pink-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-pink-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Pilihan Ganda <span class="text-xs font-semibold bg-pink-100 text-pink-700 dark:bg-pink-900/50 dark:text-pink-400 px-2 py-0.5 rounded-full ml-1">Baru!</span></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa memilih satu jawaban benar dari beberapa opsi yang disediakan.</p>
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
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Pecah Masalah</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa belajar dekomposisi dengan memecah masalah besar menjadi bagian kecil.</p>
                </button>

                <button @click="type='classification'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-fuchsia-500 dark:hover:border-fuchsia-500 hover:shadow-xl hover:shadow-fuchsia-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-fuchsia-100 dark:bg-fuchsia-900/40 rounded-2xl mb-4 group-hover:bg-fuchsia-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-fuchsia-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Klasifikasi Ganda</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa mengklasifikasikan skenario ke dalam dua kategori pilihan (Benar/Salah).</p>
                </button>

                <button @click="type='simulation'"
                    class="group flex flex-col items-center p-8 bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-200 dark:border-gray-700 hover:border-orange-500 dark:hover:border-orange-500 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300 text-left">
                    <div class="w-16 h-16 flex items-center justify-center bg-orange-100 dark:bg-orange-900/40 rounded-2xl mb-4 group-hover:bg-orange-600 transition-colors duration-300">
                        <svg class="w-9 h-9 text-orange-600 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Simulasi Komputer <span class="text-xs font-semibold bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-400 px-2 py-0.5 rounded-full ml-1">Baru!</span></h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Siswa mensimulasikan instruksi untuk menggambar pada grid (Mode Programmer/Komputer).</p>
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

            {{-- === FORM SOAL KLASIFIKASI (MULTIPLE CHOICE) === --}}
            <div x-show="type==='classification'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-fuchsia-50 dark:bg-fuchsia-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-fuchsia-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Klasifikasi Ganda</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Siswa diminta memilih di antara dua kategori untuk setiap skenario</p>
                    </div>
                    <button type="button" @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-fuchsia-600 transition underline">Ganti Tipe</button>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="classification">

                        {{-- Info Dasar --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="title_cls" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Aktivitas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title_cls" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm"
                                    placeholder="Contoh: Berlatih 1 – Membedakan Algoritma dan Non-Algoritma">
                            </div>
                            <div class="md:col-span-2">
                                <label for="instruction_cls" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi untuk Siswa <span class="text-red-500">*</span></label>
                                <textarea name="instruction" id="instruction_cls" rows="3" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm"
                                    placeholder="Tentukan apakah kegiatan tersebut termasuk algoritma atau bukan algoritma dengan cara memilih jawaban yang tersedia.">{{ old('instruction') }}</textarea>
                            </div>
                            
                            {{-- Input Kategori Opsi Ganda --}}
                            <div class="md:col-span-2 bg-gray-50 dark:bg-gray-900/30 p-4 rounded-2xl border border-gray-200 dark:border-gray-700">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Definisi Pilihan Kategori <span class="text-red-500">*</span></h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_a" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Teks Tombol Kategori 1 (Misal: "Algoritma" / "Benar")</label>
                                        <input type="text" name="category_a" id="category_a" x-model="categoryA" required
                                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm"
                                            placeholder="Algoritma">
                                    </div>
                                    <div>
                                        <label for="category_b" class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Teks Tombol Kategori 2 (Misal: "Bukan Algoritma" / "Salah")</label>
                                        <input type="text" name="category_b" id="category_b" x-model="categoryB" required
                                            class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm"
                                            placeholder="Bukan Algoritma">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label for="deadline_cls" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_cls"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>
                            <div>
                                <label for="order_cls" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" id="order_cls" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm">
                            </div>
                        </div>

                        {{-- Daftar Soal Klasifikasi --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300">Daftar Pertanyaan / Skenario <span class="text-red-500">*</span></h4>
                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="questions.length + ' soal ditambahkan'"></span>
                            </div>

                            <template x-for="(question, qIdx) in questions" :key="qIdx">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-4 bg-gray-50 dark:bg-gray-700/50">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="flex items-center justify-center w-7 h-7 rounded-full bg-fuchsia-600 text-white text-xs font-bold" x-text="'Soal ' + (qIdx + 1)"></span>
                                        <button type="button" @click="questions.splice(qIdx, 1)" x-show="questions.length > 1"
                                            class="flex items-center justify-center w-8 h-8 rounded-lg text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Pernyataan/Kegiatan <span class="text-red-500">*</span></label>
                                            <input type="text" :name="'questions['+qIdx+'][text]'" x-model="question.text" required
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm text-sm"
                                                placeholder="Contoh: Menyusun langkah membuat mi instan">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jawaban Benar <span class="text-red-500">*</span></label>
                                            <div class="flex items-center gap-4 mt-2">
                                                <label class="inline-flex items-center">
                                                    <input type="radio" :name="'questions['+qIdx+'][answer]'" x-model="question.answer" :value="categoryA" required class="text-fuchsia-600 focus:ring-fuchsia-500">
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300" x-text="categoryA || 'Kategori 1'"></span>
                                                </label>
                                                <label class="inline-flex items-center">
                                                    <input type="radio" :name="'questions['+qIdx+'][answer]'" x-model="question.answer" :value="categoryB" required class="text-fuchsia-600 focus:ring-fuchsia-500">
                                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300" x-text="categoryB || 'Kategori 2'"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alasan / Penjelasan (Feedback) <span class="text-red-500">*</span></label>
                                            <textarea :name="'questions['+qIdx+'][explanation]'" x-model="question.explanation" rows="2" required
                                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-fuchsia-500 focus:ring-fuchsia-500 shadow-sm text-sm"
                                                placeholder="Benar, karena kegiatan ini memiliki langkah-langkah yang jelas, terurut, dan dapat diikuti."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" @click="addQuestion()"
                                class="w-full flex items-center justify-center gap-2 p-4 border-2 border-dashed border-fuchsia-300 dark:border-fuchsia-700 rounded-2xl text-fuchsia-600 dark:text-fuchsia-400 hover:bg-fuchsia-50 dark:hover:bg-fuchsia-900/20 hover:border-fuchsia-500 transition font-semibold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Pertanyaan
                            </button>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                            <button type="submit" :disabled="questions.length === 0 || !categoryA || !categoryB"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-fuchsia-600 rounded-xl hover:bg-fuchsia-700 shadow-lg hover:shadow-fuchsia-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Kuis
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === FORM SOAL PILIHAN GANDA === --}}
            <div x-show="type==='multiple_choice'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-pink-50 dark:bg-pink-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-pink-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Pilihan Ganda</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Buat pertanyaan dengan opsi jawaban dinamis</p>
                    </div>
                    <button type="button" @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-pink-600 transition underline">Ganti Tipe</button>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" class="space-y-6" id="mcForm">
                        @csrf
                        <input type="hidden" name="type" value="multiple_choice">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Kuis <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm"
                                    placeholder="Contoh: Kuis Pilihan Ganda Bab 1">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi untuk Siswa <span class="text-red-500">*</span></label>
                                <textarea name="instruction" rows="3" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm"
                                    placeholder="Contoh: Pilihlah satu jawaban yang paling tepat dari opsi yang tersedia.">{{ old('instruction') }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_mc"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm">
                            </div>
                        </div>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                            <h4 class="font-bold text-lg text-gray-800 dark:text-white mb-4">Daftar Pertanyaan</h4>
                            
                            <div class="space-y-6">
                                <template x-for="(q, qIndex) in mcQuestions" :key="q.id">
                                    <div class="p-5 border-2 border-gray-200 dark:border-gray-700 rounded-2xl relative bg-gray-50/50 dark:bg-gray-800/50">
                                        <button type="button" @click="removeMcQuestion(qIndex)" class="absolute -top-3 -right-3 w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-full hover:bg-red-500 hover:text-white transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>

                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                                Pertanyaan <span x-text="qIndex + 1"></span> <span class="text-red-500">*</span>
                                            </label>
                                            <textarea :name="'questions['+qIndex+'][text]'" x-model="q.text" rows="2" required
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm" placeholder="Tuliskan pertanyaan..."></textarea>
                                        </div>

                                        <div class="space-y-3">
                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">
                                                Opsi Jawaban & Kunci Benar <span class="text-red-500">*</span>
                                            </label>
                                            
                                            {{-- Input hidden untuk menyimpan indeks jawaban benar --}}
                                            <input type="hidden" :name="'questions['+qIndex+'][correct_option]'" :value="q.correctOption">
                                            
                                            <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                                <div class="flex items-center gap-3">
                                                    {{-- Tombol Radio untuk set kunci jawaban --}}
                                                    <button type="button" @click="q.correctOption = optIndex"
                                                        class="w-8 h-8 rounded-full border-2 flex items-center justify-center transition-colors shrink-0"
                                                        :class="q.correctOption === optIndex ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-gray-300 dark:border-gray-600 hover:border-emerald-400 dark:hover:border-emerald-500'">
                                                        <svg x-show="q.correctOption === optIndex" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                    
                                                    <div class="flex-grow flex items-center">
                                                        <span class="w-8 text-center font-bold text-gray-500 dark:text-gray-400 shrink-0" x-text="String.fromCharCode(65 + optIndex) + '.'"></span>
                                                        <input type="text" :name="'questions['+qIndex+'][options]['+optIndex+']'" x-model="q.options[optIndex]" required
                                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-pink-500 focus:ring-pink-500 shadow-sm text-sm" 
                                                            :class="q.correctOption === optIndex ? 'border-emerald-400 ring-1 ring-emerald-400 dark:border-emerald-500 dark:ring-emerald-500' : ''"
                                                            placeholder="Tulis opsi jawaban...">
                                                    </div>
                                                    
                                                    <button type="button" @click="removeMcOption(qIndex, optIndex)" x-show="q.options.length > 2" class="p-2 text-gray-400 hover:text-red-500 transition shrink-0">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button" @click="addMcOption(qIndex)" class="mt-3 text-xs font-semibold text-pink-600 dark:text-pink-400 hover:underline flex items-center ml-11">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Opsi Jawaban
                                        </button>

                                        <div x-show="q.correctOption === null" class="mt-3 text-xs text-red-500 font-semibold ml-11">
                                            ⚠️ Jangan lupa centang salah satu tombol bulat sebagai kunci jawaban benar!
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <button type="button" @click="addMcQuestion()"
                                class="mt-6 w-full flex items-center justify-center gap-2 p-4 border-2 border-dashed border-pink-300 dark:border-pink-700 rounded-2xl text-pink-600 dark:text-pink-400 hover:bg-pink-50 dark:hover:bg-pink-900/20 hover:border-pink-500 transition font-semibold text-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Pertanyaan Kuis
                            </button>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                            <button type="submit" :disabled="mcQuestions.length === 0 || mcQuestions.some(q => q.correctOption === null)"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-pink-600 rounded-xl hover:bg-pink-700 shadow-lg hover:shadow-pink-500/30 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                Simpan Kuis Pilihan Ganda
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === FORM SOAL SIMULASI === --}}
            <div x-show="type==='simulation'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-3 p-6 border-b border-gray-200 dark:border-gray-700 bg-orange-50 dark:bg-orange-900/20">
                    <div class="w-10 h-10 flex items-center justify-center bg-orange-600 rounded-xl">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 dark:text-white">Soal Simulasi Instruksi Komputer</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Mesin interaktif menggambar pada grid.</p>
                    </div>
                    <button type="button" @click="type=null" class="ml-auto text-xs text-gray-500 hover:text-orange-600 transition underline">Ganti Tipe</button>
                </div>
                <div class="p-6">
                    <form action="{{ route('tasks.store', $module->id) }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="type" value="simulation">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="title_sim" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Judul Aktivitas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title_sim" required value="{{ old('title') }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500 shadow-sm"
                                    placeholder="Contoh: Aktivitas 3 – Simulasi Instruksi Komputer">
                            </div>
                            <div class="md:col-span-2">
                                <label for="instruction_sim" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Instruksi Awal (Opsional)</label>
                                <textarea name="instruction" id="instruction_sim" rows="3" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500 shadow-sm"
                                    placeholder="Kerjakan simulasi ini baik sebagai Programmer maupun Komputer.">{{ old('instruction', 'Selesaikan latihan simulasi instruksi berikut dengan memilih peran sebagai Programmer maupun Komputer.') }}</textarea>
                            </div>
                            
                            <div>
                                <label for="deadline_sim" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Batas Waktu (Opsional)</label>
                                <input type="text" name="deadline" id="deadline_sim"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500 shadow-sm"
                                    placeholder="Pilih tanggal dan jam...">
                            </div>
                            <div>
                                <label for="order_sim" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="order" id="order_sim" required value="{{ old('order', $nextOrder) }}"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-orange-500 focus:ring-orange-500 shadow-sm">
                            </div>
                        </div>

                        <div class="p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-xl">
                            <p class="text-sm text-orange-700 dark:text-orange-400"><strong>Catatan:</strong> Mesin permainan (engine) simulasi bersifat otomatis (hardcoded) seperti di screenshot referensi. Kamu tidak perlu menyusun konten soal secara manual.</p>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('modules.show', $module->id) }}" class="px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 transition">Batal</a>
                            <button type="submit"
                                class="px-6 py-2.5 text-sm font-semibold text-white bg-orange-600 rounded-xl hover:bg-orange-700 shadow-lg hover:shadow-orange-500/30 transition">
                                Simpan Simulasi
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

                // For Classification task
                categoryA: 'Algoritma',
                categoryB: 'Bukan Algoritma',
                questions: [
                    {text: '', answer: 'Algoritma', explanation: ''}
                ],

                addActivity() {
                    const icons = this.availableIcons;
                    const icon = icons[this.activities.length % icons.length];
                    this.activities.push({
                        name: '',
                        icon: icon,
                        steps: ['', '', ''],
                        showIconPicker: false
                    });
                },
                
                addQuestion() {
                    this.questions.push({
                        text: '',
                        answer: this.categoryA || '',
                        explanation: ''
                    });
                },

                // For Multiple Choice task
                mcQuestions: [
                    { 
                        id: Date.now(), 
                        text: '', 
                        options: ['', '', '', ''], 
                        correctOption: null 
                    }
                ],

                addMcQuestion() {
                    this.mcQuestions.push({
                        id: Date.now(),
                        text: '',
                        options: ['', '', '', ''],
                        correctOption: null
                    });
                },

                removeMcQuestion(index) {
                    if (this.mcQuestions.length > 1) {
                        this.mcQuestions.splice(index, 1);
                    }
                },

                addMcOption(qIndex) {
                    this.mcQuestions[qIndex].options.push('');
                },

                removeMcOption(qIndex, optIndex) {
                    const q = this.mcQuestions[qIndex];
                    if (q.options.length > 2) {
                        q.options.splice(optIndex, 1);
                        // Perbaiki correctOption jika terhapus atau bergeser
                        if (q.correctOption === optIndex) {
                            q.correctOption = null;
                        } else if (q.correctOption > optIndex) {
                            q.correctOption--;
                        }
                    }
                }
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Flatpickr untuk semua input deadline
            const deadlineInputs = ['#deadline_scratch', '#deadline_dd', '#deadline_dec', '#deadline_cls', '#deadline_sim', '#deadline_mc'];
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