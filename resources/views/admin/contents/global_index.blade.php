<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Konten Global') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-800 border border-primary-100 dark:border-dark-700/50 backdrop-blur-xl overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('admin.dashboard') }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                                <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </a>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Semua Konten</h3>
                                <p class="text-gray-500 dark:text-gray-400">Kelola artikel, video, dan infografis di seluruh modul.</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.contents.create') }}" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-primary-600/20 transition-all hover:-translate-y-0.5 mr-4 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                                Tambah Konten
                            </a>
                            <a href="{{ route('admin.contents.index') }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ !request('type') ? 'bg-primary-100 text-primary-700' : 'bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">Semua</a>
                            <a href="{{ route('admin.contents.index', ['type' => 'infographic']) }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ request('type') === 'infographic' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">Infografis</a>
                            <a href="{{ route('admin.contents.index', ['type' => 'video']) }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ request('type') === 'video' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">Video</a>
                            <a href="{{ route('admin.contents.index', ['type' => 'article']) }}" class="px-4 py-2 rounded-xl text-sm font-medium {{ request('type') === 'article' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 dark:bg-dark-700 text-gray-600 dark:text-gray-400 hover:bg-gray-200' }}">Artikel</a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-2xl text-green-600 text-sm font-medium flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-separate border-spacing-y-2">
                            <thead>
                                <tr class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                                    <th class="px-6 py-3 font-bold">Judul Konten</th>
                                    <th class="px-6 py-3 font-bold">Tipe</th>
                                    <th class="px-6 py-3 font-bold">Modul</th>
                                    <th class="px-6 py-3 font-bold text-center">Homepage</th>
                                    <th class="px-6 py-3 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contents as $content)
                                    <tr class="bg-slate-50/50 dark:bg-dark-900/50 border border-slate-100 dark:border-white/5 rounded-2xl group hover:bg-white dark:hover:bg-dark-900 transition-all duration-300">
                                        <td class="px-6 py-4 rounded-l-2xl">
                                            <div class="flex items-center">
                                                <div class="w-16 h-12 flex-shrink-0 bg-gray-100 dark:bg-dark-800 rounded-lg overflow-hidden mr-4 border border-gray-200 dark:border-white/5">
                                                    @if($content->type === 'video')
                                                        @php
                                                            $youtube_id = null;
                                                            if (preg_match('/(?:youtu.be\/|youtube.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([a-zA-Z0-9_-]{11})/', $content->media_url, $matches)) {
                                                                $youtube_id = $matches[1];
                                                            }
                                                        @endphp
                                                        @if($youtube_id)
                                                            <img src="https://img.youtube.com/vi/{{ $youtube_id }}/mqdefault.jpg" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center bg-purple-100 text-purple-600">
                                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                            </div>
                                                        @endif
                                                    @elseif($content->type === 'infographic')
                                                        <img src="{{ $content->media_url }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
                                                    @elseif($content->type === 'article')
                                                        <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-600">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v4a1 1 0 001 1h4"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10M7 16h6"></path></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-600">
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white group-hover:text-primary-600 transition-colors">{{ $content->title }}</div>
                                                    <div class="text-xs text-gray-500 truncate max-w-xs">{{ $content->description ?: Str::limit(strip_tags($content->body ?? $content->media_url), 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $badgeColors = [
                                                    'article' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                                                    'video' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400',
                                                    'infographic' => 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400',
                                                    'quiz' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400',
                                                ];
                                            @endphp
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider {{ $badgeColors[$content->type] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $content->type }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $content->module->title }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($content->is_featured)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                    Unggulan
                                                </span>
                                            @else
                                                <span class="text-gray-300 dark:text-gray-700">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right rounded-r-2xl">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('admin.contents.edit', $content) }}" class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.contents.destroy', $content) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus konten ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors bg-white dark:bg-dark-800 rounded-lg shadow-sm border border-gray-100 dark:border-white/5">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                            Belum ada konten yang sesuai dengan filter ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8">
                        {{ $contents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
