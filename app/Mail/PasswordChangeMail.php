<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $changeTime;

    public function __construct($userName, $changeTime = null)
    {
        $this->userName = $userName;
        $this->changeTime = $changeTime ?? now()->format('d/m/Y H:i:s');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thông báo đổi mật khẩu - Winstar',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-change',
        );
    }
} 