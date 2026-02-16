<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;

    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    public function build()
    {
        return $this->subject('New Visit Request: ' . $this->visitor->name . ' - ' . config('app.name'))
                   ->view('emails.visit-request');
    }
}
