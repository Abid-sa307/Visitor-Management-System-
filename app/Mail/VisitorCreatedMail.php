<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Visitor $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function build()
    {
        return $this->subject('Visitor Check-in Confirmation')
            ->view('emails.visitor_created')
            ->with([
                'visitor' => $this->visitor,
            ]);
    }
}
