<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Visitor;
use App\Models\Notification;

class NotificationHelper
{
    /**
     * Create a notification for a company
     */
    public static function createNotification(Company $company, Visitor $visitor = null, string $type, string $message): void
    {
        // Only create notification if company has notifications enabled
        if (!$company->visitor_notifications_enabled) {
            return;
        }

        Notification::create([
            'company_id' => $company->id,
            'visitor_id' => $visitor ? $visitor->id : null,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    /**
     * Create visitor created notification
     */
    public static function visitorCreated(Visitor $visitor): void
    {
        $company = $visitor->company;
        if (!$company) {
            return;
        }

        self::createNotification(
            $company,
            $visitor,
            Notification::TYPE_VISITOR_CREATED,
            "New visitor registered: {$visitor->name}"
        );
    }

    /**
     * Create visitor approved notification
     */
    public static function visitorApproved(Visitor $visitor): void
    {
        $company = $visitor->company;
        if (!$company) {
            return;
        }

        self::createNotification(
            $company,
            $visitor,
            Notification::TYPE_VISITOR_APPROVED,
            "Visitor approved: {$visitor->name}"
        );
    }

    /**
     * Create visitor check-in notification
     */
    public static function visitorCheckIn(Visitor $visitor): void
    {
        $company = $visitor->company;
        if (!$company) {
            return;
        }

        self::createNotification(
            $company,
            $visitor,
            Notification::TYPE_VISITOR_CHECK_IN,
            "Visitor checked in: {$visitor->name}"
        );
    }

    /**
     * Create visitor check-out notification
     */
    public static function visitorCheckOut(Visitor $visitor): void
    {
        $company = $visitor->company;
        if (!$company) {
            return;
        }

        self::createNotification(
            $company,
            $visitor,
            Notification::TYPE_VISITOR_CHECK_OUT,
            "Visitor checked out: {$visitor->name}"
        );
    }

    /**
     * Get unread notifications count for a company
     */
    public static function getUnreadCount(Company $company): int
    {
        return Notification::where('company_id', $company->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent notifications for a company
     */
    public static function getRecentNotifications(Company $company, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return Notification::with('visitor')
            ->where('company_id', $company->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark notifications as read for a company
     */
    public static function markAsRead(Company $company): int
    {
        return Notification::where('company_id', $company->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }
}
