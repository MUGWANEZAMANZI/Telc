<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function attempts(): HasMany
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(ExamEnrollment::class);
    }
}
