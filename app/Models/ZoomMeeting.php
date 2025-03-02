<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * Class ZoomMeeting
 *
 * @property int $id
 * @property string $topic
 * @property string $start_time
 * @property string $duration
 * @property int $host_video
 * @property int $participant_video
 * @property string $agenda
 * @property int $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|User[] $members
 * @property-read int|null $members_count
 *
 * @method static Builder|ZoomMeeting newModelQuery()
 * @method static Builder|ZoomMeeting newQuery()
 * @method static Builder|ZoomMeeting query()
 * @method static Builder|ZoomMeeting whereAgenda($value)
 * @method static Builder|ZoomMeeting whereCreatedAt($value)
 * @method static Builder|ZoomMeeting whereCreatedBy($value)
 * @method static Builder|ZoomMeeting whereDuration($value)
 * @method static Builder|ZoomMeeting whereHostVideo($value)
 * @method static Builder|ZoomMeeting whereId($value)
 * @method static Builder|ZoomMeeting whereParticipantVideo($value)
 * @method static Builder|ZoomMeeting whereStartTime($value)
 * @method static Builder|ZoomMeeting whereTopic($value)
 * @method static Builder|ZoomMeeting whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ZoomMeeting extends Model
{
    /**
     * @var string
     */
    protected $table = 'zoom_meetings';

    const STATUS_AWAITED = 1;

    const STATUS_FINISHED = 2;

    const status = [
        self::STATUS_AWAITED => 'Awaited',
        self::STATUS_FINISHED => 'Finished',
    ];

    protected $appends = ['status_text'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'topic',
        'start_time',
        'duration',
        'host_video',
        'participant_video',
        'agenda',
        'created_by',
        'status',
        'meta',
        'meeting_id',
        'time_zone',
        'password',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'topic' => 'string',
        'start_time' => 'datetime',
        'duration' => 'integer',
        'host_video' => 'boolean',
        'participant_video' => 'boolean',
        'agenda'            => 'string',
        'created_by'        => 'integer',
        'status'            => 'integer',
        'meta'              => 'array',
        'meeting_id'        => 'string',
        'time_zone'         => 'string',
        'password'          => 'string',
    ];

    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'zoom_meeting_candidates', 'meeting_id', 'user_id');
    }

    public static $rules = [
        'topic' => 'required',
        'start_time' => 'required',
        'duration' => 'required',
        'host_video' => 'required',
        'participant_video' => 'required',
        'members' => 'required|array',
        'agenda' => 'required',
    ];

    /**
     * @return string
     */
    public function getStatusTextAttribute(): string
    {
        return self::status[$this->status];
    }
}
