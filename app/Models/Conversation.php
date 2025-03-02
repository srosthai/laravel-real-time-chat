<?php

namespace App\Models;

use App\Traits\ImageTrait;
use DB;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Conversation
 *
 * @property int $id
 * @property int|null $from_id
 * @property int|null $to_id
 * @property string $message
 * @property int $status 0 for unread,1 for seen
 * @property int $message_type 0- text message, 1- image, 2- pdf, 3- doc, 4- voice
 * @property string|null $file_name
 * @property string $created_at
 * @property Carbon|null $updated_at
 * @property-read string $photo_url
 * @property-read User|null $receiver
 * @property-read User|null $sender
 *
 * @method static Builder|Conversation newModelQuery()
 * @method static Builder|Conversation newQuery()
 * @method static Builder|Conversation query()
 * @method static Builder|Conversation whereCreatedAt($value)
 * @method static Builder|Conversation whereFileName($value)
 * @method static Builder|Conversation whereFromId($value)
 * @method static Builder|Conversation whereId($value)
 * @method static Builder|Conversation whereMessage($value)
 * @method static Builder|Conversation whereMessageType($value)
 * @method static Builder|Conversation whereStatus($value)
 * @method static Builder|Conversation whereToId($value)
 * @method static Builder|Conversation whereUpdatedAt($value)
 * @mixin Model
 *
 * @property Carbon|null $deleted_at
 * @property-read User $user
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|Conversation onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|Conversation whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Conversation withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Conversation withoutTrashed()
 * @method static Builder|Conversation whereIsGroup($value)
 *
 * @property-read Group $group
 * @property-read string $time_from_now_in_min
 * @property string $to_type 1 => Message, 2 => Group Message
 * @property-read mixed $is_group
 * @property-write mixed $raw
 *
 * @method static Builder|Conversation whereToType($value)
 * @method static Builder|Conversation message()
 *
 * @property int|null $reply_to
 * @property-read Collection|GroupMessageRecipient[] $readByAll
 * @property-read Collection|GroupMessageRecipient[] $readBy
 * @property-read Collection|MessageAction $messageAction
 * @property-read int|null $read_by_all_count
 *
 * @method static Builder|Conversation whereReplyTo($value)
 *
 * @property-read Conversation|null $replyMessage
 */
class Conversation extends Model
{
    use ImageTrait;

    /**
     * @var string
     */
    protected $table = 'conversations';

    protected $fillable = [
        'from_id',
        'to_id',
        'message',
        'message_type',
        'status',
        'file_name',
        'to_type',
        'reply_to',
        'url_details',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'from_id' => 'integer',
        'reply_to' => 'integer',
        'to_id' => 'string',
        'message' => 'string',
        'message_type' => 'integer',
        'file_name' => 'string',
        'to_type' => 'string',
        'url_details' => 'array',
        'status' => 'integer',
    ];

    /**
     * @var string[]
     */
    public static $rules = [
        'to_id' => 'required|string',
        'message' => 'required|string',
    ];

    // time from now in minuts
    protected $appends = ['time_from_now_in_min', 'is_group'];

    const LIMIT = 5000;

    const PATH = 'conversation';

    const MEDIA_IMAGE = 1;

    const MEDIA_PDF = 2;

    const MEDIA_DOC = 3;

    const MEDIA_VOICE = 4;

    const MEDIA_VIDEO = 5;

    const YOUTUBE_URL = 6;

    const MEDIA_TXT = 7;

    const MEDIA_XLS = 8;

    const MEDIA_ZIP = 10;

    const MEDIA_RAR = 11;

    const MESSAGE_TYPE_BADGES = 9;

    const MEDIA_MESSAGE_TYPES = [
        self::MEDIA_IMAGE, self::MEDIA_PDF, self::MEDIA_DOC, self::MEDIA_VOICE, self::MEDIA_VIDEO, self::YOUTUBE_URL, self::MEDIA_TXT, self::MEDIA_XLS, self::MEDIA_ZIP, self::MEDIA_RAR,
    ];

    public function getMessageAttribute($value)
    {
        if (! empty($this->file_name)) {
            return $this->imageUrl(self::PATH.DIRECTORY_SEPARATOR.$value);
        }

        return $value;
    }

    protected $with = ['replyMessage'];

    /**
     * @param $value
     * @return string
     */
    public function getPhotoUrlAttribute($value): string
    {
        if (! empty($value)) {
            return $this->imageUrl(User::$PATH.DIRECTORY_SEPARATOR.$value);
        }

        return asset('assets/images/avatar.png');
    }

    /**
     * @return int
     */
    public function getIsGroupAttribute(): int
    {
        if ($this->to_type == Group::class) {
            return 1;
        }

        return 0;
    }

    /**
     * @return string
     */
    public function getTimeFromNowInMinAttribute(): string
    {
        return Carbon::now()->diffInMinutes($this->created_at);
    }

    /**
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * @return BelongsTo
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_id');
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeMessage(Builder $query): Builder
    {
        return $query->where('to_type', self::class);
    }

    /**
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeGroup(Builder $query): Builder
    {
        return $query->where('to_type', Group::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'to_id');
    }

    /**
     * @return HasMany
     */
    public function readByAll(): HasMany
    {
        return $this->hasMany(GroupMessageRecipient::class, 'conversation_id')->whereNull('read_at');
    }

    /**
     * @return HasMany
     */
    public function readBy(): HasMany
    {
        return $this->hasMany(GroupMessageRecipient::class, 'conversation_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function replyMessage(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reply_to');
    }

    /**
     * @return BelongsTo
     */
    public function archiveConversation(): BelongsTo
    {
        return $this->belongsTo(ArchivedUser::class, 'to_id', 'owner_id')->where('archived_by', '=',
            getLoggedInUserId());
    }

    /**
     * @return BelongsTo
     */
    public function archiveUsers(): BelongsTo
    {
        return $this->belongsTo(ArchivedUser::class, DB::raw('user_id'), 'owner_id')->where('archived_by', '=',
            getLoggedInUserId());
    }

    /**
     * @return HasOne
     */
    public function messageAction(): HasOne
    {
        return $this->hasOne(MessageAction::class, 'conversation_id')->where('deleted_by', getLoggedInUserId());
    }
}
