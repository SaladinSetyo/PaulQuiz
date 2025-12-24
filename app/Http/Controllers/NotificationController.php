<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
        }

        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
        return back();
    }

    public function check(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['count' => 0, 'latest' => null]);
        }

        $count = $user->unreadNotifications->count();
        $latest = $user->unreadNotifications->first();

        return response()->json([
            'count' => $count,
            'latest' => $latest ? [
                'id' => $latest->id,
                'title' => $latest->data['title'] ?? 'Notification',
                'message' => $latest->data['message'] ?? '',
                'created_at_human' => $latest->created_at->diffForHumans(),
                'created_at' => $latest->created_at->toIso8601String()
            ] : null
        ]);
    }
}
