<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Statistik Pengguna: ') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-4">Ringkasan Statistik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Poin</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $user->points }}</p>
                        </div>
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Konten Selesai</p>
                            <p class="text-3xl font-bold text-green-600">{{ $user->userProgress->count() }}</p>
                        </div>
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-lg text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kuis Diambil</p>
                            <p class="text-3xl font-bold text-purple-600">{{ $user->quizAttempts->count() }}</p>
                        </div>
                    </div>

                    <h4 class="text-xl font-bold mb-4">Progres Konten</h4>
                    @if ($user->userProgress->isNotEmpty())
                        <div class="overflow-x-auto mb-8">
                            <table class="min-w-full divide-y divide-primary-200 dark:divide-dark-700 rounded-t-2xl overflow-hidden">
                                <thead class="bg-primary-600 dark:bg-dark-800 text-white">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Modul
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Konten
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Tipe
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Selesai Pada
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-dark-800 divide-y divide-slate-200 dark:divide-dark-700">
                                    @foreach ($user->userProgress as $progress)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $progress->content->module->title ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $progress->content->title ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ ucfirst($progress->content->type ?? 'N/A') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $progress->completed_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 mb-8">Belum ada progres konten.</p>
                    @endif

                    <h4 class="text-xl font-bold mb-4">Histori Kuis</h4>
                    @if ($user->quizAttempts->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-primary-200 dark:divide-dark-700 rounded-t-2xl overflow-hidden">
                                <thead class="bg-primary-600 dark:bg-dark-800 text-white">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Kuis
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Skor
                                        </th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-dark-800 divide-y divide-slate-200 dark:divide-dark-700">
                                    @foreach ($user->quizAttempts as $attempt)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->quiz->title ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $attempt->score }}%</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $attempt->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400">Belum ada riwayat kuis.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>