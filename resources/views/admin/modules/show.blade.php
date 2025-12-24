<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Modul') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('admin.modules.index') }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $module->title }}</h3>
                            <p class="text-gray-500 dark:text-gray-400">{{ $module->description }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-4">
                        <a href="{{ route('admin.modules.quizzes.index', $module) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Kelola Kuis
                        </a>
                        <a href="{{ route('admin.modules.contents.index', $module) }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                            Kelola Konten
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>