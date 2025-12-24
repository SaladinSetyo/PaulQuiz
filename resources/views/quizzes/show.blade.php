<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="mb-8">
                        <a href="{{ route('modules.show', $quiz->module_id) }}"
                            class="inline-flex p-2 text-gray-400 hover:text-primary-600 transition-colors bg-white dark:bg-dark-900 rounded-xl border border-gray-100 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Berhasil!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Perhatian!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @if(Auth::check() && isset($canAttempt))
                        @if ($hasPerfectScore)
                            <div class="mb-8 p-4 bg-indigo-50 border border-indigo-200 rounded-lg text-indigo-700">
                                <p class="font-bold text-lg">🎉 Selamat! Anda sudah mencapai nilai sempurna (100%).</p>
                                <p>Mode Review: Anda hanya dapat melihat soal-soal ini kembali.</p>
                            </div>
                        @elseif (!$canAttempt)
                            <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-700">
                                <p class="font-bold text-lg">⚠️ Batas Percobaan Habis</p>
                                <p>Anda telah menggunakan kesempatan mencoba sebanyak 3 kali.</p>
                            </div>
                        @else
                            <div class="mb-6 flex items-center justify-between">
                                <span
                                    class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                                    Percobaan ke-{{ $attemptsCount + 1 }} dari 3
                                </span>
                            </div>
                        @endif
                    @endif

                    @if(Auth::check() && isset($attempts) && $attempts->isNotEmpty())
                        <div
                            class="mb-8 bg-slate-50 dark:bg-dark-900 rounded-xl p-6 border border-slate-200 dark:border-dark-700">
                            <h4 class="font-bold text-lg mb-4 text-gray-800 dark:text-gray-200">Riwayat Percobaan Anda</h4>
                            <div class="space-y-3">
                                @foreach($attempts as $attempt)
                                    <div
                                        class="flex justify-between items-center bg-white dark:bg-dark-800 p-3 rounded-lg shadow-sm border border-gray-100 dark:border-white/5">
                                        <span
                                            class="text-sm text-gray-600 dark:text-gray-400">{{ $attempt->created_at->format('d M Y, H:i') }}</span>
                                        <span
                                            class="font-bold {{ $attempt->score == 100 ? 'text-green-600' : ($attempt->score >= 70 ? 'text-blue-600' : 'text-red-600') }}">
                                            Skor: {{ $attempt->score }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <h3 class="text-2xl font-bold mb-4">{{ $quiz->title }}</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-8">{{ $quiz->description }}</p>

                    <form action="{{ route('quizzes.submit', $quiz) }}" method="POST">
                        @csrf
                        <div class="space-y-8">
                            @foreach ($quiz->questions as $index => $question)
                                <div
                                    class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-600 rounded-3xl transition-all shadow-sm hover:shadow-md hover:border-primary-500/30">
                                    <p class="font-bold text-lg text-gray-900 dark:text-white mb-6 flex items-start">
                                        <span
                                            class="flex-shrink-0 flex items-center justify-center w-8 h-8 rounded-lg bg-primary-50 dark:bg-dark-700 shadow-sm text-primary-600 font-bold mr-4 border border-primary-100 dark:border-dark-600">{{ $index + 1 }}</span>
                                        <span class="mt-1">{{ $question->question_text }}</span>
                                    </p>
                                    @if($question->description)
                                        <div
                                            class="ml-12 mb-6 p-4 bg-primary-50/50 dark:bg-primary-900/10 rounded-2xl border border-primary-100/50 dark:border-primary-900/20 text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                            {!! nl2br(e($question->description)) !!}
                                        </div>
                                    @endif
                                    <div class="space-y-3 ml-12">
                                        @foreach ($question->answers as $answer)
                                            <label
                                                class="group relative flex items-center cursor-pointer {{ isset($canAttempt) && !$canAttempt ? 'opacity-70 pointer-events-none' : '' }}">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                    value="{{ $answer->id }}" class="peer sr-only" {{ isset($canAttempt) && !$canAttempt ? 'disabled' : '' }}>
                                                <div
                                                    class="w-full p-4 rounded-xl bg-white dark:bg-dark-800 border-2 border-transparent group-hover:border-primary-100 dark:group-hover:border-primary-900 transition-all shadow-sm peer-checked:border-primary-600 peer-checked:bg-primary-50 dark:peer-checked:bg-primary-900/20 peer-checked:[&_.radio-circle]:bg-primary-600 peer-checked:[&_.radio-circle]:border-primary-600 peer-checked:[&_.radio-check]:opacity-100">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="radio-circle w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 mr-4 transition-all flex items-center justify-center shadow-sm">
                                                            <svg class="radio-check w-3.5 h-3.5 text-white opacity-0 transition-opacity"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="3" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        </div>
                                                        <span
                                                            class="text-gray-700 dark:text-gray-300 font-medium group-hover:text-primary-700 dark:group-hover:text-primary-300 transition-colors text-lg">{{ $answer->answer_text }}</span>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            @if(!Auth::check() || (isset($canAttempt) && $canAttempt))
                                <button type="submit"
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-md transform hover:-translate-y-0.5 transition-all">
                                    Selesai & Kirim Jawaban
                                </button>
                            @else
                                <button type="button" disabled
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-gray-400 bg-gray-200 cursor-not-allowed dark:bg-gray-700 dark:text-gray-500 w-full justify-center">
                                    {{ $hasPerfectScore ? 'Kuis Selesai (Nilai Sempurna)' : 'Batas Percobaan Habis' }} --
                                    Mode Review
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>