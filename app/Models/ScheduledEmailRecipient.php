<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduledEmailRecipient extends Model
{
    protected $fillable = [
        'scheduled_email_id',
        'user_id',
        'sent_at',
        'status',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<ScheduledEmail, $this> */
    public function scheduledEmail(): BelongsTo
    {
        return $this->belongsTo(ScheduledEmail::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
