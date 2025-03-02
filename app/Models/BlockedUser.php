<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\BlockedUser
 *
 * @property int $id
 * @property int $blocked_by
 * @property int $blocked_to
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|BlockedUser newModelQuery()
 * @method static Builder|BlockedUser newQuery()
 * @method static Builder|BlockedUser query()
 * @method static Builder|BlockedUser whereBlockedBy($value)
 * @method static Builder|BlockedUser whereBlockedTo($value)
 * @method static Builder|BlockedUser whereCreatedAt($value)
 * @method static Builder|BlockedUser whereId($value)
 * @method static Builder|BlockedUser whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property-write mixed $raw
 */
class BlockedUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'blocked_users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'blocked_by',
        'blocked_to',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'blocked_by' => 'integer',
        'blocked_to' => 'integer',
    ];
}
