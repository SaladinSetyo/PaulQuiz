<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\UserProgress; // Import UserProgress model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $modules = Module::all();
            $solvedModuleIds = [];

            if (Auth::check()) {
                $userId = Auth::id();
                // Get IDs of modules where the user has at least one quiz with a 100% score
                $solvedModuleIds = Module::whereHas('quizzes', function ($query) use ($userId) {
                    $query->whereHas('attempts', function ($q) use ($userId) {
                        $q->where('user_id', $userId)->where('score', 100);
                    });
                })->pluck('id')->toArray();
            }

            return view('modules.index', compact('modules', 'solvedModuleIds'))->render();
        } catch (\Throwable $e) {
            die("<pre>DEBUG ERROR (View/Controller): " . $e->getMessage() . "\nFILE: " . $e->getFile() . "\nLINE: " . $e->getLine() . "\nTRACE:\n" . $e->getTraceAsString() . "</pre>");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Admin only functionality - to be implemented later
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Admin only functionality - to be implemented later
    }

    /**
     * Display the specified resource.
     */
    public function show(Module $module)
    {
        try {
            // Restrict guest access: Only Module 1 ("Apa itu fintech") is public
            if (!Auth::check() && $module->id != 1) {
                return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu untuk mengakses modul ini.');
            }

            $module->load('contents', 'quizzes');

            // Record progress for non-quiz content items
            $isSolved = false;
            if (Auth::check()) {
                $userId = Auth::id();
                $isSolved = $module->quizzes()->whereHas('attempts', function ($query) use ($userId) {
                    $query->where('user_id', $userId)->where('score', 100);
                })->exists();

                foreach ($module->contents as $content) {
                    // Only track progress for non-quiz content
                    if ($content->type !== 'quiz') {
                        $progress = UserProgress::firstOrCreate(
                            ['user_id' => Auth::id(), 'content_id' => $content->id],
                            ['completed_at' => now(), 'score' => 0] // score 0 for non-quiz content
                        );

                        // Award points only if it's the first time completing this content
                        if ($progress->wasRecentlyCreated) {
                            Auth::user()->increment('points', 5); // Award 5 points for viewing content
                        }
                    }
                }
            }
            return view('modules.show', compact('module', 'isSolved'))->render();
        } catch (\Throwable $e) {
            die("<pre>DEBUG ERROR (Show Method): " . $e->getMessage() . "\nFILE: " . $e->getFile() . "\nLINE: " . $e->getLine() . "\nTRACE:\n" . $e->getTraceAsString() . "</pre>");
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Module $module)
    {
        // Admin only functionality - to be implemented later
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Module $module)
    {
        // Admin only functionality - to be implemented later
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Module $module)
    {
        // Admin only functionality - to be implemented later
    }
}
