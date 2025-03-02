<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class GroupMessageRecipients
 *
 * @property int $id
 * @property int $user_id
 * @property int $conversation_id
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-write mixed $raw
 *
 * @method static Builder|GroupMessageRecipient newModelQuery()
 * @method static Builder|GroupMessageRecipient newQuery()
 * @method static Builder|GroupMessageRecipient query()
 * @method static Builder|GroupMessageRecipient whereConversationId($value)
 * @method static Builder|GroupMessageRecipient whereCreatedAt($value)
 * @method static Builder|GroupMessageRecipient whereId($value)
 * @method static Builder|GroupMessageRecipient whereReadAt($value)
 * @method static Builder|GroupMessageRecipient whereUpdatedAt($value)
 * @method static Builder|GroupMessageRecipient whereUserId($value)
 * @mixin \Eloquent
 *
 * @property string $group_id
 * @property-read \App\Models\Conversation $conversation
 *
 * @method static Builder|GroupMessageRecipient whereGroupId($value)
 */
class GroupMessageRecipient extends Model
{
    /**
     * @var string
     */
    protected $table = 'group_message_recipients';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id', 'conversation_id', 'group_id', 'read_at',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'string',
        'conversation_id' => 'integer',
        'read_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
}
