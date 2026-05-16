<?php

namespace App\Http\Controllers;

class NotificationController extends Controller
{

    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
