<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pertanyaan: ') }} {{ Str::limit($question->question_text, 50) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center mb-4">
                        <a href="{{ route('admin.modules.quizzes.questions.index', [$module, $quiz]) }}" class="text-blue-500 hover:underline flex items-center">
                            <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Daftar Pertanyaan
                        </a>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold">{{ $question->question_text }}</h3>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('admin.modules.quizzes.questions.answers.index', [$module, $quiz, $question]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Kelola Jawaban
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
