<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module; // Import Module model
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module)
    {
        $quizzes = $module->quizzes;
        return view('admin.quizzes.index', compact('module', 'quizzes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Module $module)
    {
        return view('admin.quizzes.create', compact('module'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $module->quizzes()->create($validated);

        return redirect()->route('admin.modules.quizzes.index', $module)->with('success', 'Kuis berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module, Quiz $quiz)
    {
        return view('admin.quizzes.show', compact('module', 'quiz'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module, Quiz $quiz)
    {
        return view('admin.quizzes.edit', compact('module', 'quiz'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.modules.quizzes.index', $module)->with('success', 'Kuis berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, Quiz $quiz)
    {
        $quiz->delete();

        return redirect()->route('admin.modules.quizzes.index', $module)->with('success', 'Kuis berhasil dihapus!');
    }
}
