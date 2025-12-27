<button @click="toggleTheme()"
    class="fixed bottom-6 right-6 z-50 p-4 rounded-full bg-white dark:bg-dark-800 border-2 border-gray-200 dark:border-dark-600 shadow-2xl hover:scale-110 smooth-transition hover:shadow-primary-500/50"
    aria-label="Toggle theme">
    <!-- Moon Icon (Show in Light Mode) -->
    <svg x-show="!isDark" class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>

    <!-- Sun Icon (Show in Dark Mode) -->
    <svg x-show="isDark" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
</button>