<?php

namespace App\Mail;

use App\Models\Visitor;
use App\Models\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VisitorNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $visitor;
    public $companyUser;
    public $type;

    public function __construct(Visitor $visitor, CompanyUser $companyUser, $type = 'created')
    {
        $this->visitor = $visitor;
        $this->companyUser = $companyUser;
        $this->type = $type; // 'created' or 'approved'
    }

    public function build()
    {
        $subject = $this->type === 'approved' 
            ? 'Visitor Approved - ' . $this->visitor->name
            : 'New Visitor Registration - ' . $this->visitor->name;

        return $this->subject($subject)
                    ->view('emails.visitor-notification')
                    ->with([
                        'visitor' => $this->visitor,
                        'companyUser' => $this->companyUser,
                        'type' => $this->type,
                        'company' => $this->visitor->company,
                        'branch' => $this->visitor->branch
                    ]);
    }
}