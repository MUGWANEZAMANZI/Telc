<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'exam_template_id', 'status', 'payment_status', 'active_answers', 'remaining_seconds', 'active_section', 'enrolled_at', 'transaction_id', 'receipt_path'])]
class ExamEnrollment extends Model
{
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'active_answers' => 'array',
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
