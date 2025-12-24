<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Content;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module)
    {
        $contents = $module->contents;
        return view('admin.contents.index', compact('module', 'contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Module $module)
    {
        return view('admin.contents.create', compact('module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,video,infographic,quiz',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'media_url' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        $module->contents()->create($validated);

        return redirect()->route('admin.modules.contents.index', $module)->with('success', 'Konten berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module, Content $content)
    {
        return view('admin.contents.show', compact('module', 'content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module, Content $content)
    {
        return view('admin.contents.edit', compact('module', 'content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, Content $content)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:article,video,infographic,quiz',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'media_url' => 'nullable|url',
            'order' => 'integer|min:0',
            'is_featured' => 'boolean',
        ]);

        $validated['is_featured'] = $request->has('is_featured');

        $content->update($validated);

        return redirect()->route('admin.modules.contents.index', $module)->with('success', 'Konten berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, Content $content)
    {
        $content->delete();

        return redirect()->route('admin.modules.contents.index', $module)->with('success', 'Konten berhasil dihapus!');
    }
}
