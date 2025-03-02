<?php

namespace App\Models;

use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\GroupUser
 *
 * @property int $id
 * @property int $group_id
 * @property int $user_id
 * @property int $role
 * @property int $added_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-write mixed $raw
 * @property-read User $user
 *
 * @method static Builder|GroupUser newModelQuery()
 * @method static Builder|GroupUser newQuery()
 * @method static Builder|GroupUser query()
 * @method static Builder|GroupUser whereAddedBy($value)
 * @method static Builder|GroupUser whereCreatedAt($value)
 * @method static Builder|GroupUser whereGroupId($value)
 * @method static Builder|GroupUser whereId($value)
 * @method static Builder|GroupUser whereRole($value)
 * @method static Builder|GroupUser whereUpdatedAt($value)
 * @method static Builder|GroupUser whereUserId($value)
 * @mixin Eloquent
 *
 * @property int|null $removed_by
 * @property Carbon|null $deleted_at
 *
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|GroupUser onlyTrashed()
 * @method static bool|null restore()
 * @method static Builder|GroupUser whereDeletedAt($value)
 * @method static Builder|GroupUser whereRemovedBy($value)
 * @method static \Illuminate\Database\Query\Builder|GroupUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|GroupUser withoutTrashed()
 */
class GroupUser extends Model
{
    use SoftDeletes;

    /**
     * @var string
     */
    protected $table = 'group_users';

    const ROLE_MEMBER = 1;

    const ROLE_ADMIN = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group_id', 'user_id', 'added_by', 'role', 'removed_by', 'deleted_at',
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
        'added_by' => 'integer',
        'removed_by' => 'integer',
        'role' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
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
