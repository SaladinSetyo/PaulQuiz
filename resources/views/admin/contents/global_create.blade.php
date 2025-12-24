<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Konten Baru (Global)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-dark-800 overflow-hidden shadow-sm sm:rounded-3xl">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-4 mb-6">
                        <a href="{{ route('admin.contents.index') }}"
                            class="p-2 text-gray-400 hover:text-primary-600 transition-colors bg-slate-50 dark:bg-dark-900 rounded-xl border border-slate-200 dark:border-white/5 shadow-sm group">
                            <svg class="w-6 h-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Konten Baru</h3>
                    </div>

                    <form action="{{ route('admin.contents.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="module_id" :value="__('Pilih Modul')" />
                                <select id="module_id" name="module_id" required
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-xl shadow-sm">
                                    <option value="" disabled selected>Pilih Modul...</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->id }}" {{ old('module_id') == $module->id ? 'selected' : '' }}>{{ $module->title }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('module_id')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="type" :value="__('Tipe Konten')" />
                                <select id="type" name="type" required
                                    class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-xl shadow-sm">
                                    <option value="article" {{ old('type') == 'article' ? 'selected' : '' }}>Artikel
                                    </option>
                                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="infographic" {{ old('type') == 'infographic' ? 'selected' : '' }}>
                                        Infografis</option>
                                    <option value="quiz" {{ old('type') == 'quiz' ? 'selected' : '' }}>Kuis</option>
                                </select>
                                <x-input-error :messages="$errors->get('type')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="title" :value="__('Judul Konten')" />
                            <x-text-input id="title" class="block mt-1 w-full rounded-xl" type="text" name="title"
                                :value="old('title')" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                            <x-textarea-input id="description" class="block mt-1 w-full rounded-xl"
                                name="description">{{ old('description') }}</x-textarea-input>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                            <p class="mt-1 text-sm text-gray-500">Akan ditampilkan sebagai ringkasan di halaman detail
                                modul.</p>
                        </div>

                        <div x-data="{ type: '{{ old('type', 'article') }}' }" x-on:change="type = $event.target.value">
                            <template x-if="type === 'article'">
                                <div>
                                    <x-input-label for="body" :value="__('Isi Artikel')" />
                                    <x-textarea-input id="body" class="block mt-1 w-full rounded-xl" name="body"
                                        rows="10">{{ old('body') }}</x-textarea-input>
                                    <x-input-error :messages="$errors->get('body')" class="mt-2" />
                                </div>
                            </template>

                            <template x-if="type === 'video' || type === 'infographic'">
                                <div>
                                    <x-input-label for="media_url" :value="__('URL Media (YouTube Embed / Link Gambar)')" />
                                    <x-text-input id="media_url" class="block mt-1 w-full rounded-xl" type="text"
                                        name="media_url" :value="old('media_url')" />
                                    <x-input-error :messages="$errors->get('media_url')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500" x-show="type === 'video'">Gunakan link embed
                                        YouTube, misalnya: https://www.youtube.com/embed/a81bXkES-gg</p>
                                </div>
                            </template>

                            <template x-if="type === 'quiz'">
                                <div>
                                    <x-input-label for="quiz_id" :value="__('Pilih Kuis')" />
                                    <select id="quiz_id" name="quiz_id" :required="type === 'quiz'"
                                        class="block w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 rounded-xl shadow-sm">
                                        <option value="" disabled selected>Pilih Kuis...</option>
                                        @foreach($quizzes as $quiz)
                                            <option value="{{ $quiz->id }}" {{ old('quiz_id') == $quiz->id ? 'selected' : '' }}>
                                                {{ $quiz->title }} ({{ $quiz->module->title }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('quiz_id')" class="mt-2" />
                                    <p class="mt-1 text-sm text-gray-500">Kuis ini akan ditautkan ke konten unggulan.
                                    </p>
                                </div>
                            </template>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                            <div>
                                <x-input-label for="order" :value="__('Urutan')" />
                                <x-text-input id="order" class="block mt-1 w-full rounded-xl" type="number" name="order"
                                    :value="old('order', 0)" min="0" />
                                <x-input-error :messages="$errors->get('order')" class="mt-2" />
                            </div>

                            <div class="pt-6">
                                <label for="is_featured" class="inline-flex items-center cursor-pointer">
                                    <div class="relative">
                                        <input id="is_featured" type="checkbox" class="sr-only peer" name="is_featured"
                                            value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600">
                                        </div>
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">Tampilkan di
                                        Homepage (Unggulan)</span>
                                </label>
                                <x-input-error :messages="$errors->get('is_featured')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end pt-6 border-t border-gray-100 dark:border-dark-700">
                            <x-primary-button class="rounded-xl px-8 py-3">
                                {{ __('Simpan Konten Baru') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>