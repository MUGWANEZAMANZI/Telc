<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $plan_id
 * @property string $type
 * @property string $provider
 * @property string $provider_order_id
 * @property string $status
 * @property float|null $price_paid
 * @property int $duration_months
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read Plan|null $plan
 */
class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'type',
        'provider',
        'provider_order_id',
        'status',
        'price_paid',
        'duration_months',
        'starts_at',
        'ends_at',
    ];

    protected $attributes = [
        'type' => 'plan',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'price_paid' => 'decimal:2',
            'duration_months' => 'integer',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Plan, $this> */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && ($this->ends_at === null || $this->ends_at->isFuture());
    }
}
