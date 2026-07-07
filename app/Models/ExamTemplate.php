<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $level
 * @property string|null $source_pdf
 * @property array $theme
 * @property array $timings
 * @property array $sections
 * @property bool $is_published
 * @property string|null $exam_provider
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ExamAttempt> $attempts
 * @property-read \Illuminate\Database\Eloquent\Collection<int, ExamEnrollment> $enrollments
 */
#[Fillable(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_published', 'exam_provider', 'price'])]
class ExamTemplate extends Model
{
    protected function casts(): array
    {
        return [
            'theme' => 'array',
            'timings' => 'array',
            'sections' => 'array',
            'is_published' => 'boolean',
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
