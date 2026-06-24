<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'exam_template_id', 'status', 'payment_status', 'enrolled_at'])]
class ExamEnrollment extends Model
{
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
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
