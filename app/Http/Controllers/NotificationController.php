<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Company;
use App\Http\Controllers\NotificationHelper;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get notifications for the current company
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // For company users, get their company's notifications
        if ($user instanceof \App\Models\Company) {
            $company = $user;
        } else {
            // For admin users, you might want to handle differently
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = NotificationHelper::getRecentNotifications($company);
        $unreadCount = NotificationHelper::getUnreadCount($company);

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
        
        // For company users, mark their company's notifications as read
        if ($user instanceof \App\Models\Company) {
            $company = $user;
            $count = NotificationHelper::markAsRead($company);
            
            return response()->json([
                'success' => true,
                'marked_read' => $count
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get unread count
     */
    public function unreadCount(Request $request)
    {
        $user = auth()->user();
        
        // For company users, get their company's unread count
        if ($user instanceof \App\Models\Company) {
            $company = $user;
            $count = NotificationHelper::getUnreadCount($company);
            
            return response()->json(['unread_count' => $count]);
        }

        return response()->json(['unread_count' => 0]);
    }
}
