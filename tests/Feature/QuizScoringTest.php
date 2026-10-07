<?php

use App\Models\Answer;
use App\Models\Module;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

/**
 * Create a quiz where every question has one correct and one wrong answer.
 *
 * @return array{0: Quiz, 1: array<int, int>, 2: array<int, int>} [quiz, correct answer ids, wrong answer ids] keyed by question id
 */
function createScoringQuiz(int $questions = 3): array
{
    $module = Module::create(['title' => 'Modul Uji']);
    $quiz = Quiz::create(['module_id' => $module->id, 'title' => 'Kuis Uji']);

    $correct = [];
    $wrong = [];
    for ($i = 1; $i <= $questions; $i++) {
        $question = Question::create(['quiz_id' => $quiz->id, 'question_text' => "Soal {$i}"]);
        $correct[$question->id] = Answer::create(['question_id' => $question->id, 'answer_text' => 'Benar', 'is_correct' => true])->id;
        $wrong[$question->id] = Answer::create(['question_id' => $question->id, 'answer_text' => 'Salah', 'is_correct' => false])->id;
    }

    return [$quiz, $correct, $wrong];
}

/**
 * Build a submission that answers the first $correctCount questions correctly and the rest wrong.
 */
function scoringAnswers(array $correct, array $wrong, int $correctCount): array
{
    $answers = [];
    foreach (array_keys($correct) as $index => $questionId) {
        $answers[$questionId] = $index < $correctCount ? $correct[$questionId] : $wrong[$questionId];
    }

    return ['answers' => $answers];
}

test('first attempt awards the quiz score as points', function () {
    [$quiz, $correct, $wrong] = createScoringQuiz();
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 2));

    expect($user->fresh()->points)->toEqual(67);
});

test('a retry that does not beat the best score awards no points', function () {
    [$quiz, $correct, $wrong] = createScoringQuiz();
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 2));
    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 1));
    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 2));

    expect($user->fresh()->points)->toEqual(67);
    expect($user->quizAttempts()->count())->toBe(3);
});

test('a better retry only awards the improvement over the best score', function () {
    [$quiz, $correct, $wrong] = createScoringQuiz();
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 1));
    $this->actingAs($user)->post(route('quizzes.submit', $quiz), scoringAnswers($correct, $wrong, 3));

    expect($user->fresh()->points)->toEqual(100);
});

test('correct answers from another quiz do not count', function () {
    [$quiz, $correct] = createScoringQuiz();
    [, $otherCorrect] = createScoringQuiz();
    $user = User::factory()->create();

    $answers = array_combine(array_keys($correct), array_values($otherCorrect));
    $response = $this->actingAs($user)->post(route('quizzes.submit', $quiz), ['answers' => $answers]);

    $response->assertSessionHas('success', 'Kuis telah diselesaikan! Skor Anda: 0%');
    expect($user->fresh()->points)->toEqual(0);
});
