<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module, Quiz $quiz, Question $question)
    {
        $answers = $question->answers;
        return view('admin.answers.index', compact('module', 'quiz', 'question', 'answers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Module $module, Quiz $quiz, Question $question)
    {
        return view('admin.answers.create', compact('module', 'quiz', 'question'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        $question->answers()->create($validated);

        return redirect()->route('admin.modules.quizzes.questions.answers.index', [$module, $quiz, $question])->with('success', 'Jawaban berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module, Quiz $quiz, Question $question, Answer $answer)
    {
        return view('admin.answers.show', compact('module', 'quiz', 'question', 'answer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module, Quiz $quiz, Question $question, Answer $answer)
    {
        return view('admin.answers.edit', compact('module', 'quiz', 'question', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, Quiz $quiz, Question $question, Answer $answer)
    {
        $validated = $request->validate([
            'answer_text' => 'required|string',
            'is_correct' => 'boolean',
        ]);

        $answer->update($validated);

        return redirect()->route('admin.modules.quizzes.questions.answers.index', [$module, $quiz, $question])->with('success', 'Jawaban berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, Quiz $quiz, Question $question, Answer $answer)
    {
        $answer->delete();

        return redirect()->route('admin.modules.quizzes.questions.answers.index', [$module, $quiz, $question])->with('success', 'Jawaban berhasil dihapus!');
    }
}
