<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\LastConversation
 *
 * @property int $id
 * @property string $group_id
 * @property int $conversation_id
 * @property int $user_id
 * @property array $group_details
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Conversation $conversation
 * @property-write mixed $raw
 *
 * @method static Builder|LastConversation newModelQuery()
 * @method static Builder|LastConversation newQuery()
 * @method static Builder|LastConversation query()
 * @method static Builder|LastConversation whereConversationId($value)
 * @method static Builder|LastConversation whereCreatedAt($value)
 * @method static Builder|LastConversation whereGroupDetails($value)
 * @method static Builder|LastConversation whereGroupId($value)
 * @method static Builder|LastConversation whereId($value)
 * @method static Builder|LastConversation whereUpdatedAt($value)
 * @method static Builder|LastConversation whereUserId($value)
 * @mixin Eloquent
 */
class LastConversation extends Model
{
    /**
     * @var string
     */
    protected $table = 'last_conversations';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'group_id', 'conversation_id', 'group_details'];

    /**
     * @var string[]
     */
    protected $casts = [
        'user_id' => 'integer',
        'group_id' => 'string',
        'conversation_id' => 'integer',
        'group_details' => 'array',
    ];

    /**
     * @return HasOne
     */
    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class, 'id', 'conversation_id');
    }
}
