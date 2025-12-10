<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyText;
    public $logo;
    public $company;
    public $company_address;
    public $company_email;

    public function __construct($subject, $body, $logo = null)
    {
        $this->subjectText = $subject;
        $this->bodyText = $body;
        $this->logo = $logo ?? asset('storage/images/logo.jpg');
        $this->company = config('app.name', 'ComitsBD');
        $this->company_address = config('app.address', 'H 375, 3rd Floor, R 28, Mohakhali DOHS, Bangladesh');
        $this->company_email = config('mail.from.address', 'support@comitsbd.com');
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->view('emails.default')
            ->with([
                'subject' => $this->subjectText,
                'body' => $this->bodyText,
                'logo' => $this->logo,
                'company' => $this->company,
                'company_address' => $this->company_address,
                'company_email' => $this->company_email,
            ]);
    }
}

