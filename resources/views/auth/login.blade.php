<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal - {{ config('app.name', 'AlgoLearn') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-white">

    <div class="min-h-screen flex">

        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white z-10 relative">
            <div class="mx-auto w-full max-w-sm lg:w-96">

                <div class="mb-8">
                    <a href="/" class="flex items-center gap-2 mb-6 group">
                        <div class="w-10 h-10 bg-indigo-600 text-white rounded-xl flex items-center justify-center font-bold text-2xl shadow-indigo-200 shadow-lg group-hover:scale-110 transition duration-200">
                            A
                        </div>
                        <span class="font-bold text-2xl text-gray-900 tracking-tight">AlgoLearn</span>
                    </a>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Instructor Portal
                    </h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Silakan masuk untuk mengelola materi dan nilai.
                    </p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="mt-8">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Sekolah</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                    </svg>
                                </div>
                                <input id="email" type="email" name="email" :value="old('email')" required autofocus
                                    class="block w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 transition"
                                    placeholder="admin@sekolah.sch.id">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <div class="flex justify-between items-center">
                                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                                @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                    Lupa sandi?
                                </a>
                                @endif
                            </div>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <input id="password" type="password" name="password" required autocomplete="current-password"
                                    class="block w-full pl-10 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm py-3 transition"
                                    placeholder="••••••••">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900 cursor-pointer">
                                Ingat saya
                            </label>
                        </div>

                        <div>
                            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition transform active:scale-95">
                                Masuk ke Dashboard
                            </button>
                        </div>
                    </form>

                    <div class="mt-8">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">
                                    Bukan Guru?
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('auth.google') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                Login Siswa via Google &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1 bg-indigo-900 overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-800 opacity-90"></div>

            <div class="absolute -top-24 -left-24 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

            <div class="relative z-10 h-full flex flex-col justify-center items-center px-10 text-center">
                <div class="mb-8 p-4 bg-white/10 backdrop-blur-sm rounded-2xl border border-white/10 shadow-2xl">
                    <svg class="w-24 h-24 text-white opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h2 class="text-4xl font-bold text-white tracking-tight">Admin Control Center</h2>
                <p class="mt-4 text-lg text-indigo-100 max-w-md">
                    Kelola data siswa, pantau progres materi, dan berikan penilaian tugas dalam satu platform terintegrasi.
                </p>
                <div class="mt-8 flex gap-2">
                    <span class="h-1.5 w-1.5 bg-white rounded-full opacity-50"></span>
                    <span class="h-1.5 w-1.5 bg-white rounded-full opacity-100"></span>
                    <span class="h-1.5 w-1.5 bg-white rounded-full opacity-50"></span>
                </div>
            </div>
        </div>
    </div>

</body>

</html>