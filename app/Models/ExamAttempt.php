<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $exam_template_id
 * @property int|null $user_id
 * @property array<string, mixed> $answers
 * @property array{earned: float, possible: float, percentage: float}|null $result
 * @property Carbon|null $started_at
 * @property Carbon|null $submitted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ExamTemplate $examTemplate
 * @property-read User|null $user
 */
#[Fillable(['exam_template_id', 'user_id', 'answers', 'result', 'started_at', 'submitted_at'])]
class ExamAttempt extends Model
{
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'result' => 'array',
            'started_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<ExamTemplate, $this> */
    public function examTemplate(): BelongsTo
    {
        return $this->belongsTo(ExamTemplate::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
