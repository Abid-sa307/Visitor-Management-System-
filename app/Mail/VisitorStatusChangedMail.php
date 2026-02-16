<?php

namespace App\Mail;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;
    public $status;
    public $isCompanyUser;

    public function __construct(Visitor $visitor, string $status, bool $isCompanyUser = false)
    {
        $this->visitor = $visitor;
        $this->status = strtolower($status);
        $this->isCompanyUser = $isCompanyUser;
    }

    public function build()
    {
        $subject = $this->isCompanyUser 
            ? "Visitor Status Update: {$this->visitor->name} - {$this->status}"
            : "Your visit has been " . ($this->status === 'approved' ? 'approved' : $this->status);
            
        return $this->subject($subject . ' - ' . config('app.name'))
                   ->view('emails.visitor-status-changed');
    }
}
