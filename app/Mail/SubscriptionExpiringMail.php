<?php

namespace App\Mail;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionExpiringMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public Subscription $subscription;

    public Plan $plan;

    public int $daysRemaining;

    public function __construct(User $user, Subscription $subscription, Plan $plan, int $daysRemaining)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->plan = $plan;
        $this->daysRemaining = $daysRemaining;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.mailers.zoho_sales.username') ?? config('mail.from.address'),
                config('mail.from.name') ?? 'GERMAN SUCCESS'
            ),
            subject: "Ihr GERMAN SUCCESS Abo läuft in {$this->daysRemaining} Tagen ab!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-expiring',
            with: [
                'name' => $this->user->name,
                'planName' => $this->plan->name,
                'daysRemaining' => $this->daysRemaining,
                'endsAt' => $this->subscription->ends_at->format('d.m.Y'),
            ],
        );
    }
}
