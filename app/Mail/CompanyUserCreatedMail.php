<?php

namespace App\Mail;

use App\Models\CompanyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompanyUserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $companyUser;
    public $password;

    public function __construct(CompanyUser $companyUser, $password = null)
    {
        $this->companyUser = $companyUser;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to ' . ($this->companyUser->company->name ?? 'Visitor Management System'))
                    ->view('emails.company-user-created')
                    ->with([
                        'companyUser' => $this->companyUser,
                        'password' => $this->password,
                        'company' => $this->companyUser->company
                    ]);
    }
}