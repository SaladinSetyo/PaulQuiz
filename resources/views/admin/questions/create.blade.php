<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Pertanyaan Baru untuk Kuis: ') }} {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-8">
                        <a href="{{ route('admin.modules.quizzes.questions.index', [$module, $quiz]) }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Pertanyaan Baru</h3>
                    </div>
                    <form action="{{ route('admin.modules.quizzes.questions.store', [$module, $quiz]) }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <x-input-label for="question_text" :value="__('Teks Pertanyaan')" />
                            <x-textarea-input id="question_text" class="block mt-1 w-full rounded-xl"
                                name="question_text" required>{{ old('question_text') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label for="description" :value="__('Deskripsi/Penjelasan (Opsional)')" />
                            <x-textarea-input id="description" class="block mt-1 w-full rounded-xl"
                                name="description">{{ old('description') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Gunakan untuk memberikan penjelasan
                                lebih detail tentang pertanyaan atau jawaban yang benar.</p>
                        </div>

                        <div class="mt-12" x-data="{ 
                            answers: [
                                { text: '' },
                                { text: '' }
                            ],
                            addAnswer() {
                                this.answers.push({ text: '' });
                            },
                            removeAnswer(index) {
                                if (this.answers.length > 2) {
                                    this.answers.splice(index, 1);
                                }
                            }
                        }">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-white">Pilihan Jawaban</h4>
                                <button type="button" @click="addAnswer()"
                                    class="text-sm px-4 py-2 bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 rounded-xl hover:bg-primary-100 dark:hover:bg-primary-900/40 transition-colors font-bold flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Pilihan
                                </button>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(answer, index) in answers" :key="index">
                                    <div
                                        class="flex items-start gap-4 p-4 bg-slate-50/50 dark:bg-dark-900/50 border border-slate-100 dark:border-white/5 rounded-2xl group transition-all">
                                        <div class="pt-2">
                                            <div class="flex items-center">
                                                <input type="radio" name="correct_answer_index" :value="index" required
                                                    class="w-5 h-5 text-emerald-600 bg-white border-gray-300 focus:ring-emerald-500 dark:focus:ring-emerald-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                                    :checked="index === 0">
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <input type="text" :name="'answers[' + index + '][answer_text]'"
                                                class="block w-full border-slate-200 dark:border-dark-700 dark:bg-dark-900 dark:text-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-xl shadow-sm"
                                                placeholder="Ketik teks jawaban di sini..." x-model="answer.text"
                                                required>
                                        </div>

                                        <button type="button" @click="removeAnswer(index)" x-show="answers.length > 2"
                                            class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 italic flex items-center">
                                <svg class="w-4 h-4 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Pilih salah satu opsi sebagai jawaban yang benar menggunakan radio button.
                            </p>
                        </div>

                        <div
                            class="flex items-center justify-end mt-12 pt-6 border-t border-gray-100 dark:border-dark-700">
                            <x-primary-button
                                class="bg-emerald-600 hover:bg-emerald-700 rounded-xl px-8 py-3 shadow-lg shadow-emerald-500/20">
                                {{ __('Simpan Pertanyaan & Jawaban') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>