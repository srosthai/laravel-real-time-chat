<?php

namespace App\Models;

use Eloquent;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * App\Models\MessageAction
 *
 * @property int $id
 * @property int $conversation_id
 * @property int $deleted_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static Builder|MessageAction newModelQuery()
 * @method static Builder|MessageAction newQuery()
 * @method static Builder|MessageAction query()
 * @method static Builder|MessageAction whereConversationId($value)
 * @method static Builder|MessageAction whereCreatedAt($value)
 * @method static Builder|MessageAction whereDeletedBy($value)
 * @method static Builder|MessageAction whereId($value)
 * @method static Builder|MessageAction whereUpdatedAt($value)
 * @mixin Eloquent
 *
 * @property int $is_hard_delete
 *
 * @method static Builder|MessageAction whereIsHardDelete($value)
 *
 * @property-write mixed $raw
 */
class MessageAction extends Model
{
    /**
     * @var string
     */
    protected $table = 'message_action';

    /**
     * @var string[]
     */
    protected $fillable = [
        'conversation_id',
        'deleted_by',
        'is_hard_delete',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'conversation_id' => 'integer',
        'deleted_by' => 'integer',
        'is_hard_delete' => 'boolean',
    ];

    /**
     * @var string[]
     */
    public static $rules = [
        'conversation_id' => 'required|integer',
        'deleted_by' => 'required|integer',
    ];
}
