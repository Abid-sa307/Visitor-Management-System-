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
        // Database first; add 'mail' if you want emails too
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $v = $this->visitor;
        return [
            'type'        => 'visitor_created',
            'visitor_id'  => $v->id,
            'name'        => $v->name,
            'phone'       => $v->phone,
            'status'      => $v->status,
            'company_id'  => $v->company_id,
            'branch_id'   => $v->branch_id,
            'created_at'  => optional($v->created_at)->toDateTimeString(),
            'message'     => "New visitor registered: {$v->name}",
        ];
    }
}
