<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Trader Panic - Pro</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            background-color: #0b0e11;
            color: #eaecef;
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        ::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        ::-webkit-scrollbar-track {
            background: #0b0e11;
        }

        ::-webkit-scrollbar-thumb {
            background: #2b3139;
            border-radius: 2px;
        }

        .crs-crosshair {
            cursor: crosshair;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ===== SMOOTH ANIMATION SYSTEM ===== */
        /* Base smooth transitions */
        .smooth-transition {
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        .smooth-transition-fast {
            transition: all 0.15s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        .smooth-color {
            transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }

        .smooth-transform {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .smooth-opacity {
            transition: opacity 0.2s ease-in-out;
        }

        /* Price Flash Animations (Pintu Pro Style) */
        @keyframes flashUp {
            0% {
                background-color: rgba(14, 203, 129, 0);
            }

            30% {
                background-color: rgba(14, 203, 129, 0.25);
            }

            100% {
                background-color: rgba(14, 203, 129, 0);
            }
        }

        @keyframes flashDown {
            0% {
                background-color: rgba(246, 70, 93, 0);
            }

            30% {
                background-color: rgba(246, 70, 93, 0.25);
            }

            100% {
                background-color: rgba(246, 70, 93, 0);
            }
        }

        .price-flash-up {
            animation: flashUp 0.6s ease-out;
        }

        .price-flash-down {
            animation: flashDown 0.6s ease-out;
        }

        /* Smooth number transitions */
        .number-update {
            transition: transform 0.2s ease-out;
        }

        .number-update:active {
            transform: scale(1.05);
        }

        /* Smooth hover effects */
        .hover-lift {
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }

        .hover-lift:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Fade in animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Slide in from right */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .slide-in-right {
            animation: slideInRight 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        /* Custom scrollbar smooth */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #2b3139 #14161b;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #14161b;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #2b3139;
            border-radius: 3px;
            transition: background 0.2s ease;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #3b4149;
        }

        /* Mobile tabs visibility */
        @media (min-width: 1024px) {
            .mobile-tabs-container {
                display: none !important;
            }
        }
    </style>
</head>

<body class="flex flex-col h-screen bg-[#0b0e11] text-[#eaecef] select-none text-xs overflow-hidden">

    <!-- HEADER -->
