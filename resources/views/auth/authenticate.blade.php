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
        class="relative w-full min-h-screen overflow-hidden bg-white dark:bg-dark-900 selection:bg-primary-500 selection:text-white">

        <!-- Forms Container -->
        <div class="absolute top-0 left-0 w-full h-full">
            <!-- Login Form (Left Side) -->
            <div class="absolute top-0 left-0 w-full md:w-[45%] h-full flex items-center justify-center px-8 md:px-16 transition-all duration-700 ease-in-out"
                :class="isRegister ? 'opacity-0 pointer-events-none -translate-x-20' : 'opacity-100 translate-x-0'">
                <div class="w-full max-w-md space-y-8">
                    <div class="text-center md:hidden pb-8">
                        <x-application-logo class="h-16 w-auto fill-current text-primary-600 mx-auto" />
                    </div>

                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Selamat Datang Kembali!</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Silakan masuk ke akun Anda</p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <!-- Email Address -->
                        <div>
                            <x-input-label for="login_email" :value="__('Email')" />
                            <x-text-input id="login_email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="login_password" :value="__('Password')" />
                            <x-text-input id="login_password" class="block mt-1 w-full" type="password" name="password"
                                required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary-600 shadow-sm focus:ring-primary-500"
                                    name="remember">
                                <span
                                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            @if (Route::has('password.request'))
                                <a class="text-sm text-primary-600 hover:text-primary-500 font-medium"
                                    href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif

                            <x-primary-button class="ml-3 px-6 py-3">
                                {{ __('Masuk') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Belum punya akun?
                            <button @click="toggle" class="text-primary-600 font-bold hover:underline ml-1">
                                Daftar Sekarang
                            </button>
                        </p>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>

            <!-- Register Form (Right Side) -->
            <div class="absolute top-0 right-0 w-full md:w-[45%] h-full flex items-center justify-center px-8 md:px-16 transition-all duration-700 ease-in-out"
                :class="!isRegister ? 'opacity-0 pointer-events-none translate-x-20' : 'opacity-100 translate-x-0'">
                <div class="w-full max-w-md space-y-6">
                    <div class="text-center md:hidden pb-8">
                        <x-application-logo class="h-16 w-auto fill-current text-primary-600 mx-auto" />
                    </div>

                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Buat Akun Baru</h2>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Mulai perjalanan finansial Anda</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <!-- Name -->
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email Address -->
                        <div class="mt-4">
                            <x-input-label for="register_email" :value="__('Email')" />
                            <x-text-input id="register_email" class="block mt-1 w-full" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="register_password" :value="__('Password')" />
                            <x-text-input id="register_password" class="block mt-1 w-full" type="password"
                                name="password" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button class="w-full justify-center py-3">
                                {{ __('Daftar') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-gray-600 dark:text-gray-400">
                            Sudah punya akun?
                            <button @click="toggle" class="text-primary-600 font-bold hover:underline ml-1">
                                Masuk Sekarang
                            </button>
                        </p>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('homepage') }}"
                            class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sliding Overlay (The "Book" Cover) -->
        <div class="hidden md:block absolute top-0 left-0 w-[55%] h-full bg-gradient-to-br from-primary-600 to-indigo-700 transition-all duration-700 ease-in-out z-50 flex items-center justify-center overflow-hidden shadow-2xl"
            :style="isRegister ? 'transform: translateX(0); border-top-right-radius: 5rem; border-bottom-right-radius: 5rem;' : 'transform: translateX(81.8%); border-top-left-radius: 5rem; border-bottom-left-radius: 5rem;'">

            <!-- Background Patterns -->
            <div class="absolute inset-0 opacity-20 pointer-events-none">
                <div
                    class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-white mix-blend-overlay filter blur-3xl animate-blob">
                </div>
                <div
                    class="absolute top-1/2 right-0 w-72 h-72 rounded-full bg-secondary-400 mix-blend-overlay filter blur-3xl animate-blob animation-delay-2000">
                </div>
                <div
                    class="absolute -bottom-32 left-20 w-80 h-80 rounded-full bg-purple-500 mix-blend-overlay filter blur-3xl animate-blob animation-delay-4000">
                </div>
            </div>

            <!-- Massive Logo -->
            <div class="relative z-10 flex flex-col items-center text-center p-12 text-white">
                <div class="mb-4 transform transition-transform duration-500 hover:scale-105">
                    <x-application-logo class="h-64 w-auto fill-current text-white drop-shadow-2xl" />
                </div>
                <h2 class="text-5xl font-bold mb-4 tracking-tight">Paul Quiz</h2>
                <p class="text-xl text-blue-100 max-w-md">Platform pembelajaran literasi keuangan masa depan.</p>

                <button @click="toggle"
                    class="mt-12 px-8 py-3 border-2 border-white rounded-full font-bold hover:bg-white hover:text-primary-600 transition-colors duration-300">
                    <span x-text="isRegister ? 'Login Saja' : 'Buat Akun Baru'"></span>
                </button>
            </div>
        </div>

    </div>
</body>

</html>