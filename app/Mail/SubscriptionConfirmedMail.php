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

class SubscriptionConfirmedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public Subscription $subscription;

    public Plan $plan;

    public function __construct(User $user, Subscription $subscription, Plan $plan)
    {
        $this->user = $user;
        $this->subscription = $subscription;
        $this->plan = $plan;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('sales@germansuccess.com', 'GERMAN SUCCESS'),
            subject: 'Ihr Abo bei GERMAN SUCCESS ist aktiv!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-confirmed',
            with: [
                'name' => $this->user->name,
                'planName' => $this->plan->name,
                'pricePaid' => number_format((float) $this->subscription->price_paid, 2, ',', '.'),
                'durationMonths' => $this->subscription->duration_months,
                'endsAt' => $this->subscription->ends_at?->format('d.m.Y') ?? 'Unbegrenzt',
                'provider' => ucfirst($this->subscription->provider),
            ],
        );
    }
}
