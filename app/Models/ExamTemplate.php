<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $level
 * @property string|null $source_pdf
 * @property array<string, mixed> $theme
 * @property array<string, mixed> $timings
 * @property array<int, array<string, mixed>> $sections
 * @property bool $is_published
 * @property bool $is_free
 * @property string|null $exam_provider
 * @property float|null $price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ExamAttempt> $attempts
 * @property-read Collection<int, ExamEnrollment> $enrollments
 * @property string|null $price_label
 * @property bool $is_partially_free
 * @property bool $is_actually_free
 */
#[Fillable(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_published', 'exam_provider', 'price', 'is_free'])]
class ExamTemplate extends Model
{
    protected function casts(): array
    {
        return [
            'theme' => 'array',
            'timings' => 'array',
            'sections' => 'array',
            'is_published' => 'boolean',
            'is_free' => 'boolean',
        ];
    }

    /** @return HasMany<ExamAttempt, $this> */
    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    /** @return HasMany<ExamEnrollment, $this> */
    public function enrollments(): HasMany
    {
        return $this->hasMany(ExamEnrollment::class);
    }
}
