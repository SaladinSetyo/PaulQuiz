<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Http\Request;

class GlobalQuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('module')->withCount('questions')->latest()->paginate(10);
        return view('admin.quizzes.global_index', compact('quizzes'));
    }

    public function create()
    {
        $modules = Module::all();
        return view('admin.quizzes.global_create', compact('modules'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Quiz::create($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil dibuat!');
    }

    public function edit(Quiz $quiz)
    {
        $modules = Module::all();
        return view('admin.quizzes.global_edit', compact('quiz', 'modules'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $quiz->update($validated);

        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil diperbarui!');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Kuis berhasil dihapus!');
    }
}
