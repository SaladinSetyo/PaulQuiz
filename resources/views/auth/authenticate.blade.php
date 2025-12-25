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
    <div x-data="{ 
        isRegister: {{ $isRegister ? 'true' : 'false' }},
        toggle() {
            this.isRegister = !this.isRegister;
        }
    }"
        class="relative w-full min-h-screen bg-slate-900 flex items-center justify-center p-4 sm:p-6 selection:bg-primary-500 selection:text-white overflow-hidden">

        <!-- Background Decor -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[70%] h-[70%] rounded-full bg-primary-900/20 blur-[120px]">
            </div>
            <div class="absolute top-[40%] -right-[10%] w-[60%] h-[60%] rounded-full bg-secondary-900/20 blur-[120px]">
            </div>
        </div>

        <!-- Centered Card Container -->
        <div style="height: 650px;"
            class="relative w-full max-w-4xl md:max-w-6xl bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden border border-white/10 relative z-10">

            <!-- Forms Container -->
            <div class="absolute inset-0">
                <!-- Login Form (Left Side) -->
                <div class="absolute top-0 left-0 w-full md:w-[45%] h-full flex items-center justify-center px-8 md:px-12 transition-all duration-700 ease-in-out"
                    :class="isRegister ? 'opacity-0 pointer-events-none -translate-x-20' : 'opacity-100 translate-x-0'">
                    <div class="w-full max-w-sm space-y-6">
                        <div class="text-center md:hidden pb-6">
                            <x-application-logo class="h-12 w-auto fill-current text-primary-600 mx-auto" />
                        </div>

                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Selamat Datang!
                            </h2>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Masuk untuk melanjutkan
                                pembelajaran finansial Anda.</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="login_email" :value="__('Email Address')" />
                                <x-text-input id="login_email"
                                    class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                    type="email" name="email" :value="old('email')" required autofocus
                                    autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-input-label for="login_password" :value="__('Password')" />
                                <x-text-input id="login_password"
                                    class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                    type="password" name="password" required autocomplete="current-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <label for="remember_me" class="inline-flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="rounded dark:bg-slate-700 border-gray-300 dark:border-slate-600 text-primary-600 shadow-sm focus:ring-primary-500"
                                        name="remember">
                                    <span
                                        class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Ingat Saya') }}</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-primary-600 hover:text-primary-500 font-bold"
                                        href="{{ route('password.request') }}">
                                        Lupa Password?
                                    </a>
                                @endif
                            </div>

                            <x-primary-button class="w-full justify-center py-3.5 text-base rounded-xl mt-6">
                                {{ __('Masuk Sekarang') }}
                            </x-primary-button>
                        </form>

                        <div class="mt-6 text-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Belum punya akun?</span>
                            <button @click="toggle" class="text-primary-600 font-bold hover:underline ml-1">
                                Daftar Gratis
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('homepage') }}"
                                class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors uppercase tracking-wider">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Register Form (Right Side) -->
                <div class="absolute top-0 right-0 w-full md:w-[45%] h-full flex items-center justify-center px-8 md:px-12 transition-all duration-700 ease-in-out"
                    :class="!isRegister ? 'opacity-0 pointer-events-none translate-x-20' : 'opacity-100 translate-x-0'">
                    <div class="w-full max-w-sm space-y-6">
                        <div class="text-center md:hidden pb-6">
                            <x-application-logo class="h-12 w-auto fill-current text-primary-600 mx-auto" />
                        </div>

                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Buat Akun</h2>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Bergabunglah dengan komunitas
                                literasi finansial.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" />
                                <x-text-input id="name"
                                    class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                    type="text" name="name" :value="old('name')" required autofocus
                                    autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="register_email" :value="__('Email Address')" />
                                <x-text-input id="register_email"
                                    class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                    type="email" name="email" :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="register_password" :value="__('Password')" />
                                    <x-text-input id="register_password"
                                        class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                        type="password" name="password" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi')" />
                                    <x-text-input id="password_confirmation"
                                        class="block mt-1 w-full rounded-xl bg-gray-50 dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 focus:ring-primary-500"
                                        type="password" name="password_confirmation" required
                                        autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>

                            <x-primary-button class="w-full justify-center py-3.5 text-base rounded-xl mt-6">
                                {{ __('Daftar Sekarang') }}
                            </x-primary-button>
                        </form>

                        <div class="mt-6 text-center text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Sudah punya akun?</span>
                            <button @click="toggle" class="text-primary-600 font-bold hover:underline ml-1">
                                Masuk Saja
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <a href="{{ route('homepage') }}"
                                class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors uppercase tracking-wider">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sliding Overlay (The Slider) -->
            <div style="width: 55%;"
                class="hidden md:flex absolute top-0 left-0 h-full bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-800 transition-all duration-700 ease-in-out z-50 items-center justify-center overflow-hidden"
                :style="isRegister ? 'transform: translateX(0); border-top-right-radius: 2rem; border-bottom-right-radius: 2rem;' : 'transform: translateX(81.8%); border-top-left-radius: 2rem; border-bottom-left-radius: 2rem;'">

                <!-- Animated Background -->
                <div class="absolute inset-0">
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
                        <x-application-logo class="h-40 w-auto fill-current text-white" />
                    </div>
                    <h2 class="text-4xl font-extrabold mb-4 tracking-tight leading-tight">Masa Depan<br>Finansial Anda
                    </h2>
                    <p class="text-lg text-blue-100/90 mb-10 leading-relaxed">Pelajari, Pahami, dan Kuasai literasi
                        keuangan bersama Paul Quiz.</p>

                    <button @click="toggle"
                        class="px-10 py-3.5 border border-white/30 bg-white/10 hover:bg-white hover:text-primary-700 backdrop-blur-sm rounded-full font-bold transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl">
                        <span x-text="isRegister ? 'Sudah Punya Akun?' : 'Belum Punya Akun?'"></span>
                    </button>
                    <p class="mt-4 text-xs font-medium text-white/50 uppercase tracking-widest">Paul Quiz Education
                        Platform</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>