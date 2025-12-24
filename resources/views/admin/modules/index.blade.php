<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Modul') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Modul</h3>
                        </div>
                        <a href="{{ route('admin.modules.create') }}"
                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-primary-600/20 transition-all hover:-translate-y-0.5 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah Modul Baru
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                                    <th class="px-6 py-3 font-bold">ID</th>
                                    <th class="px-6 py-3 font-bold">Nama Modul</th>
                                    <th class="px-6 py-3 font-bold">Deskripsi</th>
                                    <th class="px-6 py-3 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($modules as $module)
                                    <tr
                                        class="bg-slate-50/50 dark:bg-dark-900/50 border border-slate-100 dark:border-white/5 rounded-2xl group hover:bg-white dark:hover:bg-dark-900 transition-all duration-300">
                                        <td class="px-6 py-4 rounded-l-2xl text-sm font-medium">
                                            #{{ $module->id }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">
                                                {{ $module->title }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                                {{ Str::limit($module->description, 50) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right rounded-r-2xl">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.modules.show', $module) }}"
                                                    class="p-2 text-gray-400 hover:text-emerald-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                    title="Kelola Modul">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.modules.edit', $module) }}"
                                                    class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                    title="Edit Modul">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                <form action="{{ route('admin.modules.destroy', $module) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus modul ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5"
                                                        title="Hapus Modul">
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
                                            Belum ada modul. Klik "Tambah Modul Baru" untuk memulai.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>