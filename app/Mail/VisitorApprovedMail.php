<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Visitor $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function build()
    {
        return $this->subject('Your Visit Has Been Approved')
            ->view('emails.visitor_approved')
            ->with([
                'visitor' => $this->visitor,
            ]);
    }
}
