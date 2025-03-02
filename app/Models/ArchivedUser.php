<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ArchivedUser
 *
 * @property int $id
 * @property string $owner_id
 * @property string $owner_type
 * @property int $archived_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $archivedBy
 *
 * @method static Builder|ArchivedUser newModelQuery()
 * @method static Builder|ArchivedUser newQuery()
 * @method static Builder|ArchivedUser query()
 * @method static Builder|ArchivedUser whereArchivedBy($value)
 * @method static Builder|ArchivedUser whereCreatedAt($value)
 * @method static Builder|ArchivedUser whereId($value)
 * @method static Builder|ArchivedUser whereOwnerId($value)
 * @method static Builder|ArchivedUser whereOwnerType($value)
 * @method static Builder|ArchivedUser whereUpdatedAt($value)
 * @mixin Eloquent
 */
class ArchivedUser extends Model
{
    /**
     * @var string
     */
    protected $table = 'archived_users';

    /**
     * @var string[]
     */
    protected $fillable = [
        'owner_id',
        'owner_type',
        'archived_by',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'owner_id' => 'string',
        'owner_type' => 'string',
        'archived_by' => 'integer',
    ];

    /**
     * @return BelongsTo
     */
    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
}
