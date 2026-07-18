<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class ScheduledEmail extends Model
{
    protected $fillable = [
        'subject',
        'body',
        'recipient_filter',
        'specific_role',
        'status',
        'scheduled_at',
        'sent_count',
        'total_count',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'sent_count' => 'integer',
            'total_count' => 'integer',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** @return HasMany<ScheduledEmailRecipient, $this> */
    public function recipients(): HasMany
    {
        return $this->hasMany(ScheduledEmailRecipient::class);
    }

    public function isScheduled(): bool
    {
        if ($this->status !== 'scheduled') {
            return false;
        }

        $scheduledAt = $this->attributes['scheduled_at'] ?? null;

        return $scheduledAt !== null && Carbon::parse($scheduledAt)->isPast();
    }
}
