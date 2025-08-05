<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Attendance;

class AttendanceThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $attendance;
    public $type; // 'check_in' hoặc 'check_out'

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Attendance $attendance, string $type = 'check_in')
    {
        $this->user = $user;
        $this->attendance = $attendance;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'check_in' 
            ? 'Cảm ơn bạn đã điểm danh vào - ' . config('app.name')
            : 'Cảm ơn bạn đã điểm danh ra - ' . config('app.name');

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance.thank-you',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
