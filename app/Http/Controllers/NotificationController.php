<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {

        // Default date range: last 7 days
        $fromDate = now()->subDays(6)->startOfDay();
        $toDate   = now()->endOfDay();

        // Get all notifications
        $notifications = Notification::where('type', 'Machine')->orderBy('id', 'desc')->paginate(10);

        // Mark all as read
        Notification::where('is_read', false)->update(['is_read' => true]);

        // Get active tab from request or default
        $activeTab = request()->get('tab', 'MaintenanceAlerts');

        // Get ICT notifications
        $ideal_cycle_time = Notification::where('type', 'ICT')
          ->whereBetween('created_at', [$fromDate, $toDate])
          ->orderBy('id', 'desc')->paginate(10);

        return view('admin.notifications', compact('notifications', 'activeTab', 'ideal_cycle_time'));
    }
}
