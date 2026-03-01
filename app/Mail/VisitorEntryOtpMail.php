<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorEntryOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;
    public $otp;

    /**
     * Create a new message instance.
     *
     * @param Visitor $visitor
     * @param string $otp
     */
    public function __construct(Visitor $visitor, string $otp)
    {
        $this->visitor = $visitor;
        $this->otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your Entry OTP - ' . config('app.name'))
                    ->view('emails.visitor-entry-otp');
    }
}
