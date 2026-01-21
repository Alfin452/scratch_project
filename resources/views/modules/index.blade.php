<x-dynamic-component :component="Auth::user()->isTeacher() ? 'app-layout' : 'student-layout'"> <x-slot name="header">
        <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pembelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">

                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Modul Materi</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kelola urutan dan konten pembelajaran untuk siswa.</p>
                    </div>

                    @if(Auth::user()->isTeacher())
                    <a href="{{ route('modules.create') }}"
                        class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 text-white text-sm font-medium rounded-xl transition duration-200 shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Materi Baru
                    </a>
                    @endif
                </div>

                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 dark:bg-gray-700/50 border-b border-gray-100 dark:border-gray-700">
                            <tr>
                                <th scope="col" class="px-8 py-5 font-semibold tracking-wider">Bab</th>
                                <th scope="col" class="px-6 py-5 font-semibold tracking-wider">Judul & Deskripsi</th>
                                <th scope="col" class="px-6 py-5 font-semibold tracking-wider text-center">Status</th>
                                <th scope="col" class="px-8 py-5 font-semibold tracking-wider text-right">Opsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($modules as $module)
                            <tr class="group hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 font-bold text-lg border border-indigo-100 dark:border-indigo-800">
                                        {{ $module->order }}
                                    </div>
                                </td>

                                <td class="px-6 py-6">
                                    <div class="flex flex-col">
                                        <a href="{{ route('modules.show', $module->id) }}" class="text-base font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors hover:underline">
                                            {{ $module->title }}
                                        </a>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs mt-1 line-clamp-1 max-w-md">
                                            {{ $module->description ?? 'Tidak ada deskripsi singkat.' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-6 text-center whitespace-nowrap">
                                    @if($module->is_active)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-700 border border-green-200 dark:bg-green-900/20 dark:text-green-400 dark:border-green-800">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                        Publik
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200 dark:bg-gray-700/50 dark:text-gray-400 dark:border-gray-600">
                                        <span class="w-1.5 h-1.5 mr-1.5 bg-gray-400 rounded-full"></span>
                                        Draft
                                    </span>
                                    @endif
                                </td>

                                <td class="px-8 py-6 text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('modules.show', $module->id) }}" class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition dark:hover:bg-gray-700 dark:hover:text-indigo-400" title="Lihat Materi">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        @if(Auth::user()->isTeacher())
                                        <a href="{{ route('modules.edit', $module->id) }}" class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition dark:hover:bg-gray-700 dark:hover:text-yellow-400" title="Edit Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                        </a>

                                        <button onclick="confirmDelete({{ $module->id }})" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition dark:hover:bg-gray-700 dark:hover:text-red-400" title="Hapus Permanen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $module->id }}" action="{{ route('modules.destroy', $module->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Belum ada Materi</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mt-1 max-w-sm">Mulai dengan menambahkan bab pertama untuk siswa Anda.</p>
                                        @if(Auth::user()->isTeacher())
                                        <a href="{{ route('modules.create') }}" class="mt-4 text-indigo-600 hover:text-indigo-800 font-medium dark:text-indigo-400 dark:hover:text-indigo-300">
                                            + Tambah Materi Sekarang
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/30 px-8 py-4 border-t border-gray-100 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center md:text-left">
                        Total Modul: <span class="font-bold">{{ $modules->count() }}</span>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Materi Ini?',
                text: "Data materi dan tugas di dalamnya akan hilang permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444', // Merah Tailwind
                cancelButtonColor: '#6b7280', // Abu Tailwind
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                borderRadius: '12px'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-dynamic-component>