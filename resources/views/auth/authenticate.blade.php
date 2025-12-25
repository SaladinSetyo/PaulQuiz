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

            /* Smooth Transitions */
            .form-container {
                opacity: 0;
                pointer-events: none;
                transition: all 0.7s ease-in-out;
            }

            .form-container.active {
                opacity: 1;
                pointer-events: auto;
            }

            /* Input Focus Ring Custom */
            .custom-input:focus {
                box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
                border-color: #6366f1;
            }
        </style>
        <div x-data="{
        isRegister: {{ $isRegister ? 'true' : 'false' }},
        toggle() {
            this.isRegister = !this.isRegister;
        }
    }" class="min-h-screen w-full flex items-center justify-center bg-[#0d1526] p-4 relative overflow-hidden font-sans">

            <!-- Background Decor -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div
                    class="absolute -top-[10%] -left-[10%] w-[60vw] h-[60vw] bg-indigo-900/20 rounded-full blur-[100px]">
                </div>
                <div class="absolute top-[40%] -right-[10%] w-[60vw] h-[60vw] bg-blue-900/10 rounded-full blur-[100px]">
                </div>
            </div>

            <!-- Main Card -->
            <div class="relative w-full shadow-2xl overflow-hidden flex bg-slate-900/90 backdrop-blur-2xl border border-white/5"
                style="height: 700px; max-width: 1200px; border-radius: 30px; box-shadow: 0 50px 100px -20px rgba(0,0,0,0.6);">

                <!-- Login Form Container (Visible on Left) -->
                <div class="absolute top-0 left-0 h-full flex flex-col justify-center p-8 md:p-16 form-container"
                    style="width: 50%;" :class="!isRegister ? 'active z-10' : 'z-0 transform -translate-x-10'">

                    <div class="w-full max-w-sm mx-auto space-y-8">
                        <div class="text-left">
                            <x-application-logo class="h-10 w-auto fill-current text-indigo-500 mb-6" />
                            <h1 class="text-3xl font-bold text-white tracking-tight">Selamat Datang</h1>
                            <p class="text-slate-400 mt-2 text-sm">Masuk untuk mengakses dashboard finansial Anda.</p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Email</label>
                                <input
                                    class="custom-input block w-full px-5 py-4 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                    type="email" name="email" :value="old('email')" required autofocus
                                    placeholder="nama@email.com" />
                                <x-input-error :messages="$errors->get('email')" class="mt-1" />
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Password</label>
                                <input
                                    class="custom-input block w-full px-5 py-4 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                    type="password" name="password" required placeholder="••••••••" />
                                <x-input-error :messages="$errors->get('password')" class="mt-1" />
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox"
                                        class="rounded bg-slate-800 border-slate-700 text-indigo-500 shadow-sm focus:ring-indigo-500"
                                        name="remember">
                                    <span class="ms-2 text-sm text-slate-400">Ingat Saya</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-indigo-400 hover:text-indigo-300 font-bold"
                                        href="{{ route('password.request') }}">Lupa Password?</a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/30 transition-all transform hover:-translate-y-0.5">
                                Masuk Sekarang
                            </button>
                        </form>

                        <div class="text-center pt-2">
                            <p class="text-sm text-slate-500">
                                Belum punya akun?
                                <button @click="toggle" class="text-indigo-400 font-bold hover:underline">Daftar
                                    Gratis</button>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Register Form Container (Visible on Right) -->
                <div class="absolute top-0 h-full flex flex-col justify-center p-8 md:p-16 form-container"
                    style="width: 50%; right: 0; left: auto;"
                    :class="isRegister ? 'active z-10' : 'z-0 transform translate-x-10'">

                    <div class="w-full max-w-sm mx-auto space-y-6">
                        <div class="text-left">
                            <x-application-logo class="h-10 w-auto fill-current text-indigo-500 mb-6" />
                            <h1 class="text-3xl font-bold text-white tracking-tight">Buat Akun Baru</h1>
                            <p class="text-slate-400 mt-2 text-sm">Bergabunglah dan mulai belajar hari ini.</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Nama
                                    Lengkap</label>
                                <input
                                    class="custom-input block w-full px-5 py-3.5 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                    type="text" name="name" :value="old('name')" required placeholder="Nama Anda" />
                            </div>

                            <div class="space-y-1">
                                <label
                                    class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Email</label>
                                <input
                                    class="custom-input block w-full px-5 py-3.5 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                    type="email" name="email" :value="old('email')" required
                                    placeholder="nama@email.com" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Password</label>
                                    <input
                                        class="custom-input block w-full px-5 py-3.5 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                        type="password" name="password" required />
                                </div>
                                <div class="space-y-1">
                                    <label
                                        class="text-xs font-bold text-slate-300 uppercase tracking-wider ml-1">Konfirmasi</label>
                                    <input
                                        class="custom-input block w-full px-5 py-3.5 rounded-xl bg-slate-950 border border-slate-700 text-white placeholder-slate-600 focus:outline-none transition-all"
                                        type="password" name="password_confirmation" required />
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl shadow-lg shadow-indigo-900/30 transition-all transform hover:-translate-y-0.5 mt-2">
                                Daftar Sekarang
                            </button>
                        </form>

                        <div class="text-center pt-2">
                            <p class="text-sm text-slate-500">
                                Sudah punya akun?
                                <button @click="toggle" class="text-indigo-400 font-bold hover:underline">Masuk
                                    Saja</button>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Slider Overlay - Absolute Positioning -->
                <div class="auth-slider absolute top-0 h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-800 transition-all duration-700 ease-in-out z-20 items-center justify-center overflow-hidden shadow-2xl"
                    style="width: 50%;"
                    :style="isRegister ? 'left: 0; border-top-right-radius: 30px; border-bottom-right-radius: 30px;' : 'left: 50%; border-top-left-radius: 30px; border-bottom-left-radius: 30px;'">

                    <div class="relative z-10 text-center p-12 text-white max-w-md">
                        <div
                            class="mb-8 transform transition-transform duration-500 hover:scale-110 drop-shadow-2xl flex justify-center">
                            <x-application-logo class="h-32 w-auto fill-current text-white" />
                        </div>
                        <h2 class="text-3xl font-extrabold mb-4">Paul Quiz</h2>
                        <p class="text-lg text-indigo-100 mb-8 opacity-90">Platform edukasi finansial terdepan untuk
                            masa
                            depan Anda.</p>

                        <button @click="toggle"
                            class="px-8 py-3 bg-white/10 hover:bg-white/20 border border-white/30 backdrop-blur-md rounded-full font-bold transition-all">
                            <span x-text="isRegister ? 'Sudah Punya Akun?' : 'Belum Punya Akun?'"></span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </body>

</html>