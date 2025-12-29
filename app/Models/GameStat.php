<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameStat extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_trades',
        'winning_trades',
        'best_streak',
        'total_profit',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'total_profit' => 'decimal:2',
    ];

    /**
     * Get the user that owns the game stats.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
