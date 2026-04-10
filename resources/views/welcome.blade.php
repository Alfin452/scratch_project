<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'AlgoLearn') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    {{-- Scripts & Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- GSAP CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <script>
        // Check Dark Mode on Load
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 overflow-x-hidden"
    x-data="{ 
          mobileMenuOpen: false,
          darkMode: localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
          toggleTheme() {
              this.darkMode = !this.darkMode;
              if (this.darkMode) {
                  document.documentElement.classList.add('dark');
                  localStorage.setItem('color-theme', 'dark');
              } else {
                  document.documentElement.classList.remove('dark');
                  localStorage.setItem('color-theme', 'light');
              }
          }
      }">

    <nav class="fixed top-0 z-50 w-full bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 transition-colors duration-300 nav-container">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">

                <div class="shrink-0 flex items-center">
                    <a href="#" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-200 logo-anim">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="font-bold text-xl text-gray-900 dark:text-white logo-text-anim">Algo<span class="text-indigo-600 dark:text-indigo-400">Learn</span></span>
                    </a>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <div class="flex gap-6 text-sm font-medium text-gray-600 dark:text-gray-300 nav-links">
                        <a href="#features" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Fitur</a>
                        <a href="#about" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tentang</a>
                    </div>

                    <div class="flex items-center gap-4">
                        <button @click="toggleTheme()" class="p-2 text-gray-500 rounded-full hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors focus:outline-none">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="darkMode" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>

                        <div class="auth-buttons">
                            @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40">Dashboard</a>
                            @else
                            <a href="{{ route('auth.google') }}" class="flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded-full hover:bg-gray-50 dark:bg-gray-800 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 transition-all shadow-sm hover:shadow-md">
                                <svg class="w-5 h-5" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                                </svg>
                                <span>Masuk dengan Google</span>
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <div class="md:hidden flex items-center gap-4">
                    <button @click="toggleTheme()" class="text-gray-500 dark:text-gray-400 focus:outline-none">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <svg x-show="darkMode" style="display: none;" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                        </svg>
                    </button>

                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-indigo-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="mobileMenuOpen" class="md:hidden bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 p-4 space-y-4 shadow-xl">
            <a href="#features" class="block text-base font-medium text-gray-600 dark:text-gray-300">Fitur</a>
            <a href="#about" class="block text-base font-medium text-gray-600 dark:text-gray-300">Tentang</a>
            <hr class="border-gray-200 dark:border-gray-700">
            @auth
            <a href="{{ url('/dashboard') }}" class="block w-full text-center px-4 py-2 text-white bg-indigo-600 rounded-lg">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full px-4 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05" />
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335" />
                </svg>
                Masuk dengan Google
            </a>
            @endauth
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl blob-1"></div>
        <div class="absolute bottom-0 right-0 translate-x-1/3 translate-y-1/3 w-[30rem] h-[30rem] bg-purple-500/20 rounded-full blur-3xl blob-2"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="hero-content text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300 text-sm font-semibold mb-6 hero-badge opacity-0 translate-y-4">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Platform Pembelajaran Algoritma & Pemrograman
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6 hero-title opacity-0 translate-y-8">
                        Kuasai Logika <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 dark:from-indigo-400 dark:to-purple-400">Pemrograman</span>
                        dengan Mudah
                    </h1>

                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto lg:mx-0 hero-desc opacity-0 translate-y-8">
                        AlgoLearn membantu siswa memahami algoritma melalui metode pembelajaran interaktif, visual, dan menyenangkan. Mulai perjalanan codingmu hari ini!
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start hero-cta opacity-0 translate-y-8">
                        <a href="{{ route('auth.google') }}" class="px-8 py-4 text-base font-bold text-white bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-600/30 hover:scale-105 hover:bg-indigo-700 transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Mulai Sekarang</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                        <a href="#features" class="px-8 py-4 text-base font-bold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-300 flex items-center justify-center gap-2 group">
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                            Fitur Utama
                        </a>
                    </div>
                </div>

                <div class="relative hero-image opacity-0 translate-x-12">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white dark:border-gray-700 bg-white dark:bg-gray-800">
                        <div class="p-6">
                            <div class="flex gap-2 mb-4">
                                <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 bg-gray-100 dark:bg-gray-700 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-100 dark:bg-gray-700 rounded w-1/2"></div>
                                <div class="h-32 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800 flex items-center justify-center text-indigo-400">
                                    <svg class="w-16 h-16 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                                <div class="h-4 bg-gray-100 dark:bg-gray-700 rounded w-2/3"></div>
                            </div>
                            <div class="absolute -right-6 top-10 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 float-card-1">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400">Status</p>
                                        <p class="font-bold text-gray-800 dark:text-white">Completed!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-24 bg-white dark:bg-gray-800 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 feature-header opacity-0 translate-y-8">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4">Mengapa AlgoLearn?</h2>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Platform ini dirancang khusus untuk membuat konsep pemrograman yang rumit menjadi sederhana dan menyenangkan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="feature-card p-8 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:bg-white dark:hover:bg-gray-700 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 hover:shadow-xl transition-all duration-300 opacity-0 translate-y-12">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center text-indigo-600 dark:text-indigo-400 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Materi Terstruktur</h3>
                    <p class="text-gray-600 dark:text-gray-400">Kurikulum disusun bertahap mulai dari dasar hingga konsep lanjut, sesuai dengan standar pendidikan.</p>
                </div>

                <div class="feature-card p-8 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:bg-white dark:hover:bg-gray-700 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 hover:shadow-xl transition-all duration-300 opacity-0 translate-y-12" style="transition-delay: 100ms;">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/50 rounded-xl flex items-center justify-center text-purple-600 dark:text-purple-400 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Latihan Interaktif</h3>
                    <p class="text-gray-600 dark:text-gray-400">Asah kemampuan logikamu dengan kuis interaktif dan studi kasus nyata yang menantang.</p>
                </div>

                <div class="feature-card p-8 bg-gray-50 dark:bg-gray-700/50 rounded-2xl hover:bg-white dark:hover:bg-gray-700 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 hover:shadow-xl transition-all duration-300 opacity-0 translate-y-12" style="transition-delay: 200ms;">
                    <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900/50 rounded-xl flex items-center justify-center text-yellow-600 dark:text-yellow-400 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Sistem Peringkat</h3>
                    <p class="text-gray-600 dark:text-gray-400">Bersainglah dengan teman sekelasmu dan dapatkan pencapaian (achievements) menarik.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 bg-gray-50 dark:bg-gray-900 relative border-t border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="about-text opacity-0 translate-y-8">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-6">Tentang AlgoLearn</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        AlgoLearn adalah inisiatif pendidikan yang bertujuan untuk mendemokratisasi akses terhadap ilmu komputer bagi siswa sekolah menengah.
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                        Kami percaya bahwa pemikiran komputasional adalah skill abad 21 yang penting. Dengan pendekatan visual dan gamifikasi, kami mengubah "kode yang menakutkan" menjadi teka-teki yang menyenangkan.
                    </p>

                    <div class="grid grid-cols-2 gap-6">

                    </div>
                </div>

                <div class="relative about-image opacity-0 translate-x-8">
                    <div class="absolute inset-0 bg-gradient-to-tr from-indigo-600 to-purple-600 rounded-3xl transform rotate-3 opacity-20 dark:opacity-40"></div>
                    <div class="relative bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-xl border border-gray-100 dark:border-gray-700">
                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">Visi Kami</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Mencetak generasi digital native yang tidak hanya bisa menggunakan teknologi, tapi juga menciptakannya.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-lg bg-green-100 dark:bg-green-900/50 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">Misi Kami</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Menyediakan platform pembelajaran yang adaptif, menyenangkan, dan relevan dengan industri saat ini.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 mb-12">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">A</div>
                        <span class="font-bold text-xl text-gray-900 dark:text-white">AlgoLearn</span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm max-w-xs">
                        Membangun generasi programmer masa depan Indonesia dengan pondasi logika yang kuat.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#features" class="hover:text-indigo-600">Fitur</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Kurikulum</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Harga</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-4">Dukungan</h4>
                    <ul class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                        <li><a href="#" class="hover:text-indigo-600">Bantuan</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Privasi</a></li>
                        <li><a href="#" class="hover:text-indigo-600">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-800 pt-8 text-center">
                <p class="text-sm text-gray-400">&copy; {{ date('Y') }} AlgoLearn. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Navbar Entrance
        gsap.from(".nav-container", {
            y: -100,
            opacity: 0,
            duration: 1,
            ease: "power4.out"
        });

        // Hero Entrance (Staggered)
        const tl = gsap.timeline();
        tl.to(".logo-anim", {
                scale: 1,
                duration: 0.5,
                ease: "back.out(1.7)"
            })
            .to(".hero-badge", {
                y: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power3.out"
            }, "-=0.2")
            .to(".hero-title", {
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power3.out"
            }, "-=0.4")
            .to(".hero-desc", {
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power3.out"
            }, "-=0.6")
            .to(".hero-cta", {
                y: 0,
                opacity: 1,
                duration: 0.8,
                ease: "power3.out"
            }, "-=0.6")
            .to(".hero-image", {
                x: 0,
                opacity: 1,
                duration: 1,
                ease: "power3.out"
            }, "-=0.8");

        // Blob Animation (Background)
        gsap.to(".blob-1", {
            x: "50%",
            y: "50%",
            duration: 10,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });
        gsap.to(".blob-2", {
            x: "-20%",
            y: "-20%",
            duration: 8,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut"
        });

        // Features Scroll Animation
        gsap.to(".feature-header", {
            scrollTrigger: {
                trigger: "#features",
                start: "top 80%",
            },
            y: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power3.out"
        });

        gsap.utils.toArray(".feature-card").forEach((card, i) => {
            gsap.to(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                },
                y: 0,
                opacity: 1,
                duration: 0.8,
                delay: i * 0.1,
                ease: "power3.out"
            });
        });

        // About Section Animation
        gsap.to(".about-text", {
            scrollTrigger: {
                trigger: "#about",
                start: "top 75%",
            },
            y: 0,
            opacity: 1,
            duration: 1,
            ease: "power3.out"
        });

        gsap.to(".about-image", {
            scrollTrigger: {
                trigger: "#about",
                start: "top 75%",
            },
            x: 0,
            opacity: 1,
            duration: 1,
            delay: 0.2,
            ease: "power3.out"
        });
    </script>
</body>

</html>