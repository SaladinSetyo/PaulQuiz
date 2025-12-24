<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-6">
                        <a href="{{ url('/') }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Selamat Datang di Admin Panel!
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400">Anda dapat mengelola berbagai aspek aplikasi di
                                sini.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Card: Manajemen Modul -->
                        <div
                            class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">Manajemen Modul</h4>
                            <p class="text-slate-600 dark:text-gray-400 mb-4">
                                Tambah, edit, atau hapus modul pembelajaran.
                            </p>
                            <a href="{{ route('admin.modules.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition-colors shadow-lg shadow-blue-600/20">
                                Kelola Modul
                            </a>
                        </div>

                        <!-- Card: Manajemen Kuis & Pertanyaan -->
                        <div
                            class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">Manajemen Kuis</h4>
                            <p class="text-slate-600 dark:text-gray-400 mb-4">
                                Buat, modifikasi, dan atur kuis serta pertanyaan.
                            </p>
                            <a href="{{ route('admin.quizzes.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition-colors shadow-lg shadow-green-600/20">
                                Kelola Kuis
                            </a>
                        </div>

                        <!-- Card: Manajemen Konten -->
                        <div
                            class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">Manajemen Konten</h4>
                            <p class="text-slate-600 dark:text-gray-400 mb-4">
                                Kelola artikel, video, dan infografis.
                            </p>
                            <a href="{{ route('admin.contents.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20">
                                Kelola Konten
                            </a>
                        </div>

                        <!-- Card: Lihat Statistik Pengguna -->
                        <div
                            class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">Statistik Pengguna</h4>
                            <p class="text-slate-600 dark:text-gray-400 mb-4">
                                Lihat progres dan leaderboard pengguna.
                            </p>
                            <a href="{{ route('leaderboard.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 transition-colors shadow-lg shadow-yellow-600/20">
                                Lihat Leaderboard
                            </a>
                        </div>

                        <!-- Card: Broadcast Notifikasi -->
                        <div
                            class="p-6 bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="font-bold text-xl mb-2 text-gray-900 dark:text-white">Broadcast Notifikasi</h4>
                            <p class="text-slate-600 dark:text-gray-400 mb-4">
                                Kirim notifikasi ke semua pengguna terdaftar.
                            </p>
                            <a href="{{ route('admin.notifications.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition-colors shadow-lg shadow-red-600/20">
                                Broadcast
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>