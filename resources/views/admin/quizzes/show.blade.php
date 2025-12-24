<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Kuis: ') }} {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('admin.modules.quizzes.index', $module) }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Kuis</h3>
                    </div>

                    <div
                        class="bg-slate-50 dark:bg-dark-900 p-6 rounded-3xl border border-slate-100 dark:border-white/5 mb-8">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $quiz->title }}</h4>
                        <p class="text-gray-600 dark:text-gray-400">{{ $quiz->description }}</p>
                    </div>

                    <div class="flex items-center justify-start">
                        <a href="{{ route('admin.modules.quizzes.questions.index', [$module, $quiz]) }}"
                            class="inline-flex items-center px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Kelola Pertanyaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>