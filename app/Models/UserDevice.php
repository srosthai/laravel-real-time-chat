<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserDevice
 *
 * @property int $id
 * @property int $user_id
 * @property string $player_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|UserDevice newModelQuery()
 * @method static Builder|UserDevice newQuery()
 * @method static Builder|UserDevice query()
 * @method static Builder|UserDevice whereCreatedAt($value)
 * @method static Builder|UserDevice whereId($value)
 * @method static Builder|UserDevice wherePlayerId($value)
 * @method static Builder|UserDevice whereUpdatedAt($value)
 * @method static Builder|UserDevice whereUserId($value)
 * @mixin Eloquent
 */
class UserDevice extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_devices';

    /**
     * @var string[]
     */
    protected $fillable = [
        'player_id',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'player_id' => 'string',
    ];
}
