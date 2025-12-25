<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            // Update only if last_seen_at is null or older than 2 minutes to reduce DB writes
            if (!$user->last_seen_at || $user->last_seen_at->lt(now()->subMinutes(2))) {
                $user->update(['last_seen_at' => now()]);
            }
        }
        return $next($request);
}
