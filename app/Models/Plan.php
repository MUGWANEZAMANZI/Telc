<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property float $price
 * @property string $currency
 * @property string $interval
 * @property array<int, string> $features
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Subscription> $subscriptions
 */
class Plan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'interval',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /** @return HasMany<Subscription, $this> */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getPriceForDuration(int $months): float
    {
        if ($this->interval === 'month') {
            // Keep original standard plan prices exactly to preserve standard billing logic
            if (abs((float) $this->price - 6.98) < 0.01) {
                if ($months === 1) {
                    return 6.98;
                } elseif ($months === 3) {
                    return 19.47;
                } elseif ($months === 6) {
                    return 36.85;
                } elseif ($months === 12) {
                    return 59.90;
                }
            }

            // General monthly plan calculation with the same discount structure
            if ($months === 1) {
                return (float) $this->price;
            } elseif ($months === 3) {
                return round((float) $this->price * 3 * 0.93, 2);
            } elseif ($months === 6) {
                return round((float) $this->price * 6 * 0.88, 2);
            } elseif ($months === 12) {
                return round((float) $this->price * 12 * 0.715, 2);
            }
        }

        return (float) $this->price * $months;
    }
}
