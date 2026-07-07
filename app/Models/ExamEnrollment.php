<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $exam_template_id
 * @property string $status
 * @property string $payment_status
 * @property array<string, mixed>|null $active_answers
 * @property int|null $remaining_seconds
 * @property int $active_section
 * @property Carbon|null $enrolled_at
 * @property string|null $transaction_id
 * @property string|null $receipt_path
 * @property string|null $paypal_order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read ExamTemplate $examTemplate
 * @property-read User $user
 */
#[Fillable(['user_id', 'exam_template_id', 'status', 'payment_status', 'active_answers', 'remaining_seconds', 'active_section', 'enrolled_at', 'transaction_id', 'receipt_path', 'paypal_order_id'])]
class ExamEnrollment extends Model
{
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'active_answers' => 'array',
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
