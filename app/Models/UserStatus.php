<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder as BuilderAlias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserStatus
 *
 * @property int $id
 * @property int $user_id
 * @property string $emoji
 * @property string $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static BuilderAlias|UserStatus newModelQuery()
 * @method static BuilderAlias|UserStatus newQuery()
 * @method static BuilderAlias|UserStatus query()
 * @method static bool|null restore()
 * @method static BuilderAlias|UserStatus whereCreatedAt($value)
 * @method static BuilderAlias|UserStatus whereEmoji($value)
 * @method static BuilderAlias|UserStatus whereId($value)
 * @method static BuilderAlias|UserStatus whereStatus($value)
 * @method static BuilderAlias|UserStatus whereUpdatedAt($value)
 * @method static BuilderAlias|UserStatus whereUserId($value)
 * @mixin Eloquent
 *
 * @property string $emoji_short_name
 *
 * @method static BuilderAlias|UserStatus whereEmojiShortName($value)
 */
class UserStatus extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_status';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'emoji', 'status', 'emoji_short_name'];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'emoji' => 'string',
        'status' => 'string',
        'emoji_short_name' => 'string',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
