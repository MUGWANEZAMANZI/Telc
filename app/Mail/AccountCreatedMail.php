<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.mailers.zoho_info.username') ?? config('mail.from.address'),
                config('mail.from.name') ?? 'GERMAN SUCCESS'
            ),
            subject: 'Willkommen bei GERMAN SUCCESS - Ihr Account wurde erstellt',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-email',
            with: [
                'name' => $this->user->name,
            ],
        );
    }
}
