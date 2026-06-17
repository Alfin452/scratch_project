<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Portal - {{ config('app.name', 'AlgoLearn') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        .login-grid-bg {
            background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 bg-white" 
      x-data="loginAnimation()" 
      @mousemove.window="handleMouseMove"
      x-init="$watch('isTyping', value => watchIsTyping(value))">

    <div class="min-h-screen grid lg:grid-cols-2">
        
        {{-- Left Content Section --}}
        <div class="relative hidden lg:flex flex-col justify-between bg-gradient-to-br from-gray-900 via-gray-900 to-black p-12 text-white overflow-hidden">
            <div class="relative z-20">
                <a href="/" class="flex items-center gap-2 text-lg font-semibold group">
                    <div class="w-8 h-8 rounded-lg bg-white/10 backdrop-blur-sm flex items-center justify-center group-hover:bg-white/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span>AlgoLearn Admin</span>
                </a>
            </div>

            <div class="relative z-20 flex items-end justify-center h-[500px]">
                {{-- Cartoon Characters Container --}}
                <div class="relative w-[550px] h-[400px]">
                    
                    {{-- Purple tall character - Back layer --}}
                    <div id="char-purple"
                        class="absolute bottom-0 transition-all duration-700 ease-in-out"
                        :style="`
                            left: 70px;
                            width: 180px;
                            height: ${(isTyping || (password.length > 0 && !showPassword)) ? '440px' : '400px'};
                            background-color: #6C3FF5;
                            border-radius: 10px 10px 0 0;
                            z-index: 1;
                            transform: ${
                                (password.length > 0 && showPassword) 
                                ? 'skewX(0deg)' 
                                : (isTyping || (password.length > 0 && !showPassword)) 
                                    ? 'skewX(' + ((purplePos.bodySkew || 0) - 12) + 'deg) translateX(40px)' 
                                    : 'skewX(' + (purplePos.bodySkew || 0) + 'deg)'
                            };
                            transform-origin: bottom center;
                        `">
                        {{-- Purple Eyes --}}
                        <div class="absolute flex gap-8 transition-all duration-700 ease-in-out"
                             :style="`
                                left: ${(password.length > 0 && showPassword) ? 20 : isLookingAtEachOther ? 55 : (45 + purplePos.faceX)}px;
                                top: ${(password.length > 0 && showPassword) ? 35 : isLookingAtEachOther ? 65 : (40 + purplePos.faceY)}px;
                             `">
                             {{-- Eye 1 --}}
                             <div class="rounded-full flex items-center justify-center transition-all duration-150"
                                  :style="`width: 18px; height: ${isPurpleBlinking ? '2px' : '18px'}; background-color: white; overflow: hidden;`">
                                  <div x-show="!isPurpleBlinking" id="purple-eye-1" class="rounded-full"
                                       :style="getPupilStyle('purple-eye-1', 5, 7, '#2D2D2D', 
                                            (password.length > 0 && showPassword) ? (isPurplePeeking ? 4 : -4) : isLookingAtEachOther ? 3 : null,
                                            (password.length > 0 && showPassword) ? (isPurplePeeking ? 5 : -4) : isLookingAtEachOther ? 4 : null)">
                                  </div>
                             </div>
                             {{-- Eye 2 --}}
                             <div class="rounded-full flex items-center justify-center transition-all duration-150"
                                  :style="`width: 18px; height: ${isPurpleBlinking ? '2px' : '18px'}; background-color: white; overflow: hidden;`">
                                  <div x-show="!isPurpleBlinking" id="purple-eye-2" class="rounded-full"
                                       :style="getPupilStyle('purple-eye-2', 5, 7, '#2D2D2D', 
                                            (password.length > 0 && showPassword) ? (isPurplePeeking ? 4 : -4) : isLookingAtEachOther ? 3 : null,
                                            (password.length > 0 && showPassword) ? (isPurplePeeking ? 5 : -4) : isLookingAtEachOther ? 4 : null)">
                                  </div>
                             </div>
                        </div>
                    </div>

                    {{-- Black tall character - Middle layer --}}
                    <div id="char-black"
                        class="absolute bottom-0 transition-all duration-700 ease-in-out"
                        :style="`
                            left: 240px;
                            width: 120px;
                            height: 310px;
                            background-color: #2D2D2D;
                            border-radius: 8px 8px 0 0;
                            z-index: 2;
                            transform: ${
                                (password.length > 0 && showPassword)
                                ? 'skewX(0deg)'
                                : isLookingAtEachOther
                                    ? 'skewX(' + ((blackPos.bodySkew || 0) * 1.5 + 10) + 'deg) translateX(20px)'
                                    : (isTyping || (password.length > 0 && !showPassword))
                                        ? 'skewX(' + ((blackPos.bodySkew || 0) * 1.5) + 'deg)'
                                        : 'skewX(' + (blackPos.bodySkew || 0) + 'deg)'
                            };
                            transform-origin: bottom center;
                        `">
                        {{-- Black Eyes --}}
                        <div class="absolute flex gap-6 transition-all duration-700 ease-in-out"
                             :style="`
                                left: ${(password.length > 0 && showPassword) ? 10 : isLookingAtEachOther ? 32 : (26 + blackPos.faceX)}px;
                                top: ${(password.length > 0 && showPassword) ? 28 : isLookingAtEachOther ? 12 : (32 + blackPos.faceY)}px;
                             `">
                             {{-- Eye 1 --}}
                             <div class="rounded-full flex items-center justify-center transition-all duration-150"
                                  :style="`width: 16px; height: ${isBlackBlinking ? '2px' : '16px'}; background-color: white; overflow: hidden;`">
                                  <div x-show="!isBlackBlinking" id="black-eye-1" class="rounded-full"
                                       :style="getPupilStyle('black-eye-1', 4, 6, '#2D2D2D', 
                                            (password.length > 0 && showPassword) ? -4 : isLookingAtEachOther ? 0 : null,
                                            (password.length > 0 && showPassword) ? -4 : isLookingAtEachOther ? -4 : null)">
                                  </div>
                             </div>
                             {{-- Eye 2 --}}
                             <div class="rounded-full flex items-center justify-center transition-all duration-150"
                                  :style="`width: 16px; height: ${isBlackBlinking ? '2px' : '16px'}; background-color: white; overflow: hidden;`">
                                  <div x-show="!isBlackBlinking" id="black-eye-2" class="rounded-full"
                                       :style="getPupilStyle('black-eye-2', 4, 6, '#2D2D2D', 
                                            (password.length > 0 && showPassword) ? -4 : isLookingAtEachOther ? 0 : null,
                                            (password.length > 0 && showPassword) ? -4 : isLookingAtEachOther ? -4 : null)">
                                  </div>
                             </div>
                        </div>
                    </div>

                    {{-- Orange semi-circle character - Front left --}}
                    <div id="char-orange"
                        class="absolute bottom-0 transition-all duration-700 ease-in-out"
                        :style="`
                            left: 0px;
                            width: 240px;
                            height: 200px;
                            z-index: 3;
                            background-color: #FF9B6B;
                            border-radius: 120px 120px 0 0;
                            transform: ${(password.length > 0 && showPassword) ? 'skewX(0deg)' : 'skewX(' + (orangePos.bodySkew || 0) + 'deg)'};
                            transform-origin: bottom center;
                        `">
                        {{-- Orange Eyes (Pupils only) --}}
                        <div class="absolute flex gap-8 transition-all duration-200 ease-out"
                             :style="`
                                left: ${(password.length > 0 && showPassword) ? 50 : (82 + orangePos.faceX)}px;
                                top: ${(password.length > 0 && showPassword) ? 85 : (90 + orangePos.faceY)}px;
                             `">
                             <div id="orange-eye-1" class="rounded-full"
                                  :style="getPupilStyle('orange-eye-1', 5, 12, '#2D2D2D', 
                                       (password.length > 0 && showPassword) ? -5 : null,
                                       (password.length > 0 && showPassword) ? -4 : null)"></div>
                             <div id="orange-eye-2" class="rounded-full"
                                  :style="getPupilStyle('orange-eye-2', 5, 12, '#2D2D2D', 
                                       (password.length > 0 && showPassword) ? -5 : null,
                                       (password.length > 0 && showPassword) ? -4 : null)"></div>
                        </div>
                    </div>

                    {{-- Yellow character - Front right --}}
                    <div id="char-yellow"
                        class="absolute bottom-0 transition-all duration-700 ease-in-out"
                        :style="`
                            left: 310px;
                            width: 140px;
                            height: 230px;
                            background-color: #E8D754;
                            border-radius: 70px 70px 0 0;
                            z-index: 4;
                            transform: ${(password.length > 0 && showPassword) ? 'skewX(0deg)' : 'skewX(' + (yellowPos.bodySkew || 0) + 'deg)'};
                            transform-origin: bottom center;
                        `">
                        {{-- Yellow Eyes (Pupils only) --}}
                        <div class="absolute flex gap-6 transition-all duration-200 ease-out"
                             :style="`
                                left: ${(password.length > 0 && showPassword) ? 20 : (52 + yellowPos.faceX)}px;
                                top: ${(password.length > 0 && showPassword) ? 35 : (40 + yellowPos.faceY)}px;
                             `">
                             <div id="yellow-eye-1" class="rounded-full"
                                  :style="getPupilStyle('yellow-eye-1', 5, 12, '#2D2D2D', 
                                       (password.length > 0 && showPassword) ? -5 : null,
                                       (password.length > 0 && showPassword) ? -4 : null)"></div>
                             <div id="yellow-eye-2" class="rounded-full"
                                  :style="getPupilStyle('yellow-eye-2', 5, 12, '#2D2D2D', 
                                       (password.length > 0 && showPassword) ? -5 : null,
                                       (password.length > 0 && showPassword) ? -4 : null)"></div>
                        </div>
                        {{-- Yellow Mouth --}}
                        <div class="absolute w-20 h-[4px] bg-[#2D2D2D] rounded-full transition-all duration-200 ease-out"
                             :style="`
                                left: ${(password.length > 0 && showPassword) ? 10 : (40 + yellowPos.faceX)}px;
                                top: ${(password.length > 0 && showPassword) ? 88 : (88 + yellowPos.faceY)}px;
                             `"></div>
                    </div>

                </div>
            </div>

            <div class="relative z-20 flex items-center gap-8 text-sm text-white/60">
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-white transition-colors">Contact</a>
            </div>

            {{-- Decorative elements --}}
            <div class="absolute inset-0 login-grid-bg"></div>
            <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl mix-blend-overlay"></div>
            <div class="absolute bottom-1/4 left-1/4 w-96 h-96 bg-white/5 rounded-full blur-3xl mix-blend-overlay"></div>
        </div>

        {{-- Right Login Section --}}
        <div class="flex items-center justify-center p-8 bg-white dark:bg-gray-900 relative">
            <div class="w-full max-w-[420px] relative z-10">
                
                {{-- Mobile Logo --}}
                <div class="lg:hidden flex items-center justify-center gap-2 text-lg font-semibold mb-12 text-gray-900 dark:text-white">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span>AlgoLearn Admin</span>
                </div>

                {{-- Header --}}
                <div class="text-center mb-10">
                    <h1 class="text-3xl font-bold tracking-tight mb-2 text-gray-900 dark:text-white">Welcome back!</h1>
                    <p class="text-gray-500 text-sm">Please enter your details to login</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-900 dark:text-white">Email</label>
                        <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="email"
                            x-model="email"
                            @focus="isTyping = true"
                            @blur="isTyping = false"
                            placeholder="admin@sekolah.sch.id"
                            class="flex h-12 w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 text-sm text-gray-900 dark:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent transition-colors shadow-sm" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <div class="relative">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                x-model="password"
                                placeholder="••••••••"
                                class="flex h-12 w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-3 py-2 pr-10 text-sm text-gray-900 dark:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 focus-visible:border-transparent transition-colors shadow-sm" />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="showPassword" style="display:none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <input id="remember" type="checkbox" name="remember" class="peer h-4 w-4 shrink-0 rounded-sm border border-gray-300 text-indigo-600 focus:ring-indigo-600 cursor-pointer">
                            <label for="remember" class="text-sm font-normal text-gray-700 dark:text-gray-300 cursor-pointer select-none">
                                Remember for 30 days
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-700 hover:underline font-medium">
                            Forgot password?
                        </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full h-12 inline-flex items-center justify-center rounded-md bg-indigo-600 px-8 py-2 text-sm font-medium text-white shadow transition-colors hover:bg-indigo-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-indigo-600 disabled:pointer-events-none disabled:opacity-50">
                        Log in
                    </button>
                </form>

                <div class="mt-6">
                    <a href="{{ route('auth.google') }}" class="w-full h-12 inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 px-8 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm transition-colors hover:bg-gray-50 dark:hover:bg-gray-800 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-400">
                        <svg class="mr-2 h-5 w-5" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Log in with Google (Siswa)
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('loginAnimation', () => ({
                mouseX: 0,
                mouseY: 0,
                email: '',
                password: '',
                showPassword: false,
                isTyping: false,
                isLookingAtEachOther: false,
                isPurplePeeking: false,
                isPurpleBlinking: false,
                isBlackBlinking: false,
                
                purplePos: { faceX: 0, faceY: 0, bodySkew: 0 },
                blackPos: { faceX: 0, faceY: 0, bodySkew: 0 },
                yellowPos: { faceX: 0, faceY: 0, bodySkew: 0 },
                orangePos: { faceX: 0, faceY: 0, bodySkew: 0 },
                
                init() {
                    const getRandomBlinkInterval = () => Math.random() * 4000 + 3000;
                    
                    const schedulePurpleBlink = () => {
                        setTimeout(() => {
                            this.isPurpleBlinking = true;
                            setTimeout(() => {
                                this.isPurpleBlinking = false;
                                schedulePurpleBlink();
                            }, 150);
                        }, getRandomBlinkInterval());
                    };
                    schedulePurpleBlink();

                    const scheduleBlackBlink = () => {
                        setTimeout(() => {
                            this.isBlackBlinking = true;
                            setTimeout(() => {
                                this.isBlackBlinking = false;
                                scheduleBlackBlink();
                            }, 150);
                        }, getRandomBlinkInterval());
                    };
                    scheduleBlackBlink();

                    const schedulePurplePeek = () => {
                        setTimeout(() => {
                            if (this.password.length > 0 && this.showPassword) {
                                this.isPurplePeeking = true;
                                setTimeout(() => {
                                    this.isPurplePeeking = false;
                                }, 800);
                            }
                            schedulePurplePeek();
                        }, Math.random() * 3000 + 2000);
                    };
                    schedulePurplePeek();

                    // Run tracking loop
                    const loop = () => {
                        this.updatePositions();
                        requestAnimationFrame(loop);
                    };
                    requestAnimationFrame(loop);
                },

                handleMouseMove(e) {
                    this.mouseX = e.clientX;
                    this.mouseY = e.clientY;
                },

                watchIsTyping(val) {
                    if (val) {
                        this.isLookingAtEachOther = true;
                        setTimeout(() => {
                            this.isLookingAtEachOther = false;
                        }, 800);
                    } else {
                        this.isLookingAtEachOther = false;
                    }
                },

                updatePositions() {
                    this.purplePos = this.calculatePosition('char-purple');
                    this.blackPos = this.calculatePosition('char-black');
                    this.yellowPos = this.calculatePosition('char-yellow');
                    this.orangePos = this.calculatePosition('char-orange');
                },

                calculatePosition(elementId) {
                    const el = document.getElementById(elementId);
                    if (!el) return { faceX: 0, faceY: 0, bodySkew: 0 };
                    
                    const rect = el.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 3;

                    const deltaX = this.mouseX - centerX;
                    const deltaY = this.mouseY - centerY;

                    return {
                        faceX: Math.max(-15, Math.min(15, deltaX / 20)),
                        faceY: Math.max(-10, Math.min(10, deltaY / 30)),
                        bodySkew: Math.max(-6, Math.min(6, -deltaX / 120))
                    };
                },

                getPupilStyle(elementId, maxDistance, pupilSize, pupilColor, forceLookX, forceLookY) {
                    let px = 0, py = 0;
                    if (forceLookX !== undefined && forceLookX !== null && forceLookY !== undefined && forceLookY !== null) {
                        px = forceLookX;
                        py = forceLookY;
                    } else {
                        const el = document.getElementById(elementId);
                        if (el) {
                            const rect = el.getBoundingClientRect();
                            const centerX = rect.left + rect.width / 2;
                            const centerY = rect.top + rect.height / 2;
                            
                            const deltaX = this.mouseX - centerX;
                            const deltaY = this.mouseY - centerY;
                            const distance = Math.min(Math.sqrt(deltaX ** 2 + deltaY ** 2), maxDistance);
                            const angle = Math.atan2(deltaY, deltaX);
                            
                            px = Math.cos(angle) * distance;
                            py = Math.sin(angle) * distance;
                        }
                    }

                    return `width: ${pupilSize}px; height: ${pupilSize}px; background-color: ${pupilColor}; transform: translate(${px}px, ${py}px); transition: transform 0.1s ease-out;`;
                }
            }));
        });
    </script>
</body>

</html>