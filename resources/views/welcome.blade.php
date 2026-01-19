<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AlgoLearn') }} - Belajar Algoritma dengan Scratch</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-50 text-gray-900 font-sans">

    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center gap-2">
                        <div class="w-8 h-8 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xl">
                            A
                        </div>
                        <span class="font-bold text-xl text-indigo-900">AlgoLearn</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600">Dashboard</a>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-indigo-600 transition">Log in</a>

                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-md hover:shadow-lg">Register</a>
                    @endif
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-24 pb-16 sm:pt-32 sm:pb-24 overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row items-center gap-12">

            <div class="flex-1 text-center lg:text-left z-10">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Belajar Algoritma</span>
                    <span class="block text-indigo-600">Jadi Lebih Menyenangkan</span>
                </h1>
                <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                    Media pembelajaran interaktif berbasis web dengan model tutorial menggunakan <b>Scratch</b>. Dirancang khusus untuk siswa Kelas VII agar mudah memahami logika pemrograman.
                </p>
                <div class="mt-8 sm:mt-10 sm:flex sm:justify-center lg:justify-start gap-4">
                    <div class="rounded-md shadow">
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg transition transform hover:-translate-y-1">
                            Mulai Belajar Sekarang
                        </a>
                    </div>
                    <div class="mt-3 sm:mt-0 sm:ml-3">
                        <a href="#fitur" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-bold rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg transition">
                            Lihat Fitur
                        </a>
                    </div>
                </div>
            </div>

            <div class="flex-1 w-full max-w-lg lg:max-w-none relative">
                <div class="relative rounded-2xl shadow-2xl overflow-hidden border-4 border-white transform rotate-2 hover:rotate-0 transition duration-500">
                    <img src="https://scratch.mit.edu/images/scratch-og.png" alt="App Screenshot" class="w-full h-auto object-cover">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600/20 to-transparent"></div>
                </div>
                <div class="absolute top-0 -right-4 -z-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
                <div class="absolute -bottom-8 -left-4 -z-10 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
            </div>
        </div>
    </div>

    <div id="fitur" class="py-16 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-base font-semibold text-indigo-600 tracking-wide uppercase">Fitur Unggulan</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Metode Belajar Modern
                </p>
                <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
                    Kami menggabungkan teori dan praktik langsung menggunakan engine visual block.
                </p>
            </div>

            <div class="mt-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="relative p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="absolute top-0 left-0 -mt-4 -ml-4 w-12 h-12 bg-indigo-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Materi Terstruktur</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Modul disusun sistematis dari pengenalan hingga logika kompleks sesuai kurikulum.
                        </p>
                    </div>

                    <div class="relative p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="absolute top-0 left-0 -mt-4 -ml-4 w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Workspace Interaktif</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Coding langsung di browser tanpa instalasi tambahan. Terintegrasi dengan Scratch GUI.
                        </p>
                    </div>

                    <div class="relative p-6 bg-gray-50 rounded-xl hover:shadow-lg transition duration-300">
                        <div class="absolute top-0 left-0 -mt-4 -ml-4 w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-gray-900">Penilaian Real-time</h3>
                        <p class="mt-2 text-base text-gray-500">
                            Upload hasil karyamu, dapatkan nilai dan feedback langsung dari pengajar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center gap-2 mb-4 md:mb-0">
                <span class="font-bold text-lg">AlgoLearn</span>
                <span class="text-gray-400 text-sm">© {{ date('Y') }} Skripsi TI UNISKA.</span>
            </div>
            <div class="text-gray-400 text-sm">
                Dibuat dengan Laravel & Scratch
            </div>
        </div>
    </footer>

</body>

</html>