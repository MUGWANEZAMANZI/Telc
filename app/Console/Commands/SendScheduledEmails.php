<?php

namespace App\Console\Commands;

use App\Jobs\SendScheduledBatchEmail;
use App\Models\ScheduledEmail;
use Illuminate\Console\Command;

class SendScheduledEmails extends Command
{
    protected $signature = 'scheduled-emails:send';

    protected $description = 'Process and send scheduled emails in batches of 90';

    public function handle(): int
    {
        $emails = ScheduledEmail::query()
            ->where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($emails as $email) {
            $pendingRecipients = $email->recipients()
                ->where('status', 'pending')
                ->limit(90)
                ->pluck('user_id', 'id');

            if ($pendingRecipients->isEmpty()) {
                if ($email->recipients()->where('status', 'pending')->count() === 0) {
                    $email->update(['status' => 'sent']);
                }

                continue;
            }

            $email->update(['status' => 'sending']);

            SendScheduledBatchEmail::dispatch($email->id, $pendingRecipients->keys()->toArray());
        }

        return Command::SUCCESS;
    }
}
