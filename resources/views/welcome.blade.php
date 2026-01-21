<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AlgoLearn') }} - Belajar Algoritma</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-indigo-500 selection:text-white">

    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xl shadow-md">
                        A
                    </div>
                    <span class="font-bold text-xl text-indigo-900 tracking-tight">AlgoLearn</span>
                </div>

                <div class="flex items-center">
                    @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition">
                        Ke Dashboard &rarr;
                    </a>
                    @else
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-16 pb-20 lg:pt-32 lg:pb-28 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">

            <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                Belajar Coding <br class="hidden sm:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600">Jadi Lebih Seru!</span>
            </h1>

            <p class="mt-4 text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-10">
                Platform belajar algoritma interaktif untuk siswa SMP.
                Pahami logika pemrograman dengan mudah menggunakan visual blocks.
            </p>

            <div class="flex justify-center">
                @auth
                <a href="{{ url('/dashboard') }}" class="px-8 py-4 text-lg font-bold text-white bg-indigo-600 rounded-xl shadow-xl hover:bg-indigo-700 hover:shadow-2xl transition transform hover:-translate-y-1">
                    Lanjutkan Belajar
                </a>
                @else
                <a href="{{ route('auth.google') }}" class="group relative flex items-center justify-center gap-3 px-8 py-4 bg-white border-2 border-gray-200 hover:border-indigo-100 hover:bg-indigo-50 text-gray-700 font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.84z" fill="#FBBC05" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                    </svg>
                    <span class="group-hover:text-indigo-700">Masuk dengan Google</span>
                </a>
                @endauth
            </div>

            <p class="mt-4 text-xs text-gray-400">
                Gunakan akun Google Yang Aktif
            </p>

            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-tr from-indigo-100 to-purple-100 rounded-full blur-3xl -z-10 opacity-50"></div>
        </div>
    </div>

    <div class="py-16 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 text-center hover:border-indigo-200 transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-xl">📚</div>
                    <h3 class="font-bold text-gray-900">Materi Terstruktur</h3>
                    <p class="text-sm text-gray-500 mt-2">Modul belajar disusun sistematis agar mudah dipahami pemula.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 text-center hover:border-purple-200 transition">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-xl">🧩</div>
                    <h3 class="font-bold text-gray-900">Praktik Scratch</h3>
                    <p class="text-sm text-gray-500 mt-2">Langsung praktik coding dengan visual blocks di browser.</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100 text-center hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 text-xl">🏆</div>
                    <h3 class="font-bold text-gray-900">Sistem Poin</h3>
                    <p class="text-sm text-gray-500 mt-2">Kumpulkan poin dari setiap tugas dan jadilah juara kelas.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400 text-sm">© {{ date('Y') }} AlgoLearn.</p>
        </div>
    </footer>

</body>

</html>