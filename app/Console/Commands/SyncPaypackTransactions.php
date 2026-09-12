<?php

namespace App\Console\Commands;

use App\Mail\SubscriptionConfirmedMail;
use App\Models\Subscription;
use App\Services\PaypackService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SyncPaypackTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paypack:sync-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check pending Paypack transactions and events to update subscription statuses in database';

    /**
     * Execute the console command.
     */
    public function handle(PaypackService $paypackService): int
    {
        $this->info('Starting Paypack pending transactions sync...');

        // 1. Fetch pending Paypack subscriptions from database
        $pendingSubs = Subscription::query()
            ->with(['user', 'plan'])
            ->where('provider', 'paypack')
            ->where('status', 'pending')
            ->get();

        $processedCount = 0;

        foreach ($pendingSubs as $sub) {
            $ref = $sub->provider_order_id;
            if (! $ref) {
                continue;
            }

            try {
                $tx = $paypackService->getTransaction($ref);
                $status = strtolower($tx['status'] ?? 'pending');

                if ($status === 'successful' || $status === 'completed' || $status === 'success') {
                    $this->activateSubscription($sub, $tx);
                    $processedCount++;
                } elseif ($status === 'failed' || $status === 'cancelled') {
                    $sub->update(['status' => 'failed']);
                    $this->warn("Subscription {$sub->id} (Ref: {$ref}) marked as failed.");
                } elseif ($sub->created_at && $sub->created_at->diffInHours(now()) >= 24) {
                    // Mark as failed if pending for more than 24 hours
                    $sub->update(['status' => 'failed']);
                    $this->warn("Subscription {$sub->id} (Ref: {$ref}) timed out after 24 hours.");
                }
            } catch (\Throwable $e) {
                Log::error('Paypack sync error for subscription ID '.$sub->id, [
                    'ref' => $ref,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // 2. Fetch recent Paypack events to catch any standalone completed transactions
        try {
            $eventsResponse = $paypackService->getEvents(['limit' => 30]);
            $transactions = $eventsResponse['transactions'] ?? [];

            foreach ($transactions as $event) {
                $eventKind = $event['event_kind'] ?? $event['event-kind'] ?? '';
                $data = $event['data'] ?? $event;
                $ref = $data['ref'] ?? '';
                $status = strtolower($data['status'] ?? '');

                if ($ref && ($status === 'successful' || $status === 'completed' || $eventKind === 'transaction:processed')) {
                    $sub = Subscription::query()->where('provider_order_id', $ref)->first();
                    if ($sub && $sub->status === 'pending') {
                        $this->activateSubscription($sub, $data);
                        $processedCount++;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Paypack sync events error', ['error' => $e->getMessage()]);
        }

        $this->info("Paypack sync complete. Activated {$processedCount} subscription(s).");

        return Command::SUCCESS;
    }

    /**
     * Activate pending subscription and notify user.
     *
     * @param  array<string, mixed>  $txData
     */
    protected function activateSubscription(Subscription $sub, array $txData): void
    {
        $duration = $sub->duration_months ?? 1;

        // Cancel any existing active subscriptions for this user
        Subscription::query()
            ->where('user_id', $sub->user_id)
            ->where('id', '!=', $sub->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'ends_at' => now()]);

        $endsAt = now()->addMonths($duration);

        $sub->update([
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => $endsAt,
        ]);

        if ($sub->user && $sub->plan) {
            try {
                Mail::mailer('zoho_sales')->to($sub->user->email)->send(new SubscriptionConfirmedMail($sub->user, $sub, $sub->plan));
            } catch (\Throwable $e) {
                Log::error('Could not send subscription confirmation email during Paypack sync', [
                    'user_id' => $sub->user_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("Subscription {$sub->id} (User: {$sub->user_id}) successfully activated via Paypack sync.");
    }
}
