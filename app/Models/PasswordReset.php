<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|PasswordReset newModelQuery()
 * @method static Builder|PasswordReset newQuery()
 * @method static Builder|PasswordReset query()
 * @method static Builder|PasswordReset whereCreatedAt($value)
 * @method static Builder|PasswordReset whereEmail($value)
 * @method static Builder|PasswordReset whereId($value)
 * @method static Builder|PasswordReset whereToken($value)
 * @method static Builder|PasswordReset whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property-write mixed $raw
 */
class PasswordReset extends Model
{
    /**
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * @var string[]
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'email' => 'string',
        'token' => 'string',
        'created_at' => 'datetime',
    ];
}
