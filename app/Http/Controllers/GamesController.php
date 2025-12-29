<?php

namespace App\Http\Controllers;

use App\Models\GameStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller
{
    /**
     * Save game progress for authenticated users.
     */
    public function saveProgress(Request $request)
    {
        $validated = $request->validate([
            'balance' => 'required|numeric|min:0',
            'total_trades' => 'required|integer|min:0',
            'winning_trades' => 'required|integer|min:0',
            'best_streak' => 'required|integer|min:0',
            'total_profit' => 'required|numeric',
        ]);

        $stats = GameStat::updateOrCreate(
            ['user_id' => Auth::id()],
            $validated
        );

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get top 10 players leaderboard.
     */
    public function getLeaderboard()
    {
        $leaders = GameStat::with('user:id,name')
            ->orderBy('total_profit', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($stat) {
                return [
                    'id' => $stat->id,
                    'username' => $stat->user->name,
                    'total_profit' => (float) $stat->total_profit,
                    'total_trades' => $stat->total_trades,
                    'balance' => (float) $stat->balance,
                ];
            });

        return response()->json($leaders);
    }

    /**
     * Reset user's game stats (keep record, reset values).
     */
    public function resetProgress()
    {
        $stats = GameStat::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'balance' => 1000.00,
                'total_trades' => 0,
                'winning_trades' => 0,
                'best_streak' => 0,
                'total_profit' => 0.00,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress reset successfully',
            'data' => $stats
        ]);
    }
}
