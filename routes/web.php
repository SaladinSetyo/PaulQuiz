<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\LeaderboardController; // Add this line
use App\Http\Controllers\Admin\ModuleController as AdminModuleController; // Alias Admin Module Controller
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $infographics = \App\Models\Content::where('type', 'infographic')
        ->orderBy('is_featured', 'desc')
        ->latest()
        ->take(3)
        ->get();
    $videos = \App\Models\Content::where('type', 'video')
        ->orderBy('is_featured', 'desc')
        ->latest()
        ->take(3)
        ->get();
    $firstModule = \App\Models\Module::first();
    $featuredQuiz = \App\Models\Content::where('type', 'quiz')
        ->where('is_featured', true)
        ->with('module')
        ->latest()
        ->first();

    $isFeaturedQuizSolved = false;
    if (Auth::check() && $featuredQuiz && $featuredQuiz->quiz_id) {
        $isFeaturedQuizSolved = \App\Models\QuizAttempt::where('user_id', Auth::id())
            ->where('quiz_id', $featuredQuiz->quiz_id)
            ->where('score', 100)
            ->exists();
    }

    return view('homepage', compact('infographics', 'videos', 'firstModule', 'featuredQuiz', 'isFeaturedQuizSolved'));
})->name('homepage');

Route::get('/dashboard', function () {
    return redirect('/'); // Redirect authenticated users to the new homepage
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Leaderboard Route
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // User Statistics Route
    Route::get('/users/{user}/stats', [ProfileController::class, 'showStats'])->name('users.stats');


    // Notification Routes
    Route::get('/notifications/check', [NotificationController::class, 'check'])->name('notifications.check');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Public Routes (Accessible by Guests)
Route::resource('modules', ModuleController::class)->only(['index', 'show']);

// Quiz Routes (Public for Guest Access)
Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('modules', AdminModuleController::class);
    Route::resource('contents', \App\Http\Controllers\Admin\GlobalContentController::class);
    Route::resource('quizzes', \App\Http\Controllers\Admin\GlobalQuizController::class);
    Route::resource('modules.quizzes', \App\Http\Controllers\Admin\QuizController::class);
    Route::resource('modules.quizzes.questions', \App\Http\Controllers\Admin\QuestionController::class);
    Route::resource('modules.quizzes.questions.answers', \App\Http\Controllers\Admin\AnswerController::class);
    Route::resource('modules.contents', \App\Http\Controllers\Admin\ContentController::class);

    // Notification Routes
    Route::get('/notifications/create', [\App\Http\Controllers\Admin\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'store'])->name('notifications.store');
}); // Closing the admin route group

Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user-dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');
});
