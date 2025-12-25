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
    <style>
        .auth-slider {
            display: none;
        }

        @media (min-width: 768px) {
            .auth-slider {
                display: flex;
            }
        }
    </style>
    <div x-data="{
        isRegister: {{ $isRegister ? 'true' : 'false' }},
        toggle() {
            this.isRegister = !this.isRegister;
        }
    }"
        class="min-h-screen w-full flex items-center justify-center bg-[#0B1120] p-4 relative overflow-hidden selection:bg-primary-500 selection:text-white">

        <!-- Ambient Background Glows -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] bg-primary-600/10 rounded-full blur-[120px]">
            </div>
            <div
                class="absolute bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] bg-secondary-600/10 rounded-full blur-[120px]">
            </div>
        </div>

        <!-- Card Container -->
        <div
            class="relative w-full max-w-6xl h-[650px] bg-slate-900/80 backdrop-blur-xl rounded-[2.5rem] shadow-2xl shadow-black/50 border border-white/5 overflow-hidden flex">

            <!-- Login Form Section (Always on Left visually, usually) -->
            <!-- We use absolute positioning to swap them smoothly -->

            <div class="absolute inset-0 w-full h-full">
                <!-- Login Form -->
                <div class="absolute top-0 left-0 w-full md:w-[50%] h-full flex items-center justify-center p-8 md:p-12 transition-all duration-700 ease-[cubic-bezier(0.87,0,0.13,1)]"
                    :class="isRegister ? 'opacity-0 pointer-events-none -translate-x-[20%]' : 'opacity-100 translate-x-0 z-10'">

                    <div class="w-full max-w-[360px] space-y-7">
                        <div class="text-center md:hidden pb-4">
                            <x-application-logo class="h-10 w-auto fill-current text-primary-500 mx-auto" />
                        </div>

                        <div class="text-left">
                            <h1 class="text-3xl font-bold text-white tracking-tight">Selamat Datang 👋</h1>
                            <p class="mt-2 text-slate-400 text-sm leading-relaxed">Masuk untuk melanjutkan perjalanan
                                finansial Anda.</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <div class="space-y-1.5">
                                <x-input-label for="login_email" :value="__('Email Address')"
                                    class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-primary-500 transition-colors"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <x-text-input id="login_email"
                                        class="block w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                        type="email" name="email" :value="old('email')" required autofocus
                                        autocomplete="username" placeholder="nama@email.com" />
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="space-y-1.5">
                                <x-input-label for="login_password" :value="__('Password')"
                                    class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-slate-500 group-focus-within:text-primary-500 transition-colors"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <x-text-input id="login_password"
                                        class="block w-full pl-11 pr-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                        type="password" name="password" required autocomplete="current-password"
                                        placeholder="••••••••" />
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                                    <div class="relative flex items-center">
                                        <input id="remember_me" type="checkbox" class="peer sr-only" name="remember">
                                        <div
                                            class="w-5 h-5 border-2 border-slate-600 rounded-md peer-checked:bg-primary-500 peer-checked:border-primary-500 transition-all duration-200">
                                        </div>
                                        <svg class="absolute w-3 h-3 text-white left-1 top-1 opacity-0 peer-checked:opacity-100 transition-opacity duration-200 pointer-events-none"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <span
                                        class="ms-2 text-sm text-slate-400 group-hover:text-slate-300 transition-colors">{{ __('Ingat Saya') }}</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-primary-500 hover:text-primary-400 font-bold transition-colors"
                                        href="{{ route('password.request') }}">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-primary-900/30 hover:shadow-primary-600/40 transform hover:-translate-y-0.5 transition-all duration-300">
                                {{ __('Masuk Sekarang') }}
                            </button>
                        </form>

                        <div class="pt-2 text-center text-sm">
                            <span class="text-slate-500">Belum punya akun?</span>
                            <button @click="toggle"
                                class="text-primary-400 hover:text-primary-300 font-bold hover:underline ml-1 transition-colors">
                                Daftar Gratis
                            </button>
                        </div>

                        <div class="text-center pt-4">
                            <a href="{{ route('homepage') }}"
                                class="inline-flex items-center group text-xs font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-wider">
                                <span
                                    class="w-6 h-6 rounded-full border border-slate-700 bg-slate-800 flex items-center justify-center mr-2 group-hover:bg-slate-700 transition-colors">
                                    <svg class="w-3 h-3 text-slate-400 group-hover:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </span>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Register Form (Right Side) -->
                <div class="absolute top-0 right-0 w-full md:w-[50%] h-full flex items-center justify-center p-8 md:p-12 transition-all duration-700 ease-[cubic-bezier(0.87,0,0.13,1)]"
                    :class="!isRegister ? 'opacity-0 pointer-events-none translate-x-[20%]' : 'opacity-100 translate-x-0 z-10'">

                    <div class="w-full max-w-[360px] space-y-6">
                        <div class="text-center md:hidden pb-4">
                            <x-application-logo class="h-10 w-auto fill-current text-primary-500 mx-auto" />
                        </div>

                        <div class="text-left">
                            <h1 class="text-3xl font-bold text-white tracking-tight">Buat Akun 🚀</h1>
                            <p class="mt-2 text-slate-400 text-sm leading-relaxed">Bergabunglah dengan komunitas
                                literasi finansial.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf
                            <div class="space-y-1.5">
                                <x-input-label for="name" :value="__('Nama Lengkap')"
                                    class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                <x-text-input id="name"
                                    class="block w-full px-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                    type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                    placeholder="Nama Lengkap" />
                                <x-input-error :messages="$errors->get('name')" class="mt-1" />
                            </div>

                            <div class="space-y-1.5">
                                <x-input-label for="register_email" :value="__('Email Address')"
                                    class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                <x-text-input id="register_email"
                                    class="block w-full px-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                    type="email" name="email" :value="old('email')" required autocomplete="username"
                                    placeholder="nama@email.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <x-input-label for="register_password" :value="__('Password')"
                                        class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                    <x-text-input id="register_password"
                                        class="block w-full px-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                        type="password" name="password" required autocomplete="new-password"
                                        placeholder="••••••••" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                </div>
                                <div class="space-y-1.5">
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi')"
                                        class="text-slate-300 font-medium text-xs uppercase tracking-wider pl-1" />
                                    <x-text-input id="password_confirmation"
                                        class="block w-full px-4 py-3.5 rounded-2xl bg-slate-950/50 border border-slate-700/50 text-white placeholder-slate-600 focus:border-primary-500 focus:ring-4 focus:ring-primary-500/10 transition-all duration-300"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" placeholder="••••••••" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-indigo-600 hover:from-primary-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-primary-900/30 hover:shadow-primary-600/40 transform hover:-translate-y-0.5 transition-all duration-300 mt-2">
                                {{ __('Daftar Sekarang') }}
                            </button>
                        </form>

                        <div class="pt-2 text-center text-sm">
                            <span class="text-slate-500">Sudah punya akun?</span>
                            <button @click="toggle"
                                class="text-primary-400 hover:text-primary-300 font-bold hover:underline ml-1 transition-colors">
                                Masuk Saja
                            </button>
                        </div>

                        <div class="text-center pt-4">
                            <a href="{{ route('homepage') }}"
                                class="inline-flex items-center group text-xs font-bold text-slate-500 hover:text-white transition-colors uppercase tracking-wider">
                                <span
                                    class="w-6 h-6 rounded-full border border-slate-700 bg-slate-800 flex items-center justify-center mr-2 group-hover:bg-slate-700 transition-colors">
                                    <svg class="w-3 h-3 text-slate-400 group-hover:text-white" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                </span>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sliding Overlay (The Slider) -->
            <div class="auth-slider absolute top-0 h-full bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-900 transition-all duration-[800ms] ease-[cubic-bezier(0.87,0,0.13,1)] z-50 items-center justify-center overflow-hidden shadow-2xl"
                style="width: 50%; height: 100%; top: 0;"
                :style="isRegister ? 'left: 0; mask-image: linear-gradient(to right, black 95%, transparent 100%);' : 'left: 50%; mask-image: linear-gradient(to left, black 95%, transparent 100%);'">

                <!-- Animated Background -->
                <div class="absolute inset-0">
                    <div
                        class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 mix-blend-overlay">
                    </div>
                    <div
                        class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-blue-500/30 blur-[80px] animate-blob">
                    </div>
                    <div
                        class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-purple-500/30 blur-[80px] animate-blob animation-delay-2000">
                    </div>
                </div>

                <!-- Content -->
                <div class="relative z-10 flex flex-col items-center text-center p-12 text-white max-w-sm">
                    <div class="mb-8 transform transition-transform duration-500 hover:scale-110 drop-shadow-2xl">
                        <x-application-logo
                            class="h-44 w-auto fill-current text-white filter drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]" />
                    </div>
                    <h2 class="text-4xl font-extrabold mb-4 tracking-tight leading-tight">Masa Depan<br>Finansial Anda
                    </h2>
                    <p class="text-lg text-blue-100/90 mb-10 leading-relaxed font-light">Pelajari, Pahami, dan Kuasai
                        literasi keuangan bersama Paul Quiz.</p>

                    <button @click="toggle"
                        class="group px-10 py-3.5 border border-white/30 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                        <span x-text="isRegister ? 'Sudah Punya Akun?' : 'Belum Punya Akun?'"
                            class="group-hover:text-white transition-colors"></span>
                    </button>
                    <p class="mt-8 text-[10px] font-medium text-white/40 uppercase tracking-[0.2em]">Paul Quiz Education
                        Platform</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>