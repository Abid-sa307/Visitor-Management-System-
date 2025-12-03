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
        $companyName = $this->visitor->company->name ?? config('app.name');
        return $this->subject('Your Visit to ' . $companyName . ' Has Been Approved')
            ->view('emails.visitor_approved')
            ->with([
                'visitor' => $this->visitor,
            ]);
    }
}
