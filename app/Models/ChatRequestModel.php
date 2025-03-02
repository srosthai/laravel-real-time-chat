<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ChatRequestModel
 *
 * @property int $id
 * @property int|null $from_id
 * @property string|null $owner_id
 * @property string|null $owner_type
 * @property int $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|ChatRequestModel newModelQuery()
 * @method static Builder|ChatRequestModel newQuery()
 * @method static Builder|ChatRequestModel query()
 * @method static Builder|ChatRequestModel whereCreatedAt($value)
 * @method static Builder|ChatRequestModel whereFromId($value)
 * @method static Builder|ChatRequestModel whereId($value)
 * @method static Builder|ChatRequestModel whereOwnerId($value)
 * @method static Builder|ChatRequestModel whereOwnerType($value)
 * @method static Builder|ChatRequestModel whereStatus($value)
 * @method static Builder|ChatRequestModel whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property-read User|null $receiver
 */
class ChatRequestModel extends Model
{
    public const STATUS_ACCEPTED = 1;

    public const STATUS_PENDING = 0;

    public const STATUS_DECLINE = 2;

    /**
     * @var string
     */
    protected $table = 'chat_request';

    /**
     * @var string[]
     */
    protected $fillable = [
        'from_id',
        'owner_id',
        'owner_type',
        'status',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'from_id' => 'integer',
        'owner_id' => 'string',
        'owner_type' => 'string',
        'status' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
