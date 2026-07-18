<?php

namespace App\Jobs;

use App\Mail\ScheduledEmailMail;
use App\Models\ScheduledEmail;
use App\Models\ScheduledEmailRecipient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendScheduledBatchEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /** @param array<int, int> $recipientIds */
    public function __construct(
        public int $scheduledEmailId,
        public array $recipientIds,
    ) {
        $this->onQueue('emails');
    }

    public function handle(): void
    {
        $scheduledEmail = ScheduledEmail::query()->find($this->scheduledEmailId);
        if (! $scheduledEmail || $scheduledEmail->status !== 'sending') {
            return;
        }

        foreach ($this->recipientIds as $recipientId) {
            $recipient = ScheduledEmailRecipient::query()->find((int) $recipientId);
            if (! $recipient instanceof ScheduledEmailRecipient || $recipient->status !== 'pending') {
                continue;
            }

            $user = User::query()->find($recipient->user_id);
            if (! $user instanceof User) {
                $recipient->update(['status' => 'failed', 'error_message' => 'User not found']);

                continue;
            }

            try {
                Mail::mailer('zoho_info')
                    ->to($user->email)
                    ->send(new ScheduledEmailMail($scheduledEmail, $user));

                $recipient->update(['status' => 'sent', 'sent_at' => now()]);
                $scheduledEmail->increment('sent_count');
            } catch (\Throwable $e) {
                Log::error('Failed to send scheduled email', [
                    'scheduled_email_id' => $this->scheduledEmailId,
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
                $recipient->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            }
        }

        $remainingPending = $scheduledEmail->recipients()->where('status', 'pending')->count();
        if ($remainingPending === 0) {
            $scheduledEmail->update(['status' => 'sent']);
        }
    }
}
