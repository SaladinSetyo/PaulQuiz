<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Kuis untuk Modul: ') }} {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-6">
                        <a href="{{ route('admin.modules.quizzes.index', $module) }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Kuis</h3>
                        <div class="ml-auto">
                            <a href="{{ route('admin.modules.quizzes.show', [$module, $quiz]) }}"
                                class="inline-flex items-center px-4 py-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-sm font-bold hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Kelola Pertanyaan
                            </a>
                        </div>
                    </div>
                    <form action="{{ route('admin.modules.quizzes.update', [$module, $quiz]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Kuis')" />
                            <x-text-input id="title" class="block mt-1 w-full rounded-xl" type="text" name="title"
                                :value="old('title', $quiz->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi')" />
                            <x-textarea-input id="description" class="block mt-1 w-full rounded-xl"
                                name="description">{{ old('description', $quiz->description) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div
                            class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-dark-700">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 rounded-xl px-8 py-3">
                                {{ __('Update Kuis') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>