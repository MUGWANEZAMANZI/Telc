<?php

namespace App\Livewire;

use App\Models\ScheduledEmail;
use App\Models\ScheduledEmailRecipient;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class AdminEmailScheduler extends Component
{
    public string $subject = '';

    public string $body = '';

    public string $recipientFilter = 'all_users';

    public ?string $specificRole = null;

    public ?string $scheduledDate = null;

    public ?string $scheduledTime = null;

    public ?int $editingId = null;

    public bool $showCompose = false;

    /** @var array<string, string> */
    protected array $rules = [
        'subject' => 'required|string|max:255',
        'body' => 'required|string',
        'recipientFilter' => 'required|in:all_users,subscribed,non_subscribed,specific_role',
        'specificRole' => 'nullable|string',
        'scheduledDate' => 'nullable|date|after_or_equal:today',
        'scheduledTime' => 'nullable|date_format:H:i',
    ];

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function toggleCompose(): void
    {
        $this->showCompose = ! $this->showCompose;
        if (! $this->showCompose) {
            $this->resetForm();
        }
    }

    public function resetForm(): void
    {
        $this->subject = '';
        $this->body = '';
        $this->recipientFilter = 'all_users';
        $this->specificRole = null;
        $this->scheduledDate = null;
        $this->scheduledTime = null;
        $this->editingId = null;
    }

    public function saveDraft(): void
    {
        $this->validate(['subject' => 'required|string|max:255', 'body' => 'required|string']);

        if ($this->editingId) {
            ScheduledEmail::query()->where('id', $this->editingId)->update([
                'subject' => $this->subject,
                'body' => $this->body,
                'recipient_filter' => $this->recipientFilter,
                'specific_role' => $this->specificRole,
            ]);
        } else {
            $email = ScheduledEmail::create([
                'subject' => $this->subject,
                'body' => $this->body,
                'recipient_filter' => $this->recipientFilter,
                'specific_role' => $this->specificRole,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);
            $this->editingId = $email->id;
        }

        session()->flash('success', 'Entwurf gespeichert.');
    }

    public function scheduleEmail(): void
    {
        $this->validate();

        $recipientQuery = $this->getRecipientQuery();
        $totalCount = $recipientQuery->count();

        if ($totalCount === 0) {
            session()->flash('error', 'Keine Empfänger für diesen Filter gefunden.');

            return;
        }

        $scheduledAt = null;
        if ($this->scheduledDate) {
            $time = $this->scheduledTime ?: '09:00';
            $scheduledAt = $this->scheduledDate.' '.$time;
        }

        if ($this->editingId) {
            $email = ScheduledEmail::query()->find($this->editingId);
            $email->update([
                'subject' => $this->subject,
                'body' => $this->body,
                'recipient_filter' => $this->recipientFilter,
                'specific_role' => $this->specificRole,
                'status' => $scheduledAt ? 'scheduled' : 'scheduled',
                'scheduled_at' => $scheduledAt ?? now(),
                'total_count' => $totalCount,
            ]);
        } else {
            $email = ScheduledEmail::create([
                'subject' => $this->subject,
                'body' => $this->body,
                'recipient_filter' => $this->recipientFilter,
                'specific_role' => $this->specificRole,
                'status' => 'scheduled',
                'scheduled_at' => $scheduledAt ?? now(),
                'total_count' => $totalCount,
                'created_by' => Auth::id(),
            ]);
        }

        $email->recipients()->delete();
        $recipientQuery->each(function ($user) use ($email) {
            ScheduledEmailRecipient::create([
                'scheduled_email_id' => $email->id,
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        });

        $this->resetForm();
        $this->showCompose = false;

        session()->flash('success', "E-Mail an {$totalCount} Empfänger geplant.");
    }

    public function sendNow(int $emailId): void
    {
        $email = ScheduledEmail::query()->find($emailId);
        if (! $email) {
            return;
        }

        $email->update([
            'status' => 'scheduled',
            'scheduled_at' => now(),
        ]);

        session()->flash('success', 'E-Mail wird jetzt gesendet.');
    }

    public function deleteEmail(int $emailId): void
    {
        ScheduledEmail::query()->where('id', $emailId)->delete();
        session()->flash('success', 'E-Mail gelöscht.');
    }

    public function editEmail(int $emailId): void
    {
        $email = ScheduledEmail::query()->find($emailId);
        if (! $email) {
            return;
        }

        $this->editingId = $email->id;
        $this->subject = $email->subject;
        $this->body = $email->body;
        $this->recipientFilter = $email->recipient_filter;
        $this->specificRole = $email->specific_role;
        $this->showCompose = true;
    }

    /** @return Builder<User> */
    protected function getRecipientQuery()
    {
        $query = User::query();

        return match ($this->recipientFilter) {
            'subscribed' => $query->whereHas('subscriptions', function ($q) {
                $q->where('status', 'active')->where(function ($q2) {
                    $q2->whereNull('ends_at')->orWhere('ends_at', '>', now());
                });
            }),
            'non_subscribed' => $query->whereDoesntHave('subscriptions', function ($q) {
                $q->where('status', 'active')->where(function ($q2) {
                    $q2->whereNull('ends_at')->orWhere('ends_at', '>', now());
                });
            }),
            'specific_role' => $query->where('role', $this->specificRole ?? 'student'),
            default => $query,
        };
    }

    public function render(): View
    {
        $emails = ScheduledEmail::query()
            ->with('creator')
            ->orderByDesc('created_at')
            ->paginate(10);

        $recipientCount = match ($this->recipientFilter) {
            'subscribed' => User::query()->whereHas('subscriptions', function ($q) {
                $q->where('status', 'active')->where(function ($q2) {
                    $q2->whereNull('ends_at')->orWhere('ends_at', '>', now());
                });
            })->count(),
            'non_subscribed' => User::query()->whereDoesntHave('subscriptions', function ($q) {
                $q->where('status', 'active')->where(function ($q2) {
                    $q2->whereNull('ends_at')->orWhere('ends_at', '>', now());
                });
            })->count(),
            'specific_role' => User::query()->where('role', $this->specificRole ?? 'student')->count(),
            default => User::query()->count(),
        };

        return view('livewire.admin-email-scheduler', [
            'emails' => $emails,
            'recipientCount' => $recipientCount,
        ]);
    }
}
