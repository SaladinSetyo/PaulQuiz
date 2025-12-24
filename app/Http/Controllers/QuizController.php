<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Answer;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function show(Quiz $quiz)
    {
        // Restrict guest access: Only quizzes in Module 1 are public
        if (!Auth::check() && $quiz->module_id != 1) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengerjakan kuis ini.');
        }

        $quiz->load('questions.answers');

        $canAttempt = true;
        $attemptsCount = 0;
        $hasPerfectScore = false;
        $attempts = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $attempts = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $attemptsCount = $attempts->count();
            $hasPerfectScore = $attempts->where('score', 100)->isNotEmpty();

            if ($hasPerfectScore || $attemptsCount >= 3) {
                $canAttempt = false;
            }
        }

        return view('quizzes.show', compact('quiz', 'canAttempt', 'attemptsCount', 'hasPerfectScore', 'attempts'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $attempts = QuizAttempt::where('user_id', $user->id)
                ->where('quiz_id', $quiz->id)
                ->get();

            $hasPerfectScore = $attempts->where('score', 100)->isNotEmpty();
            $attemptsCount = $attempts->count();

            if ($hasPerfectScore) {
                return redirect()->route('quizzes.show', $quiz)->with('error', 'Anda sudah mendapatkan nilai sempurna! Tidak dapat mengambil kuis lagi.');
            }

            if ($attemptsCount >= 3) {
                return redirect()->route('quizzes.show', $quiz)->with('error', 'Batas percobaan (3 kali) sudah habis.');
            }
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:answers,id',
        ]);

        $correctAnswers = Answer::whereIn('id', $validated['answers'])
            ->where('is_correct', true)
            ->count();

        $totalQuestions = $quiz->questions->count();
        $score = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;

        if (Auth::check()) {
            $attempt = QuizAttempt::create([
                'user_id' => Auth::id(),
                'quiz_id' => $quiz->id,
                'score' => round($score),
            ]);

            // Award points to the user
            $user = Auth::user();
            $user->increment('points', round($score)); // Add quiz score to user's total points
        }

        return redirect()->route('quizzes.show', $quiz)->with('success', 'Kuis telah diselesaikan! Skor Anda: ' . round($score) . '%');
    }
}
