<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;

class VisitorNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $databaseNotification;

    public function __construct($databaseNotification)
    {
        $this->databaseNotification = $databaseNotification;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'company_id' => $this->databaseNotification->company_id,
            'visitor_id' => $this->databaseNotification->visitor_id,
            'type' => $this->databaseNotification->type,
            'message' => $this->databaseNotification->message,
            'created_at' => $this->databaseNotification->created_at,
        ];
    }
}
