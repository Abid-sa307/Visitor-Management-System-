<?php

namespace App\Notifications;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VisitorCreated extends Notification
{
    use Queueable;

    public function __construct(public Visitor $visitor) {}

    public function via(object $notifiable): array
    {
        // Temporarily disable database notifications to focus on browser notifications
        return [];
    }

    public function toDatabase(object $notifiable): array
    {
        $v = $this->visitor;
        return [
            'type'        => 'visitor_created',
            'visitor_id'  => $v->id,
            'message'     => "New visitor registered: {$v->name}",
            'is_read'     => false,
        ];
    }
}
