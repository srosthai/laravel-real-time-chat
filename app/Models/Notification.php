<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Notification
 *
 * @property int $id
 * @property int $owner_id
 * @property string $owner_type
 * @property string $notification
 * @property int $to_id
 * @property int $is_read
 * @property string|null $read_at
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $receiver
 *
 * @method static Builder|Notification newModelQuery()
 * @method static Builder|Notification newQuery()
 * @method static Builder|Notification query()
 * @method static Builder|Notification whereCreatedAt($value)
 * @method static Builder|Notification whereDeletedAt($value)
 * @method static Builder|Notification whereId($value)
 * @method static Builder|Notification whereIsRead($value)
 * @method static Builder|Notification whereNotification($value)
 * @method static Builder|Notification whereOwnerId($value)
 * @method static Builder|Notification whereOwnerType($value)
 * @method static Builder|Notification whereReadAt($value)
 * @method static Builder|Notification whereToId($value)
 * @method static Builder|Notification whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property-read Group $group
 * @property-read User $sender
 * @property-read Notification $latestMsg
 * @property int $message_type
 * @property string|null $file_name
 *
 * @method static Builder|Notification whereFileName($value)
 * @method static Builder|Notification whereMessageType($value)
 */
class Notification extends Model
{
    /**
     * @var string
     */
    protected $table = 'notifications';

    /**
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'notification',
        'to_id',
        'is_read',
        'read_at',
        'message_type',
        'file_name',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'owner_id' => 'string',
        'owner_type' => 'string',
        'notification' => 'string',
        'to_id' => 'integer',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'message_type' => 'boolean',
        'file_name' => 'string',
    ];

    public static $rules = [
        'owner_id' => 'required|integer',
        'to_id' => 'required|integer',
        'notification' => 'required|string',
    ];

    /**
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'owner_id');
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return BelongsTo
     */
    public function latestMsg(): BelongsTo
    {
        return $this->belongsTo(self::class, 'latest_id');
    }
}
