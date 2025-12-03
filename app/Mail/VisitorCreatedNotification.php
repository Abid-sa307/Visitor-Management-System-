<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function build()
    {
        $companyName = $this->visitor->company->name ?? config('app.name');
        return $this->subject('New Visitor Registration - ' . $this->visitor->name . ' - ' . $companyName)
                   ->view('emails.visitor-created')
                   ->with(['visitor' => $this->visitor]);
    }
}
