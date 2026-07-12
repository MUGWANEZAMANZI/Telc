<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoiceTopic extends Model
{
    protected $fillable = [
        'title',
        'level',
        'exam_provider',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
