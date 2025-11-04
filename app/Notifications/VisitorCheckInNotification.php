<?php

namespace App\Notifications;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Visitor Check-in: ' . $this->visitor->name)
                    ->line('A new visitor has checked in:')
                    ->line('Name: ' . $this->visitor->name)
                    ->line('Email: ' . $this->visitor->email)
                    ->line('Phone: ' . $this->visitor->phone)
                    ->line('Company: ' . $this->visitor->company->name)
                    ->line('Check-in Time: ' . $this->visitor->check_in_time->format('Y-m-d H:i:s'))
                    ->action('View Visitor Details', url('/visitors/' . $this->visitor->id))
                    ->line('Thank you for using our application!');
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
