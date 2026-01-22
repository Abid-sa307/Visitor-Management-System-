<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Visitor;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class GoogleNotificationService
{
    /**
     * Send notification to company users via Google (if enabled)
     */
    public function sendNotification(Company $company, string $type, string $message, Visitor $visitor = null)
    {
        // Check if visitor notifications are enabled for this company
        if (!$company->enable_visitor_notifications) {
            return false;
        }

        try {
            // Get all users for this company
            $users = User::where('company_id', $company->id)->get();
            
            foreach ($users as $user) {
                // Create database notification
                $this->createDatabaseNotification($company, $user, $type, $message, $visitor);
                
                // Send Google notification (using FCM or other Google service)
                $this->sendGooglePushNotification($user, $type, $message, $visitor);
            }
            
            Log::info("Notifications sent for company {$company->id}, type: {$type}");
            return true;
            
        } catch (\Exception $e) {
            Log::error("Failed to send notifications: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create database notification
     */
    private function createDatabaseNotification(Company $company, User $user, string $type, string $message, Visitor $visitor = null)
    {
        $notification = Notification::create([
            'company_id' => $company->id,
            'visitor_id' => $visitor ? $visitor->id : null,
            'type' => $type,
            'message' => $message,
            'is_read' => false,
        ]);

        // Also create Laravel's built-in notification
        $user->notify(new \App\Notifications\VisitorNotification($notification));
    }

    /**
     * Send Google push notification (placeholder for FCM implementation)
     */
    private function sendGooglePushNotification(User $user, string $type, string $message, Visitor $visitor = null)
    {
        // This is where you would integrate with Firebase Cloud Messaging (FCM)
        // or other Google notification services
        
        // For now, we'll just log it
        Log::info("Google notification would be sent to user {$user->id}: {$message}");
        
        // Example FCM implementation (you'll need to add FCM package):
        /*
        $fcmToken = $user->fcm_token; // You'll need to add this field to users table
        
        if ($fcmToken) {
            $notification = [
                'title' => $this->getNotificationTitle($type),
                'body' => $message,
                'data' => [
                    'type' => $type,
                    'visitor_id' => $visitor ? $visitor->id : null,
                    'company_id' => $company->id,
                ]
            ];
            
            // Use FCM service to send notification
            // This requires installing firebase/php-jwt and guzzlehttp/guzzle
        }
        */
    }

    /**
     * Get notification title based on type
     */
    private function getNotificationTitle(string $type): string
    {
        return match($type) {
            'visit_form_submitted' => 'Visit Form Submitted',
            'visitor_approved' => 'Visitor Approved',
            'security_check_in' => 'Security Check-In',
            'security_check_out' => 'Security Check-Out',
            'visitor_mark_in' => 'Visitor Marked In',
            'visitor_mark_out' => 'Visitor Marked Out',
            default => 'Visitor Notification',
        };
    }

    /**
     * Send notification for visit form submission
     */
    public function sendVisitFormNotification(Company $company, Visitor $visitor, bool $isPublicForm = false)
    {
        $formType = $isPublicForm ? 'Public' : 'Internal';
        $message = "{$formType} visit form submitted for {$visitor->name}";
        
        return $this->sendNotification($company, 'visit_form_submitted', $message, $visitor);
    }

    /**
     * Send notification for visitor approval
     */
    public function sendApprovalNotification(Company $company, Visitor $visitor)
    {
        $message = "Visitor {$visitor->name} has been approved";
        
        return $this->sendNotification($company, 'visitor_approved', $message, $visitor);
    }

    /**
     * Send notification for security check-in
     */
    public function sendSecurityCheckInNotification(Company $company, Visitor $visitor)
    {
        $message = "Security check-in completed for {$visitor->name}";
        
        return $this->sendNotification($company, 'security_check_in', $message, $visitor);
    }

    /**
     * Send notification for security check-out
     */
    public function sendSecurityCheckOutNotification(Company $company, Visitor $visitor)
    {
        $message = "Security check-out completed for {$visitor->name}";
        
        return $this->sendNotification($company, 'security_check_out', $message, $visitor);
    }

    /**
     * Send notification for visitor mark in
     */
    public function sendMarkInNotification(Company $company, Visitor $visitor)
    {
        $message = "Visitor {$visitor->name} marked in";
        
        return $this->sendNotification($company, 'visitor_mark_in', $message, $visitor);
    }

    /**
     * Send notification for visitor mark out
     */
    public function sendMarkOutNotification(Company $company, Visitor $visitor)
    {
        $message = "Visitor {$visitor->name} marked out";
        
        return $this->sendNotification($company, 'visitor_mark_out', $message, $visitor);
    }
}
