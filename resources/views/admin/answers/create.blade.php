<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Jawaban Baru untuk Pertanyaan: ') }} {{ Str::limit($question->question_text, 50) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('admin.modules.quizzes.questions.answers.index', [$module, $quiz, $question]) }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Jawaban Baru</h3>
                    </div>
                    <form
                        action="{{ route('admin.modules.quizzes.questions.answers.store', [$module, $quiz, $question]) }}"
                        method="POST">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="answer_text" :value="__('Teks Jawaban')" />
                            <x-textarea-input id="answer_text" class="block mt-1 w-full rounded-xl"
                                name="answer_text">{{ old('answer_text') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('answer_text')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="is_correct" class="inline-flex items-center cursor-pointer">
                                <div class="relative">
                                    <input id="is_correct" type="checkbox" class="sr-only peer" name="is_correct"
                                        value="1" {{ old('is_correct') ? 'checked' : '' }}>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600">
                                    </div>
                                </div>
                                <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Jawaban
                                    Benar?</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_correct')" class="mt-2" />
                        </div>

                        <div
                            class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100 dark:border-dark-700">
                            <x-primary-button class="bg-emerald-600 hover:bg-emerald-700 rounded-xl px-8 py-3">
                                {{ __('Simpan Jawaban') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>