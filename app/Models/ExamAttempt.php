<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function examTemplate(): BelongsTo
    {
        return $this->belongsTo(ExamTemplate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
