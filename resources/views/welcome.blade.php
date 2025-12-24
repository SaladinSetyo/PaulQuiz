<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fintech Edu - Tingkatkan Literasi Keuangan Anda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <!-- Header -->
        <header class="fixed w-full top-0 z-50 transition-all duration-300 bg-white/80 dark:bg-dark-900/80 backdrop-blur-md border-b border-gray-100 dark:border-white/5">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-3 group">
                            <x-application-logo class="block h-10 w-auto fill-current text-primary-600 dark:text-primary-400 group-hover:scale-110 transition-transform duration-300" />
                            <span class="font-bold text-2xl tracking-tight text-gray-900 dark:text-white">Fintech<span class="text-primary-600 dark:text-primary-400">Edu</span></span>
                        </a>
                    </div>
                    <div class="hidden sm:flex items-center space-x-8">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-full shadow-lg shadow-primary-600/20 transition-all hover:shadow-primary-600/40 hover:-translate-y-0.5">Register</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                    <!-- Hamburger -->
                    <div class="-me-2 flex items-center sm:hidden">
                        <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow">
            <!-- Hero Section -->
            <!-- Hero Section -->
            <section class="relative pt-32 pb-20 overflow-hidden bg-gray-50 dark:bg-dark-900">
                <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                     <div class="absolute -top-[30%] -left-[10%] w-[60%] h-[60%] rounded-full bg-primary-400/10 blur-[100px]"></div>
                     <div class="absolute top-[20%] -right-[10%] w-[50%] h-[50%] rounded-full bg-secondary-400/10 blur-[100px]"></div>
                </div>

                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 shadow-sm mb-8 animate-fade-in-up">
                        <span class="flex h-2 w-2 relative mr-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary-500"></span>
                        </span>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Platform Literasi Keuangan #1</span>
                    </div>

                    <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">
                        Tingkatkan Pengetahuan <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-secondary-500">Literasi Keuangan Anda</span>
                    </h1>
                    
                    <p class="mt-4 max-w-2xl mx-auto text-xl text-gray-600 dark:text-gray-400 leading-relaxed">
                        Platform digital komprehensif untuk menguasai pengelolaan keuangan melalui konten edukatif, simulasi interaktif, dan alat perencanaan cerdas.
                    </p>

                    <div class="mt-10 flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all bg-primary-600 rounded-full hover:bg-primary-700 hover:shadow-lg hover:shadow-primary-600/30 hover:-translate-y-1">
                            Mulai Belajar Gratis
                        </a>
                        <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-700 dark:text-gray-200 transition-all bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 hover:shadow-lg hover:-translate-y-1">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <!-- Features Section -->
            <section id="features" class="py-24 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-dark-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Fitur Unggulan Kami</h2>
                        <p class="max-w-2xl mx-auto text-lg text-gray-600 dark:text-gray-400">
                            Dirancang untuk membantu Anda memahami dunia finansial dengan mudah, menyenangkan, dan efektif.
                        </p>
                    </div>
                    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Feature 1: Modules -->
                        <div class="group relative p-8 bg-gray-50 dark:bg-dark-800 rounded-3xl transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-black/50 hover:-translate-y-2 border border-gray-100 dark:border-dark-700">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-3xl transition-opacity"></div>
                            <div class="relative items-center text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494m-9-5.747h18"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Modul Terstruktur</h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Pelajari konsep fintech dari dasar hingga mahir melalui modul yang disusun secara sistematis dan mudah dipahami.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2: Quizzes -->
                        <div class="group relative p-8 bg-gray-50 dark:bg-dark-800 rounded-3xl transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-black/50 hover:-translate-y-2 border border-gray-100 dark:border-dark-700">
                            <div class="absolute inset-0 bg-gradient-to-br from-secondary-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-3xl transition-opacity"></div>
                            <div class="relative items-center text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-secondary-100 dark:bg-secondary-900/30 text-secondary-600 dark:text-secondary-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Kuis Interaktif</h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Uji pemahaman Anda setelah setiap modul dengan kuis yang menantang untuk memperkuat ingatan materi.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 3: Gamification -->
                        <div class="group relative p-8 bg-gray-50 dark:bg-dark-800 rounded-3xl transition-all duration-300 hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-black/50 hover:-translate-y-2 border border-gray-100 dark:border-dark-700">
                            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 rounded-3xl transition-opacity"></div>
                            <div class="relative items-center text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">Lacak Progres</h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Dapatkan poin dan lencana untuk setiap pencapaian, dan pantau perkembangan belajar Anda secara realtime.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-gray-500 dark:text-gray-400">
                &copy; {{ date('Y') }} Fintech Edu. All rights reserved.
            </div>
        </footer>
    </div>
</body>

</html>