<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\Module;
use Illuminate\Http\Request;

class GlobalContentController extends Controller
{
    public function index(Request $request)
    {
        $query = Content::with('module');

        if ($request->has('type') && in_array($request->type, ['article', 'video', 'infographic', 'quiz'])) {
            $query->where('type', $request->type);
        }

        $contents = $query->latest()->paginate(15);

        return view('admin.contents.global_index', compact('contents'));
    }

    public function create()
    {
        $modules = Module::all();
        $quizzes = \App\Models\Quiz::all();
        return view('admin.contents.global_create', compact('modules', 'quizzes'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'is_featured' => $request->has('is_featured'),
        ]);

        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,video,infographic,quiz',
            'quiz_id' => 'required_if:type,quiz|nullable|exists:quizzes,id',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'media_url' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        Content::create($validated);

        return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil ditambahkan!');
    }

    public function edit(Content $content)
    {
        $modules = Module::all();
        $quizzes = \App\Models\Quiz::all();
        return view('admin.contents.global_edit', compact('content', 'modules', 'quizzes'));
    }

    public function update(Request $request, Content $content)
    {
        $request->merge([
            'is_featured' => $request->has('is_featured'),
        ]);

        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,video,infographic,quiz',
            'quiz_id' => 'required_if:type,quiz|nullable|exists:quizzes,id',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'media_url' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $content->update($validated);

        return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil diperbarui!');
    }

    public function destroy(Content $content)
    {
        $content->delete();
        return redirect()->route('admin.contents.index')->with('success', 'Konten berhasil dihapus!');
    }
}
