<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    use AuthorizesRequests;

    public function adminDashboard()
    {
        $this->authorize('dashboard.show');

        return Inertia::render('Admin/Index');
    }

    // push notification
    public function notifications()
    {
        $user = auth()->user();
        $user->unreadNotifications->markAsRead();

        return inertia('Admin/Notifications/NotificationsPage', [
            'notifications' => $user->notifications()->latest()->get()
        ]);
    }
}
