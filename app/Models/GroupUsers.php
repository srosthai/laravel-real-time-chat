<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupUsers
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property int $is_removed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 *
 * @method static Builder|GroupUsers newModelQuery()
 * @method static Builder|GroupUsers newQuery()
 * @method static Builder|GroupUsers query()
 * @method static Builder|GroupUsers whereCreatedAt($value)
 * @method static Builder|GroupUsers whereGroupId($value)
 * @method static Builder|GroupUsers whereId($value)
 * @method static Builder|GroupUsers whereIsRemoved($value)
 * @method static Builder|GroupUsers whereUpdatedAt($value)
 * @method static Builder|GroupUsers whereUserId($value)
 * @mixin \Eloquent
 *
 * @property int $role
 * @property int $added_by
 * @property int|null $removed_by
 * @property string|null $deleted_at
 * @property-write mixed $raw
 *
 * @method static Builder|GroupUsers whereAddedBy($value)
 * @method static Builder|GroupUsers whereDeletedAt($value)
 * @method static Builder|GroupUsers whereRemovedBy($value)
 * @method static Builder|GroupUsers whereRole($value)
 */
class GroupUsers extends Model
{
    /**
     * @var string
     */
    protected $table = 'group_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'user_id', 'is_removed',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'group_id' => 'integer',
        'user_id' => 'integer',
        'is_removed' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'group_id' => 'required|integer',
        'user_id' => 'required|integer',
        'is_removed' => 'nullable|integer',
    ];

    /**
     * @return HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
