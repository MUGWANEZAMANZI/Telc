<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $organization
 * @property string $email
 * @property string|null $message
 * @property string $password
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ContactRequest extends Model
{
    protected $fillable = [
        'name',
        'organization',
        'email',
        'message',
        'password',
        'status',
    ];
}
