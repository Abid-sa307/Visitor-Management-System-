<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;

    
    /**
     * Create a new message instance.
     *
     * @param Visitor $visitor
     */
    public function __construct(Visitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Visit Request Update - ' . config('app.name'))
                    ->view('emails.visitor-rejected');
    }
}
