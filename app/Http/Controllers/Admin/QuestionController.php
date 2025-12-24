<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Module $module, Quiz $quiz)
    {
        $questions = $quiz->questions;
        return view('admin.questions.index', compact('module', 'quiz', 'questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Module $module, Quiz $quiz)
    {
        return view('admin.questions.create', compact('module', 'quiz'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Module $module, Quiz $quiz)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
            'correct_answer_index' => 'required|integer',
        ]);

        \DB::transaction(function () use ($validated, $quiz) {
            $question = $quiz->questions()->create([
                'question_text' => $validated['question_text'],
                'description' => $validated['description'],
            ]);

            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => ($index == $validated['correct_answer_index']),
                ]);
            }
        });

        return redirect()->route('admin.modules.quizzes.questions.index', [$module, $quiz])->with('success', 'Pertanyaan dan Jawaban berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module, Quiz $quiz, Question $question)
    {
        $question->load('answers');
        return view('admin.questions.show', compact('module', 'quiz', 'question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module, Quiz $quiz, Question $question)
    {
        $question->load('answers');
        return view('admin.questions.edit', compact('module', 'quiz', 'question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module, Quiz $quiz, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'description' => 'nullable|string',
            'answers' => 'required|array|min:2',
            'answers.*.answer_text' => 'required|string',
            'answers.*.is_correct' => 'nullable|boolean',
            'correct_answer_index' => 'required|integer',
        ]);

        \DB::transaction(function () use ($validated, $question) {
            $question->update([
                'question_text' => $validated['question_text'],
                'description' => $validated['description'],
            ]);

            // Delete old answers and recreate. Simple and clean.
            $question->answers()->delete();

            foreach ($validated['answers'] as $index => $answerData) {
                $question->answers()->create([
                    'answer_text' => $answerData['answer_text'],
                    'is_correct' => ($index == $validated['correct_answer_index']),
                ]);
            }
        });

        return redirect()->route('admin.modules.quizzes.questions.index', [$module, $quiz])->with('success', 'Pertanyaan dan Jawaban berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module, Quiz $quiz, Question $question)
    {
        $question->delete();

        return redirect()->route('admin.modules.quizzes.questions.index', [$module, $quiz])->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
