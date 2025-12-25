<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paul Quiz</title>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="{{ asset('logo.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-dark-900 text-gray-900 dark:text-white">

    <body>
        <style>
            .auth-slider {
                display: none;
            }

            @media (min-width: 768px) {
                .auth-slider {
                    display: flex;
                }
            }

            .no-scrollbar::-webkit-scrollbar {
                display: none;
            }

            .no-scrollbar {
                -ms-overflow-style: none;
                scrollbar-width: none;
            }

            .form-container {
                opacity: 0;
                pointer-events: none;
                transition: all 0.7s ease-in-out;
            }

            .form-container.active {
                opacity: 1;
                pointer-events: auto;
            }

            /* Modern Input Styling */
            .custom-input {
                background: #0f172a;
                /* Slate 900 */
                border: 1px solid rgba(30, 41, 59, 0.8);
                /* Slate 800 */
                transition: all 0.3s ease;
                box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.3);
                color: white;
            }

            .custom-input::placeholder {
                color: #64748b;
            }

            .custom-input:focus {
                outline: none;
                border-color: #6366f1;
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2), 0 0 20px rgba(99, 102, 241, 0.1);
                transform: translateY(-1px);
            }
        </style>
        <div x-data="{ 
        isRegister: {{ $isRegister ? 'true' : 'false' }},
        toggle() { this.isRegister = !this.isRegister; }
    }" class="min-h-screen w-full flex items-center justify-center bg-[#050b14] p-4 relative overflow-hidden font-sans">

            <!-- Ambient Background Glows -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    class="absolute -top-[20%] -left-[10%] w-[70vw] h-[70vw] bg-indigo-900/10 rounded-full blur-[120px]">
                </div>
                <div class="absolute top-[30%] -right-[20%] w-[60vw] h-[60vw] bg-blue-900/10 rounded-full blur-[120px]">
                </div>
            </div>

            <!-- Main Card -->
            <div class="relative w-full overflow-hidden flex bg-[#0B1120]"
                style="height: 700px; max-width: 1200px; border-radius: 40px; box-shadow: 0 0 80px -20px rgba(67, 56, 202, 0.25), 0 30px 60px -10px rgba(0,0,0,0.8);">

                <!-- Login Form Container -->
                <div class="absolute top-0 left-0 h-full flex flex-col justify-center p-6 md:p-12 form-container"
                    style="width: 50%;" :class="!isRegister ? 'active z-10' : 'z-0 transform -translate-x-10'">

                    <div class="w-full max-w-sm mx-auto space-y-4">
                        <div class="text-left">
                            <!-- Removed Small Logo -->
                            <h1 class="text-3xl font-bold text-white tracking-tight">Selamat Datang</h1>
                            <p class="text-slate-400 mt-1 text-sm">Masuk untuk mengakses dashboard finansial Anda.</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-2" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-3">
                            @csrf
                            <div class="space-y-1">
                                <label
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Email</label>
                                <input class="custom-input block w-full px-5 py-3.5 rounded-xl" type="email"
                                    name="email" :value="old('email')" required autofocus
                                    placeholder="nama@email.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Password</label>
                                <input class="custom-input block w-full px-5 py-3.5 rounded-xl" type="password"
                                    name="password" required placeholder="••••••••" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" class="peer sr-only" name="remember">
                                        <div
                                            class="w-5 h-5 border-2 border-slate-700 rounded-md peer-checked:bg-indigo-600 peer-checked:border-indigo-600 transition-all">
                                        </div>
                                        <svg class="absolute w-3 h-3 text-white left-1 top-1 opacity-0 peer-checked:opacity-100 pointer-events-none"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span
                                        class="ms-2 text-sm text-slate-400 group-hover:text-slate-300 transition-colors">Ingat
                                        Saya</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-indigo-400 hover:text-indigo-300 font-bold transition-colors"
                                        href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/50 hover:shadow-indigo-600/50 transition-all transform hover:-translate-y-0.5">
                                Masuk Sekarang
                            </button>
                        </form>

                        <div class="text-center pt-2">
                            <p class="text-sm text-slate-500">
                                Belum punya akun?
                                <button @click="toggle"
                                    class="text-indigo-400 font-bold hover:underline hover:text-indigo-300 transition-colors">Daftar
                                    Gratis</button>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Register Form Container -->
                <div class="absolute top-0 h-full flex flex-col justify-center p-6 md:p-12 form-container"
                    style="width: 50%; right: 0; left: auto;"
                    :class="isRegister ? 'active z-10' : 'z-0 transform translate-x-10'">

                    <div class="w-full max-w-sm mx-auto space-y-4">
                        <div class="text-left">
                            <!-- Removed Small Logo -->
                            <h1 class="text-3xl font-bold text-white tracking-tight">Buat Akun Baru</h1>
                            <p class="text-slate-400 mt-1 text-sm">Bergabunglah dan mulai belajar hari ini.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-3">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Nama
                                    Lengkap</label>
                                <input class="custom-input block w-full px-5 py-3 rounded-xl" type="text" name="name"
                                    :value="old('name')" required placeholder="Nama Anda" />
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Email</label>
                                <input class="custom-input block w-full px-5 py-3 rounded-xl" type="email" name="email"
                                    :value="old('email')" required placeholder="nama@email.com" />
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Password</label>
                                    <input class="custom-input block w-full px-5 py-3 rounded-xl" type="password"
                                        name="password" required />
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-slate-400 uppercase tracking-wider ml-1">Konfirmasi</label>
                                    <input class="custom-input block w-full px-5 py-3 rounded-xl" type="password"
                                        name="password_confirmation" required />
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/50 hover:shadow-indigo-600/50 transition-all transform hover:-translate-y-0.5 mt-2">
                                Daftar Sekarang
                            </button>
                        </form>

                        <div class="text-center pt-2">
                            <p class="text-sm text-slate-500">
                                Sudah punya akun?
                                <button @click="toggle"
                                    class="text-indigo-400 font-bold hover:underline hover:text-indigo-300 transition-colors">Masuk
                                    Saja</button>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Slider Overlay - SLOW ANIMATION (4000ms) & HUGE LOGO -->
                <div class="auth-slider absolute top-0 h-full transition-all duration-[4000ms] ease-[cubic-bezier(0.85,0,0.15,1)] z-20 items-center justify-center overflow-hidden"
                    style="width: 50%; box-shadow: 0 0 50px rgba(0,0,0,0.5); background: linear-gradient(135deg, #1e3a8a 0%, #312e81 50%, #1e1b4b 100%);"
                    :style="isRegister ? 'left: 0; mask-image: linear-gradient(to right, black 95%, transparent 100%); -webkit-mask-image: linear-gradient(to right, black 95%, transparent 100%); border-top-right-radius: 40px; border-bottom-right-radius: 40px;' : 'left: 50%; mask-image: linear-gradient(to left, black 95%, transparent 100%); -webkit-mask-image: linear-gradient(to left, black 95%, transparent 100%); border-top-left-radius: 40px; border-bottom-left-radius: 40px;'">

                    <!-- Noise & Shapes -->
                    <div class="absolute inset-0 pointer-events-none">
                        <div
                            class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-150 mix-blend-overlay">
                        </div>
                        <!-- Vivid Blobs -->
                        <div
                            class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-indigo-500 rounded-full blur-[80px] opacity-40 animate-pulse">
                        </div>
                        <div
                            class="absolute bottom-[-50px] right-[-50px] w-64 h-64 bg-blue-500 rounded-full blur-[80px] opacity-40">
                        </div>
                    </div>

                    <div class="relative z-10 text-center p-12 text-white max-w-md">
                        <div
                            class="mb-6 transform transition-transform duration-500 hover:scale-105 drop-shadow-2xl flex justify-center">
                            <x-application-logo
                                class="h-80 w-auto fill-current text-white filter drop-shadow-[0_0_25px_rgba(255,255,255,0.4)]" />
                        </div>
                        <h2 class="text-4xl font-extrabold mb-2 tracking-tight drop-shadow-lg">Paul Quiz</h2>
                        <p class="text-lg text-indigo-100/90 mb-8 leading-relaxed font-light drop-shadow-md">
                            Platform edukasi finansial terdepan untuk masa depan Anda.
                        </p>

                        <button @click="toggle"
                            class="px-8 py-3.5 bg-white/10 hover:bg-white/20 border border-white/20 backdrop-blur-md rounded-full font-bold transition-all shadow-lg hover:shadow-white/20 hover:-translate-y-1">
                            <span x-text="isRegister ? 'Sudah Punya Akun?' : 'Belum Punya Akun?'"></span>
                        </button>

                        <!-- Dots Removed -->
                    </div>
                </div>

            </div>
    </body>

</html>