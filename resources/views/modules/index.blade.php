<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Literasi Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-dark-800/50 border border-primary-100 dark:border-white/5 backdrop-blur-xl overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <a href="{{ url('/') }}"
                                class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Modul Pembelajaran</h3>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $modules->count() }} Modul
                            Tersedia</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($modules as $module)
                            <a href="{{ route('modules.show', $module) }}"
                                class="group block p-8 bg-white dark:bg-gradient-to-bl dark:from-dark-800 dark:to-dark-900 rounded-3xl shadow-sm hover:shadow-xl hover:shadow-primary-600/10 border border-primary-200 dark:border-white/10 transition-all duration-300 hover:-translate-y-1">
                                <div class="flex items-center justify-between mb-6">
                                    <span
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 group-hover:bg-primary-600 group-hover:text-white transition-all duration-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6.253v11.494m-9-5.747h18"></path>
                                        </svg>
                                    </span>
                                    <div class="flex items-center space-x-3">
                                        @if (Auth::check() && in_array($module->id, $solvedModuleIds))
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-500 border border-green-500/20">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Selesai
                                            </span>
                                        @endif
                                        <span
                                            class="text-sm font-medium text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">Akses
                                            &rarr;</span>
                                    </div>
                                </div>
                                <h4
                                    class="font-bold text-xl text-gray-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                    {{ $module->title }}
                                </h4>
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed line-clamp-3">
                                    {{ $module->description }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>