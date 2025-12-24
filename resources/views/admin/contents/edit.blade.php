<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Konten untuk Modul: ') }} {{ $module->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center mb-4">
                        <a href="{{ route('admin.modules.contents.index', $module) }}"
                            class="text-blue-500 hover:underline flex items-center">
                            <svg class="w-4 h-4 me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Kembali ke Daftar Konten
                        </a>
                    </div>
                    <form action="{{ route('admin.modules.contents.update', [$module, $content]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Judul Konten')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title"
                                :value="old('title', $content->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="type" :value="__('Tipe Konten')" />
                            <select id="type" name="type"
                                class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="article" {{ old('type', $content->type) == 'article' ? 'selected' : '' }}>
                                    Artikel</option>
                                <option value="video" {{ old('type', $content->type) == 'video' ? 'selected' : '' }}>Video
                                </option>
                                <option value="infographic" {{ old('type', $content->type) == 'infographic' ? 'selected' : '' }}>Infografis</option>
                                <option value="quiz" {{ old('type', $content->type) == 'quiz' ? 'selected' : '' }}>Kuis
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                            <x-textarea-input id="description" class="block mt-1 w-full"
                                name="description">{{ old('description', $content->description) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="body" :value="__('Isi Konten (untuk Artikel)')" />
                            <x-textarea-input id="body" class="block mt-1 w-full"
                                name="body">{{ old('body', $content->body) }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('body')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="media_url" :value="__('URL Media (untuk Video/Infografis)')" />
                            <x-text-input id="media_url" class="block mt-1 w-full" type="text" name="media_url"
                                :value="old('media_url', $content->media_url)" />
                            <x-input-error :messages="$errors->get('media_url')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="order" :value="__('Urutan')" />
                            <x-text-input id="order" class="block mt-1 w-full" type="number" name="order"
                                :value="old('order', $content->order)" min="0" />
                            <x-input-error :messages="$errors->get('order')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <label for="is_featured" class="inline-flex items-center">
                                <input id="is_featured" type="checkbox"
                                    class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:bg-gray-900"
                                    name="is_featured" {{ old('is_featured', $content->is_featured) ? 'checked' : '' }}>
                                <span
                                    class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Tampilkan di Homepage (Unggulan)') }}</span>
                            </label>
                            <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Update Konten') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>