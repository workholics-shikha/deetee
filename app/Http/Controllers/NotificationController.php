<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
   
    public function index()
    {
        // Get all notifications
        $notifications = Notification::orderBy('id', 'desc')->paginate(10);

        // Mark all as read
        Notification::where('is_read', false)->update(['is_read' => true]);

        // Get active tab from request or default
        $activeTab = request()->get('tab', 'MaintenanceAlerts');

        return view('admin.notifications', compact('notifications', 'activeTab'));
    }
}
