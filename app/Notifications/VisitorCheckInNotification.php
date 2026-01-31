<?php

namespace App\Notifications;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class VisitorCheckInNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $visitor;

    /**
     * Create a new notification instance.
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'visitor_id' => $this->visitor->id,
            'visitor_name' => $this->visitor->name,
            'message' => 'New visitor registered: ' . $this->visitor->name,
            'type' => 'visitor_created',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'visitor_id' => $this->visitor->id,
            'visitor_name' => $this->visitor->name,
            'check_in_time' => $this->visitor->check_in_time,
        ];
    }
}
