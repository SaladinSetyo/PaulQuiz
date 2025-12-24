<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Kuis (Global)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700/50 backdrop-blur-xl overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.dashboard') }}"
                                class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Semua Kuis</h3>
                                <p class="text-gray-500 dark:text-gray-400">Atur kuis dan pertanyaan di seluruh modul.
                                </p>
                            </div>
                        </div>

                        <a href="{{ route('admin.quizzes.create') }}"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-0.5 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Kuis
                        </a>
                    </div>

                    @if(session('success'))
                        <div
                            class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-600 text-sm font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                                    <th class="px-6 py-3 font-bold">Judul Kuis</th>
                                    <th class="px-6 py-3 font-bold">Modul</th>
                                    <th class="px-6 py-3 font-bold text-center">Soal</th>
                                    <th class="px-6 py-3 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr
                                        class="bg-slate-50/50 dark:bg-dark-900/50 border border-slate-100 dark:border-white/5 rounded-2xl group hover:bg-white dark:hover:bg-dark-900 transition-all duration-300">
                                        <td class="px-6 py-4 rounded-l-2xl">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center justify-center mr-4">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div
                                                        class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">
                                                        {{ $quiz->title }}</div>
                                                    <div class="text-xs text-gray-500 truncate max-w-xs">
                                                        {{ Str::limit($quiz->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $quiz->module->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 dark:bg-dark-800 text-gray-600 dark:text-gray-400">
                                                {{ $quiz->questions_count ?? $quiz->questions()->count() }} Soal
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right rounded-r-2xl">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.modules.quizzes.questions.index', [$quiz->module, $quiz]) }}"
                                                    class="p-2 text-gray-400 hover:text-blue-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                    title="Kelola Pertanyaan">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.quizzes.edit', $quiz) }}"
                                                    class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                    title="Edit Kuis">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                        title="Hapus Kuis">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada kuis. Klik "Tambah Kuis" untuk memulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $quizzes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>