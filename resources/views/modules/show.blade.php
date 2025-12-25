<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-dark-800/50 border border-primary-100 dark:border-white/5 backdrop-blur-xl overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 md:p-8 text-gray-900 dark:text-gray-100">
                    <div class="mb-8">
                        <a href="{{ route('modules.index') }}"
                            class="inline-flex p-2 text-gray-400 hover:text-primary-600 transition-colors bg-white dark:bg-dark-900 rounded-xl border border-gray-100 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="max-w-4xl mx-auto">
                        <div class="mb-10 text-center">
                            <h3
                                class="text-3xl md:text-4xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 flex items-center justify-center gap-3">
                                {{ $module->title }}
                                @if (Auth::check())
                                    @if ($isSolved)
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-green-500 text-white shadow-lg shadow-green-500/30 ring-4 ring-green-500/10 mb-2">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Selesai
                                            </span>
                                            <span class="text-xs font-medium text-green-600 dark:text-green-400">Modul sudah
                                                terselesaikan</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-500 text-white shadow-lg shadow-amber-500/30 ring-4 ring-amber-500/10 mb-2">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Belum Selesai
                                            </span>
                                            <span class="text-xs font-medium text-amber-600 dark:text-amber-400">Kuis belum
                                                dikerjakan</span>
                                        </div>
                                    @endif
                                @endif
                            </h3>
                            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                                {{ $module->description }}
                            </p>
                        </div>

                        <div class="space-y-12">
                            @forelse ($module->contents as $content)
                                <div
                                    class="bg-white dark:bg-dark-800 rounded-3xl p-8 border border-primary-100 dark:border-dark-600 shadow-sm hover:shadow-md transition-shadow">
                                    <h4 class="font-bold text-2xl text-gray-900 dark:text-white mb-6 flex items-center">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-primary-100 text-primary-700 text-sm mr-3 font-bold">{{ $loop->iteration }}</span>
                                        {{ $content->title }}
                                    </h4>

                                    @if($content->description)
                                        <div
                                            class="mb-6 p-5 bg-primary-50/50 dark:bg-primary-900/10 rounded-2xl border border-primary-100/50 dark:border-primary-900/20 text-gray-700 dark:text-gray-300 text-base leading-relaxed">
                                            {!! nl2br(e($content->description)) !!}
                                        </div>
                                    @endif

                                    <div
                                        class="prose prose-lg dark:prose-invert max-w-none text-gray-800 dark:text-gray-200">
                                        @if ($content->type === 'article')
                                            <div>{!! $content->body !!}</div>
                                        @elseif ($content->type === 'video')
                                            <div class="aspect-w-16 aspect-h-9 rounded-xl overflow-hidden shadow-lg bg-black">
                                                <iframe class="w-full h-full" src="{{ $content->embed_url }}" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                        @elseif ($content->type === 'infographic')
                                            <img src="{{ $content->media_url }}" alt="{{ $content->title }}"
                                                class="w-full rounded-xl shadow-md transform hover:scale-[1.01] transition-transform duration-500">
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="text-center py-12 bg-primary-50 dark:bg-dark-800 rounded-3xl border border-dashed border-primary-200 dark:border-dark-600">
                                    <p class="text-gray-600 dark:text-gray-400">Konten untuk modul ini akan segera hadir!
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if($module->quizzes->isNotEmpty())
                            <div class="mt-16 pt-10 border-t border-primary-200 dark:border-dark-700 text-center">
                                @if(Auth::check() && $isSolved)
                                    <div
                                        class="bg-emerald-500/10 border border-emerald-500/20 rounded-3xl p-8 mb-8 inline-block">
                                        <h4 class="text-2xl font-bold mb-2 text-emerald-600 dark:text-emerald-400">Selamat! 🎉
                                        </h4>
                                        <p class="text-emerald-700 dark:text-emerald-300">Anda telah menyelesaikan kuis untuk
                                            modul ini dengan nilai sempurna.</p>
                                    </div>
                                @else
                                    <h4 class="text-2xl font-bold mb-2"></h4>
                                    <p class="text-gray-600 dark:text-gray-400 mb-8"></p>
                                @endif

                                <div class="flex flex-wrap justify-center gap-4">
                                    @foreach($module->quizzes as $quiz)
                                        <div class="flex flex-col items-center w-full">
                                            @if($quiz->description)
                                                <div
                                                    class="mb-8 text-base text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed text-center">
                                                    {!! nl2br(e($quiz->description)) !!}
                                                </div>
                                            @endif

                                            @if(!(Auth::check() && $isSolved))
                                                <h4 class="text-2xl font-bold mb-2">Sudah Paham Materinya?</h4>
                                                <p class="text-gray-600 dark:text-gray-400 mb-6">Uji pemahaman Anda sekarang dan
                                                    dapatkan poin!</p>
                                            @endif

                                            <a href="{{ route('quizzes.show', $quiz) }}"
                                                class="inline-flex items-center px-8 py-4 border border-transparent text-base font-bold rounded-full text-white bg-gradient-to-r from-secondary-500 to-emerald-600 hover:from-secondary-600 hover:to-emerald-700 shadow-lg hover:shadow-secondary-500/30 hover:-translate-y-1 transition-all {{ Auth::check() && $isSolved ? 'opacity-75 grayscale-[0.5]' : '' }}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $isSolved ? 'Ulangi Kuis:' : 'Ambil Kuis:' }} {{ $quiz->title }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>