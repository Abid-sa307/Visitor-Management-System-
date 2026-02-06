<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current company
     */
    /**
     * Get notifications for the current company
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $query = \App\Models\Notification::with(['company', 'visitor'])
            ->latest();

        // Filter by company unless Super Admin
        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $notifications = $query->take(10)->get();
        
        // Count unread
        $unreadQuery = \App\Models\Notification::where('is_read', false);
        if (!$user->isSuperAdmin()) {
            $unreadQuery->where('company_id', $user->company_id);
        }
        $unreadCount = $unreadQuery->count();

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

        $query = \App\Models\Notification::where('is_read', false);
        
        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $query->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'marked_read' => 0
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

        $query = \App\Models\Notification::where('is_read', false);

        if (!$user->isSuperAdmin()) {
            $query->where('company_id', $user->company_id);
        }

        $count = $query->count();
        
        return response()->json(['unread_count' => $count]);
    }
}
