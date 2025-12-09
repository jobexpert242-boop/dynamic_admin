<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DefaultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $logo;
    public $company;
    public $company_address;
    public $company_email;

    public function __construct($subject, $body, $logo = null)
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->logo = $logo;
        $this->company = config('app.name');
        $this->company_address = config('app.address', 'H 375, 3rd Floor, R 28, Mohakhali DOHS, Bangladesh');
        $this->company_email = config('mail.from.address', 'support@comitsbd.com');
    }

    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails.default')
            ->with([
                'subject' => $this->subject,
                'body' => $this->body,
                'logo' => $this->logo,
                'company' => $this->company,
                'company_address' => $this->company_address,
                'company_email' => $this->company_email,
            ]);
    }
}
