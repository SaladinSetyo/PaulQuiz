<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paul Quiz - Tingkatkan Literasi Keuangan Anda</title>
    <link rel="icon" href="{{ asset('logo.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" sizes="1080x1080" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="{{ asset('logo.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="{{ asset('logo.svg') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        /* Neon Green Glow Effects */
        .neon-capsule-target {
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .group:hover .neon-capsule-target {
            box-shadow: 0 0 50px 10px rgba(16, 185, 129, 0.4),
                0 0 100px 20px rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.5) !important;
        }

        .neon-btn-green {
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.4);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .neon-btn-green:hover {
            box-shadow: 0 20px 40px -5px rgba(16, 185, 129, 0.7),
                0 0 20px 2px rgba(16, 185, 129, 0.5);
        }

        .neon-card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .neon-card-hover:hover {
            box-shadow: 0 0 40px rgba(16, 185, 129, 0.4);
            border-color: rgba(16, 185, 129, 0.5) !important;
            transform: translateY(-2px);
        }
    </style>
</head>

<body id="top"
    class="font-sans antialiased bg-slate-50 dark:bg-dark-900 text-gray-900 dark:text-white selection:bg-primary-500 selection:text-white">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header
            class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/80 dark:bg-dark-900/80 backdrop-blur-md border-b border-gray-100 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-24">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center group gap-3">
                            <x-application-logo
                                class="block h-16 w-auto fill-current text-primary-600 dark:text-primary-400 group-hover:scale-105 transition-transform duration-300 drop-shadow-md" />
                            <span class="font-bold text-3xl tracking-tight text-gray-900 dark:text-white">Paul
                                <span class="text-primary-600 dark:text-primary-400">Quiz</span></span>
                        </a>
                    </div>
                    <div class="hidden sm:flex items-center space-x-8">
                        @if (Route::has('login'))
                            @auth
                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                <div>{{ Auth::user()->name }}</div>
                                                <div class="ms-1">
                                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <x-dropdown-link :href="route('profile.edit')">
                                                {{ __('Profile') }}
                                            </x-dropdown-link>
                                            <x-dropdown-link :href="route('users.stats', Auth::user())">
                                                {{ __('Statistik') }}
                                            </x-dropdown-link>

                                            <!-- Authentication -->
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf

                                                <x-dropdown-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                                                                                                                                                                                            this.closest('form').submit();">
                                                    {{ __('Log Out') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                </div>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Log
                                    in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-full shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero Section -->
            <section class="relative overflow-hidden bg-slate-50 dark:bg-dark-900"
                style="padding-top: 180px; padding-bottom: 6rem;">
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                    <div
                        class="absolute -top-[20%] right-[10%] w-[50%] h-[50%] rounded-full bg-primary-500/10 blur-[100px]">
                    </div>
                    <div
                        class="absolute bottom-[0%] left-[10%] w-[40%] h-[40%] rounded-full bg-secondary-500/10 blur-[100px]">
                    </div>
                </div>

                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                    <span
                        class="inline-block py-1 px-3 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 text-sm font-semibold mb-6">Masa
                        Depan Finansial</span>
                    <h1
                        class="text-4xl md:text-5xl lg:text-7xl font-bold tracking-tight text-gray-900 dark:text-white mb-6 leading-tight">
                        Platform Inovatif untuk <br>
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-indigo-500">Literasi
                            Keuangan</span>
                    </h1>
                    <p class="mt-6 max-w-3xl mx-auto text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
                        Paul Quiz hadir untuk memberdayakan Anda dengan pengetahuan finansial yang relevan, praktis,
                        dan mudah diakses.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ $firstModule ? route('modules.show', $firstModule) : route('register') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all bg-primary-600 rounded-full hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-600/30 hover:-translate-y-1">
                            Mulai Belajar Sekarang
                        </a>
                        <a href="{{ route('modules.index') }}"
                            class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-700 dark:text-gray-200 transition-all bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 hover:shadow-lg hover:-translate-y-1">
                            Lihat Modul
                        </a>
                    </div>
                </div>
            </section>

            <!-- Majestic Glass Capsule Section -->
            @if($featuredQuiz)
                <section class="py-16 relative z-40 -mt-20 sm:-mt-24">
                    <div class="max-w-3xl mx-auto px-4">
                        <div class="relative group">
                            <!-- Multi-Layered Neon Green Aura Glow -->
                            <div
                                class="absolute -inset-2 bg-emerald-500/30 rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                            <div
                                class="absolute -inset-10 bg-emerald-600/20 rounded-full blur-3xl opacity-0 group-hover:opacity-70 transition-opacity duration-1000">
                            </div>

                            <!-- The Majestic Symmetrical Crystal Glass Capsule with Robust Neon Green Shadow -->
                            <div
                                class="relative bg-white/10 dark:bg-white/[0.03] backdrop-blur-3xl rounded-full border border-white/30 dark:border-white/10 shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] p-4 px-10 flex items-center justify-between gap-8 transition-all duration-700 hover:scale-[1.01] hover:bg-white/20 dark:hover:bg-white/[0.05] overflow-hidden group/capsule neon-capsule-target">
                                <!-- Multi-Layered Shimmer -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1500 ease-in-out">
                                </div>

                                <!-- Pure Internal Emerald Aura -->
                                <div
                                    class="absolute inset-0 bg-emerald-600/[0.03] opacity-0 group-hover/capsule:opacity-100 transition-opacity duration-700">
                                </div>

                                <!-- Left Content: Logo -->
                                <div class="flex-shrink-0 relative group/logo">
                                    <div
                                        class="absolute inset-0 bg-primary-500/20 rounded-full blur-2xl opacity-0 group-hover/logo:opacity-100 transition-opacity duration-700">
                                    </div>
                                    <x-application-logo
                                        class="relative z-10 w-14 h-14 fill-current text-primary-600 dark:text-primary-400 drop-shadow-xl transform group-hover/logo:scale-110 transition-transform duration-500" />
                                </div>

                                <!-- Center Content: Info -->
                                <div class="flex-grow min-w-0 px-4">
                                    <div class="flex items-center gap-3 mb-1.5">
                                        <div
                                            class="flex items-center gap-1.5 px-2.5 py-0.5 bg-emerald-500/10 rounded-full border border-emerald-500/20">
                                            <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                            <span
                                                class="text-[10px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Rekomendasi</span>
                                        </div>
                                        <span
                                            class="text-gray-400 dark:text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em] hidden md:inline-block">{{ $featuredQuiz->module->title ?? 'Edukasi' }}</span>
                                    </div>
                                    <h3
                                        class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white truncate tracking-tight">
                                        {{ $featuredQuiz->title }}
                                    </h3>
                                </div>

                                <!-- Right Content: Status or Refined Modern Button -->
                                <div class="flex-shrink-0">
                                    @if(Auth::check() && $isFeaturedQuizSolved)
                                        <div class="flex flex-col items-end gap-3">
                                            <div
                                                class="flex items-center gap-2 px-4 py-2 bg-emerald-500/20 border border-emerald-500/30 rounded-xl">
                                                <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-emerald-400 font-bold text-xs uppercase tracking-wider">Anda
                                                    sudah mengerjakannya</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('modules.index') }}"
                                                    class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-full font-bold text-[10px] uppercase tracking-wider transition-all border border-white/10 flex items-center gap-2">
                                                    Lihat Modul Lain
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('homepage') }}"
                                                    class="px-5 py-2.5 bg-primary-500/20 hover:bg-primary-500/30 text-primary-300 rounded-full font-bold text-[10px] uppercase tracking-wider transition-all border border-primary-500/30">
                                                    Back to Home
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <a href="{{ route('quizzes.show', $featuredQuiz->quiz_id) }}"
                                            class="inline-flex items-center justify-center px-8 py-3.5 bg-gradient-to-br from-primary-500 via-primary-600 to-indigo-700 text-white rounded-full font-extrabold text-[11px] uppercase tracking-[0.25em] transition-all duration-500 hover:scale-105 active:scale-95 group/btn relative overflow-hidden neon-btn-green">
                                            <span class="relative z-10">Mulai Kuis</span>
                                            <svg class="w-4 h-4 ml-3 relative z-10 transform group-hover/btn:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                            </svg>

                                            <!-- Advanced Liquid Shimmer -->
                                            <div
                                                class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000 ease-in-out">
                                            </div>

                                            <!-- Subtle Inner Glow Ring -->
                                            <div
                                                class="absolute inset-[1px] rounded-full border border-white/10 pointer-events-none">
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <!-- Online Users Section (Authenticated Only) -->
            @auth
                <section class="py-10 relative z-30">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div
                            class="relative bg-white/80 dark:bg-dark-800/80 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-xl overflow-hidden group neon-card-hover">
                            <!-- Decorative Glow -->
                            <div
                                class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/20 transition-all duration-700">
                            </div>

                            <div class="flex flex-col md:flex-row items-center justify-between gap-6 relative z-10">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl text-emerald-600 dark:text-emerald-400">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                            Sobat Fintech Online
                                            <span class="flex h-3 w-3 relative">
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                            </span>
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Belajar bersama komunitas
                                            sekarang</p>
                                    </div>
                                </div>

                                <div class="flex items-center -space-x-4 overflow-x-auto py-6 px-4 [&::-webkit-scrollbar]:hidden"
                                    style="scrollbar-width: none; -ms-overflow-style: none;">
                                    @foreach($onlineUsers as $user)
                                        <div class="relative group/avatar" title="{{ $user->name }}">
                                            <div
                                                class="w-12 h-12 rounded-full border-2 border-white dark:border-dark-800 bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-300 shadow-md transform transition-all duration-300 group-hover/avatar:scale-110 group-hover/avatar:shadow-[0_0_25px_rgba(16,185,129,1)] group-hover/avatar:border-emerald-400 group-hover/avatar:z-20 cursor-help relative z-10">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div
                                                class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-dark-800 rounded-full z-20 group-hover/avatar:shadow-[0_0_10px_rgba(16,185,129,0.8)] transition-shadow">
                                            </div>
                                            <!-- Tooltip -->
                                            <div
                                                class="absolute -bottom-10 left-1/2 -translate-x-1/2 bg-emerald-600 text-white text-[10px] font-bold px-3 py-1.5 rounded-full opacity-0 group-hover/avatar:opacity-100 transition-all duration-300 whitespace-nowrap pointer-events-none shadow-lg shadow-emerald-500/20 translate-y-2 group-hover/avatar:translate-y-0 z-30">
                                                {{ $user->name }}
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($onlineUsers->isEmpty())
                                        <span class="text-gray-400 text-sm italic">Belum ada user lain online</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endauth

            <!-- About / Features Cards -->
            <section class="py-20 bg-white dark:bg-dark-800 border-y border-gray-100 dark:border-dark-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Membuka Pintu ke Dunia Finansial
                        </h2>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Kami percaya setiap orang berhak
                            memiliki pemahaman finansial yang kuat.</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Card 1 -->
                        <div
                            class="p-8 rounded-2xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-dark-700 hover:shadow-xl transition-all duration-300 group">
                            <div
                                class="w-14 h-14 rounded-xl bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v11.494m-9-5.747h18" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Konten Edukatif</h3>
                            <p class="text-gray-600 dark:text-gray-400">Akses modul lengkap tentang Fintech.</p>
                        </div>
                        <!-- Card 2 -->
                        <div
                            class="p-8 rounded-2xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-dark-700 hover:shadow-xl transition-all duration-300 group">
                            <div
                                class="w-14 h-14 rounded-xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Kuis Interaktif</h3>
                            <p class="text-gray-600 dark:text-gray-400">Uji pemahaman dengan kuis menarik.</p>
                        </div>
                        <!-- Card 3 -->
                        <div
                            class="p-8 rounded-2xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-dark-700 hover:shadow-xl transition-all duration-300 group">
                            <div
                                class="w-14 h-14 rounded-xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Pelacakan Progres</h3>
                            <p class="text-gray-600 dark:text-gray-400">Pantau kemajuan belajar Anda.</p>
                        </div>
                        <!-- Card 4 -->
                        <div
                            class="p-8 rounded-2xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-dark-700 hover:shadow-xl transition-all duration-300 group">
                            <div
                                class="w-14 h-14 rounded-xl bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Gamifikasi</h3>
                            <p class="text-gray-600 dark:text-gray-400">Bersaing di papan peringkat.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Videos Section -->
            <section class="py-20 bg-slate-50 dark:bg-dark-900">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Video Edukasi</h2>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Pembelajaran visual yang menarik.</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($videos as $video)
                            <div
                                class="group relative aspect-video rounded-3xl overflow-hidden shadow-lg border border-gray-200 dark:border-dark-700 bg-black transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:border-primary-500 z-0 hover:z-10">
                                <iframe class="w-full h-full pointer-events-auto" src="{{ $video->embed_url }}"
                                    title="{{ $video->title }}" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- Infographics Section -->
            <section class="py-20 bg-white dark:bg-dark-800 border-y border-gray-100 dark:border-dark-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Infografis Keuangan</h2>
                        <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">Pahami data dan fakta keuangan dengan
                            lebih mudah.</p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-3">
                        @foreach($infographics as $infographic)
                            <a href="{{ $infographic->media_url }}" target="_blank"
                                class="group relative rounded-3xl overflow-hidden shadow-lg border border-slate-200 dark:border-dark-700 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 bg-white dark:bg-dark-900 block aspect-[3/4]">
                                <img src="{{ $infographic->media_url }}" alt="{{ $infographic->title }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-left">
                                    <h4
                                        class="text-white font-bold text-lg mb-1 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-75">
                                        {{ $infographic->title }}
                                    </h4>
                                    <p
                                        class="text-gray-300 text-xs line-clamp-2 mb-4 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-100">
                                        {{ $infographic->description }}
                                    </p>
                                    <div
                                        class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 delay-150">
                                        <span
                                            class="text-white font-bold text-[10px] bg-primary-600 px-4 py-1.5 rounded-full uppercase tracking-widest flex items-center w-fit">
                                            Buka Preview
                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </section>

            <!-- About Us Section -->
            <section id="about" class="py-24 bg-slate-50 dark:bg-dark-900 overflow-hidden relative">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div class="grid lg:grid-cols-2 gap-16 items-center">
                        <div class="relative">
                            <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-500/10 rounded-full blur-3xl">
                            </div>
                            <div
                                class="relative rounded-3xl overflow-hidden shadow-2xl bg-white dark:bg-dark-800 p-12 flex items-center justify-center min-h-[400px]">
                                <div class="absolute inset-0 bg-gradient-to-tr from-primary-600/5 to-transparent"></div>
                                <x-application-logo class="w-96 h-96 drop-shadow-2xl animate-float" />
                                <div
                                    class="absolute bottom-8 left-8 right-8 p-6 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20">
                                    <p class="text-gray-900 dark:text-white text-lg font-medium italic text-center">
                                        "Menciptakan generasi yang cerdas finansial di era digital."</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <span
                                class="inline-block px-4 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 text-xs font-bold uppercase tracking-widest mb-6">Tentang
                                Paul Quiz</span>
                            <h2
                                class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-8 leading-tight">
                                Transformasi Edukasi <span class="text-primary-600">Fintech</span> di Indonesia
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                                Paul Quiz hadir sebagai platform edukasi interaktif yang dirancang khusus untuk membantu
                                Anda memahami dunia teknologi finansial (Fintech) dengan cara yang menyenangkan dan
                                mudah dipahami.
                            </p>
                            <div class="space-y-6">
                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white dark:bg-dark-800 shadow-lg flex items-center justify-center text-primary-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Kurikulum Terupdate
                                        </h4>
                                        <p class="text-gray-500">Materi yang selalu relevan dengan perkembangan industri
                                            terkini.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-2xl bg-white dark:bg-dark-800 shadow-lg flex items-center justify-center text-primary-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">Interaktif &
                                            Menyenangkan</h4>
                                        <p class="text-gray-500">Belajar melalui video, infografis, dan kuis yang
                                            mengasyikkan.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="py-24 bg-white dark:bg-dark-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center max-w-3xl mx-auto mb-16">
                        <span
                            class="inline-block px-4 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest mb-4">Hubungi
                            Kami</span>
                        <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6">Punya Pertanyaan?</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400 italic font-medium">Bekerjasama untuk masa
                            depan ekonomi digital yang lebih baik.</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <!-- Contact Card 1 -->
                        <div
                            class="p-8 rounded-3xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-white/5 hover:border-primary-500 transition-all duration-300 group hover:shadow-xl hover:-translate-y-2">
                            <div
                                class="w-14 h-14 bg-white dark:bg-dark-800 rounded-2xl shadow-lg flex items-center justify-center text-primary-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Email</h4>
                            <p class="text-gray-500 mb-4">Kirimkan pertanyaan kapan saja.</p>
                            <a href="mailto:support@paulquiz.my.id"
                                class="text-primary-600 font-bold hover:underline">support@paulquiz.my.id</a>
                        </div>

                        <!-- Contact Card 2 -->
                        <div
                            class="p-8 rounded-3xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-white/5 hover:border-primary-500 transition-all duration-300 group hover:shadow-xl hover:-translate-y-2">
                            <div
                                class="w-14 h-14 bg-white dark:bg-dark-800 rounded-2xl shadow-lg flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 002-2H8a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">WhatsApp</h4>
                            <p class="text-gray-500 mb-4">Layanan cepat via chat.</p>
                            <a href="https://wa.me/+6289669720953"
                                class="text-emerald-600 font-bold hover:underline">+62 896 6972 0953</a>
                        </div>

                        <!-- Contact Card 3 -->
                        <div
                            class="p-8 rounded-3xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-white/5 hover:border-primary-500 transition-all duration-300 group hover:shadow-xl hover:-translate-y-2">
                            <div
                                class="w-14 h-14 bg-white dark:bg-dark-800 rounded-2xl shadow-lg flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Social Media</h4>
                            <p class="text-gray-500 mb-4">Ikuti kami di Facebook.</p>
                            <a href="#" class="text-blue-600 font-bold hover:underline">@paulquiz_edu</a>
                        </div>

                        <!-- Contact Card 4 -->
                        <div
                            class="p-8 rounded-3xl bg-slate-50 dark:bg-dark-900 border border-slate-100 dark:border-white/5 hover:border-primary-500 transition-all duration-300 group hover:shadow-xl hover:-translate-y-2">
                            <div
                                class="w-14 h-14 bg-white dark:bg-dark-800 rounded-2xl shadow-lg flex items-center justify-center text-pink-600 mb-6 group-hover:scale-110 transition-transform">
                                <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.266.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069v-2.163zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Instagram</h4>
                            <p class="text-gray-500 mb-4">Informasi terbaru setiap hari.</p>
                            <a href="#" class="text-pink-600 font-bold hover:underline">@paulquiz.official</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- OJK Link Section -->
            <section class="py-16 bg-white dark:bg-dark-800">
                <div class="max-w-4xl mx-auto px-4 text-center">
                    <div
                        class="p-8 rounded-3xl bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30">
                        <h3 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-4">Waspada Penipuan!</h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-6">
                            Pastikan Anda selalu mengakses informasi dari sumber resmi. Laporkan indikasi penipuan
                            transaksi keuangan.
                        </p>
                        <a href="https://iasc.ojk.go.id/" target="_blank"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl text-white bg-red-600 hover:bg-red-700 shadow-lg shadow-red-600/30 transition duration-300">
                            Pusat Penanganan Penipuan OJK
                        </a>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-white py-16 relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl">
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-3 gap-12 mb-12 border-b border-white/10 pb-12">
                    <div>
                        <div class="flex items-center mb-8 group">
                            <div class="relative w-24 h-24 md:w-28 md:h-28 mr-6 flex-shrink-0">
                                <div
                                    class="absolute inset-0 bg-primary-500/20 rounded-full blur-2xl opacity-40 group-hover:opacity-100 transition-opacity duration-500">
                                </div>
                                <x-application-logo
                                    class="relative w-full h-full fill-current text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.3)] transform group-hover:scale-110 transition-transform duration-500" />
                            </div>
                            <div class="flex flex-col">
                                <h4 class="text-3xl md:text-4xl font-black tracking-tighter uppercase leading-none">
                                    PAUL <span class="text-primary-500">QUIZ</span>
                                </h4>
                                <span
                                    class="text-[10px] text-primary-400 font-bold tracking-[0.2em] mt-2 uppercase opacity-80">Education
                                    Platform</span>
                            </div>
                        </div>
                        <p class="text-gray-400 leading-relaxed">
                            Platform edukasi terlengkap untuk memahami ekosistem Fintech di Indonesia. Belajar aman,
                            cerdas, dan menyenangkan.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold mb-6">Tautan Cepat</h4>
                        <ul class="space-y-4 text-gray-400">
                            <li><a href="#top"
                                    class="hover:text-primary-400 transition-colors flex items-center group/link">
                                    Beranda
                                    <svg class="w-3 h-3 ml-2 opacity-0 -translate-y-1 group-hover/link:opacity-100 group-hover/link:translate-y-0 transition-all"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </a></li>
                            <li><a href="#about" class="hover:text-primary-400 transition-colors">Tentang Kami</a></li>
                            <li><a href="/modules" class="hover:text-primary-400 transition-colors">Modul Belajar</a>
                            </li>
                            <li><a href="#contact" class="hover:text-primary-400 transition-colors">Kontak</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold mb-6">Navigasi</h4>
                        <ul class="space-y-4 text-gray-400">
                            <li><a href="/leaderboard" class="hover:text-primary-400 transition-colors">Leaderboard</a>
                            </li>
                            <li><a href="/login" class="hover:text-primary-400 transition-colors">Masuk Siswa</a></li>
                            <li><a href="/register" class="hover:text-primary-400 transition-colors">Daftar Akun</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row items-center justify-between text-gray-500 text-sm">
                    <p>&copy; {{ date('Y') }} Paul Quiz Education. All rights reserved.</p>
                    <div class="flex gap-6 mt-4 md:mt-0">
                        <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                        <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>