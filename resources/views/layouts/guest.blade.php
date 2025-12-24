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
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div
        class="relative min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-50 dark:bg-dark-900 selection:bg-primary-500 selection:text-white overflow-hidden">
        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-primary-500/10 blur-3xl"></div>
            <div class="absolute top-[40%] -right-[10%] w-[40%] h-[40%] rounded-full bg-secondary-500/10 blur-3xl">
            </div>
        </div>

        <div class="mb-8 flex flex-col items-center">
            <a href="/" class="flex flex-col items-center group gap-4">
                <x-application-logo
                    class="w-auto h-24 fill-current text-primary-600 dark:text-primary-400 group-hover:scale-105 transition-transform duration-300 drop-shadow-xl" />
                <span class="font-bold text-3xl tracking-tight text-gray-900 dark:text-white">Paul
                    <span class="text-primary-600 dark:text-primary-400">Quiz</span></span>
            </a>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-dark-800 shadow-xl overflow-hidden sm:rounded-2xl border border-gray-100 dark:border-dark-800 relative z-10">
            {{ $slot }}
        </div>
    </div>
</body>

</html>