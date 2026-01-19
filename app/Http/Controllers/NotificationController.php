<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current company
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user || !$user->company_id) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = $user->notifications()->latest()->take(10)->get();
        $unreadCount = $user->unreadNotifications()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notifications as read
     */
    public function markAsRead(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['success' => false]);
        }

        $user->unreadNotifications->markAsRead();
        
        return response()->json([
            'success' => true,
            'marked_read' => $user->unreadNotifications()->count()
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['unread_count' => 0]);
        }

        $count = $user->unreadNotifications()->count();
        
        return response()->json(['unread_count' => $count]);
    }
}
