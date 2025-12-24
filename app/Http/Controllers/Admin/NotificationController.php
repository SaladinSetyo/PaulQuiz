<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $users = User::all();
        // Use the Notification facade to send to multiple users efficiently
        Notification::send($users, new GeneralNotification($request->title, $request->message));

        return redirect()->route('admin.dashboard')->with('success', 'Notifikasi berhasil dikirim ke semua pengguna.');
    }
}
