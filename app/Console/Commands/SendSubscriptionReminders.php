<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionExpiringMail;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendSubscriptionReminders extends Command
{
    protected $signature = 'subscriptions:remind';

    protected $description = 'Send reminder emails for subscriptions expiring within 5 days';

    public function handle(): int
    {
        $expiringSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '>', now())
            ->where('ends_at', '<=', now()->addDays(5))
            ->where(function ($query) {
                $query->whereNull('last_reminder_sent_at')
                    ->orWhere('last_reminder_sent_at', '<', now()->startOfDay());
            })
            ->with(['user', 'plan'])
            ->get();

        $sentCount = 0;

        foreach ($expiringSubscriptions as $subscription) {
            if (! $subscription->user || ! $subscription->plan) {
                continue;
            }

            $daysRemaining = (int) now()->diffInDays($subscription->ends_at);

            try {
                Mail::mailer('zoho_sales')
                    ->to($subscription->user->email)
                    ->send(new SubscriptionExpiringMail(
                        $subscription->user,
                        $subscription,
                        $subscription->plan,
                        $daysRemaining
                    ));

                $subscription->update(['last_reminder_sent_at' => now()]);
                $sentCount++;
            } catch (\Throwable $e) {
                Log::error('Could not send subscription reminder', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Sent {$sentCount} subscription reminder emails.");

        return Command::SUCCESS;
    }
}
