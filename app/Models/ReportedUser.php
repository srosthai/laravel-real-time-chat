<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ReportedUser
 *
 * @property int $id
 * @property int $reported_by
 * @property int $reported_to
 * @property string $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $reportedBy
 * @property-read User $reportedTo
 *
 * @method static Builder|ReportedUser newModelQuery()
 * @method static Builder|ReportedUser newQuery()
 * @method static Builder|ReportedUser query()
 * @method static Builder|ReportedUser whereCreatedAt($value)
 * @method static Builder|ReportedUser whereId($value)
 * @method static Builder|ReportedUser whereNotes($value)
 * @method static Builder|ReportedUser whereReportedBy($value)
 * @method static Builder|ReportedUser whereReportedTo($value)
 * @method static Builder|ReportedUser whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ReportedUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'reported_users';

    /**
     * @var string[]
     */
    protected $fillable = ['reported_by', 'reported_to', 'notes'];

    /**
     * @var string[]
     */
    protected $casts = [
        'reported_by' => 'integer',
        'reported_to' => 'integer',
        'notes' => 'string',
    ];

    const IS_ACTIVE_FILTER_INACTIVE = 0;

    const IS_ACTIVE_FILTER_ACTIVE = 1;

    const IS_ACTIVE_FILTER_ARRAY = [
        self::IS_ACTIVE_FILTER_ACTIVE => 'Active',
        self::IS_ACTIVE_FILTER_INACTIVE => 'Inactive',
    ];

    /**
     * @return BelongsTo
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * @return BelongsTo
     */
    public function reportedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_to');
    }

    /**
     * @param  string  $value
     * @return string
     */
    public function getNotesAttribute($value): string
    {
        return nl2br(htmlspecialchars_decode($value));
    }
}
