<?php

namespace App\Mail;

use App\Models\ScheduledEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ScheduledEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public ScheduledEmail $scheduledEmail;

    public User $recipient;

    public function __construct(ScheduledEmail $scheduledEmail, User $recipient)
    {
        $this->scheduledEmail = $scheduledEmail;
        $this->recipient = $recipient;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('info@germansuccess.com', 'GERMAN SUCCESS'),
            subject: $this->scheduledEmail->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.scheduled-email',
            with: [
                'name' => $this->recipient->name,
                'body' => $this->scheduledEmail->body,
            ],
        );
    }
}
