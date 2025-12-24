<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Konten: ') }} {{ $content->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center mb-4">
                        <a href="{{ route('admin.modules.contents.index', $module) }}" class="text-blue-500 hover:underline flex items-center">
                            <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Daftar Konten
                        </a>
                    </div>
                    <div class="mb-4">
                        <h3 class="text-xl font-bold">{{ $content->title }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">Tipe: {{ ucfirst($content->type) }}</p>
                        @if ($content->body)
                            <p class="mt-2">{{ $content->body }}</p>
                        @endif
                        @if ($content->media_url)
                            <p class="mt-2">URL Media: <a href="{{ $content->media_url }}" target="_blank" class="text-blue-500 hover:underline">{{ $content->media_url }}</a></p>
                        @endif
                        <p class="mt-2">Urutan: {{ $content->order }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
