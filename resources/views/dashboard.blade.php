<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-dark-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-dark-700">
                <div class="relative p-8 text-gray-900 dark:text-gray-100">
                    <!-- Deco Blob -->
                    <div
                        class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-primary-500/10 blur-3xl pointer-events-none">
                    </div>

                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                            <div class="flex items-center gap-4">
                                <a href="{{ url('/') }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                                    <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                </a>
                                <div>
                                    <h3 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary-600 to-secondary-500">
                                        Selamat Datang, {{ Auth::user()->name }}!
                                    </h3>
                                    <p class="mt-1 text-gray-600 dark:text-gray-400 text-lg">
                                        Siap melanjutkan perjalanan literasi keuangan Anda?
                                    </p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300 font-semibold text-sm border border-primary-100 dark:border-primary-800 self-start md:self-center">
                                {{ Auth::user()->roles->first()->name ?? 'User' }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Points Card -->
                        <div
                            class="p-6 rounded-2xl bg-gradient-to-br from-primary-600 to-indigo-700 text-white shadow-lg shadow-primary-600/30 relative overflow-hidden group">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 transition-transform group-hover:scale-110">
                            </div>
                            <p class="text-primary-100 font-medium mb-1 relative z-10">Total Poin</p>
                            <p class="text-4xl font-bold relative z-10">{{ Auth::user()->points }}</p>
                        </div>

                        <!-- Progress Card (Placeholder) -->
                        <div
                            class="p-6 rounded-2xl bg-white dark:bg-dark-700 border border-gray-100 dark:border-dark-600 shadow-sm relative overflow-hidden group">
                            <p class="text-gray-500 dark:text-gray-400 font-medium mb-1">Status Belajar</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">Aktif</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('modules.index') }}"
                            class="inline-flex items-center px-8 py-4 text-base font-bold text-white transition-all bg-gray-900 dark:bg-white dark:text-gray-900 rounded-xl hover:shadow-lg hover:-translate-y-1">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Mulai Belajar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>