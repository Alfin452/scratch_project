<x-app-layout>
    <x-slot name="header">Master Data Kelas</x-slot>
    <x-slot name="subHeader">Kelola data kelas siswa untuk mempermudah pemantauan progres belajar</x-slot>

    <div class="max-w-7xl mx-auto" x-data="{ 
        addModalOpen: false, 
        editModalOpen: false, 
        deleteModalOpen: false,
        classroomToEdit: { id: '', name: '' },
        classroomToDelete: { id: '', name: '', studentsCount: 0 }
    }">

        {{-- Top Actions Bar --}}
        <div class="flex justify-between items-center mb-6">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                Total: <span class="font-bold text-gray-800 dark:text-white">{{ $classrooms->count() }}</span> Kelas Terdaftar
            </div>
            
            <button @click="addModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition transform active:scale-95 duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kelas Baru
            </button>
        </div>

        {{-- Classrooms Grid/List --}}
        @if($classrooms->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Nama Kelas</th>
                            <th class="px-6 py-4">Jumlah Siswa</th>
                            <th class="px-6 py-4">Dibuat Pada</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach ($classrooms as $class)
                        <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            {{-- Nama Kelas --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 flex items-center justify-center font-black text-sm border border-indigo-100 dark:border-indigo-800/50">
                                        {{ substr($class->name, 0, 2) }}
                                    </div>
                                    <span class="font-bold text-gray-800 dark:text-white">{{ $class->name }}</span>
                                </div>
                            </td>

                            {{-- Jumlah Siswa --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-100 dark:border-blue-800/50">
                                    👥 {{ $class->students_count }} Siswa
                                </span>
                            </td>

                            {{-- Dibuat Pada --}}
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 text-xs">
                                {{ $class->created_at->format('d M Y, H:i') }}
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4 text-right space-x-1 whitespace-nowrap">
                                <button @click="
                                    classroomToEdit = { id: '{{ $class->id }}', name: '{{ addslashes($class->name) }}' }; 
                                    editModalOpen = true;
                                " class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700 rounded-lg transition duration-200">
                                    ✏️ Edit
                                </button>
                                <button @click="
                                    classroomToDelete = { id: '{{ $class->id }}', name: '{{ addslashes($class->name) }}', studentsCount: {{ $class->students_count }} };
                                    deleteModalOpen = true;
                                " class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800/30 rounded-lg transition duration-200">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-16 text-center shadow-sm">
            <div class="flex flex-col items-center gap-4">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Belum Ada Kelas</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">Tambahkan data kelas master terlebih dahulu agar siswa dapat memilih kelasnya saat masuk sistem.</p>
                </div>
                <button @click="addModalOpen = true" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition duration-200 shadow-md">
                    Tambah Kelas Pertama
                </button>
            </div>
        </div>
        @endif

        {{-- ======================================================== --}}
        {{-- MODAL: TAMBAH KELAS --}}
        {{-- ======================================================== --}}
        <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="addModalOpen = false"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700 overflow-hidden transform transition-all">
                    <div class="flex items-center gap-3 p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-9 h-9 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/40 rounded-xl text-indigo-700 dark:text-indigo-400 font-bold">
                            🏫
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white">Tambah Kelas Baru</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Buat data master kelas baru</p>
                        </div>
                        <button @click="addModalOpen = false" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">×</button>
                    </div>
                    <form action="{{ route('classrooms.store') }}" method="POST">
                        @csrf
                        <div class="p-5 space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm"
                                    placeholder="Contoh: XI RPL 1 atau XII TKJ 2">
                                <p class="mt-1.5 text-xs text-gray-400">Pastikan nama kelas unik dan mudah dikenali oleh siswa.</p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-600/20 transition">Simpan Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- MODAL: EDIT KELAS --}}
        {{-- ======================================================== --}}
        <div x-show="editModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="editModalOpen = false"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700 overflow-hidden transform transition-all">
                    <div class="flex items-center gap-3 p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-9 h-9 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/40 rounded-xl text-indigo-700 dark:text-indigo-400 font-bold">
                            ✏️
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white">Edit Nama Kelas</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Perbarui nama data kelas master</p>
                        </div>
                        <button @click="editModalOpen = false" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">×</button>
                    </div>
                    <form :action="'/classrooms/' + classroomToEdit.id" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="p-5 space-y-4">
                            <div>
                                <label for="edit_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="edit_name" x-model="classroomToEdit.name" required
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-lg shadow-indigo-600/20 transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- MODAL: KONFIRMASI HAPUS --}}
        {{-- ======================================================== --}}
        <div x-show="deleteModalOpen" x-transition.opacity class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="deleteModalOpen = false"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 dark:border-gray-700 overflow-hidden transform transition-all">
                    <div class="flex items-center gap-3 p-5 border-b border-gray-200 dark:border-gray-700">
                        <div class="w-9 h-9 flex items-center justify-center bg-red-100 dark:bg-red-900/40 rounded-xl text-red-600 dark:text-red-400 font-bold">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 dark:text-white">Konfirmasi Hapus Kelas</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tindakan ini tidak bisa dibatalkan</p>
                        </div>
                        <button @click="deleteModalOpen = false" class="ml-auto w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition">×</button>
                    </div>
                    <form :action="'/classrooms/' + classroomToDelete.id" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="p-5 space-y-3">
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                Apakah Anda yakin ingin menghapus kelas <span class="font-black text-red-600 dark:text-red-400" x-text="classroomToDelete.name"></span>?
                            </p>
                            
                            <template x-if="classroomToDelete.studentsCount > 0">
                                <div class="p-3.5 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-900/50 rounded-xl text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                    <strong>Peringatan!</strong> Ada <span class="font-bold" x-text="classroomToDelete.studentsCount"></span> siswa di dalam kelas ini. Jika kelas dihapus, para siswa tersebut akan kehilangan asosiasi kelasnya (kembali ke status tanpa kelas) dan diminta memilih kelas kembali saat masuk.
                                </div>
                            </template>
                        </div>
                        <div class="flex justify-end gap-3 px-5 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/30">
                            <button type="button" @click="deleteModalOpen = false" class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition">Batal</button>
                            <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-600/20 transition">Ya, Hapus Kelas</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
