<?php

namespace App\Repositories;

use App\Events\UserEvent;
use App\Models\BlockedUser;
use App\Models\Role;
use App\Models\User;
use Arr;
use Auth;
use Exception;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class BlockUserRepository
 */
class BlockUserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'blocked_by',
        'blocked_to',
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return BlockedUser::class;
    }

    /**
     * @param  array  $input
     * @return bool|null
     *
     * @throws Exception
     */
    public function blockUnblockUser($input)
    {
        /** @var User $blockedTo */
        $blockedTo = User::findOrFail($input['blocked_to']);
        if ($blockedTo->hasRole(Role::ADMIN_ROLE_NAME) && $input['is_blocked']) {
            throw new UnprocessableEntityHttpException('You can not block admin user.');
        }

        /** @var BlockedUser $blockedUser */
        $blockedUser = BlockedUser::whereBlockedBy($input['blocked_by'])->whereBlockedTo($input['blocked_to'])->first();

        broadcast(new UserEvent([
            'blockedBy' => Auth::user(),
            'blockedTo' => $blockedTo,
            'isBlocked' => $input['is_blocked'],
            'type' => User::BLOCK_UNBLOCK_EVENT,
        ], $blockedTo->id))->toOthers();

        if ($input['is_blocked'] == false && ! empty($blockedUser)) {
            return $blockedUser->delete();
        }

        BlockedUser::create($input);

        return true;
    }

    /**
     * @return array
     */
    public function blockedUserIds()
    {
        $blockedUserIds = BlockedUser::toBase()->whereBlockedBy(Auth::id())
            ->orWhere('blocked_to', Auth::id())
            ->pluck('blocked_by', 'blocked_to')
            ->toArray();

        $blockedByMe = Arr::where($blockedUserIds, function ($value, $key) {
            return $value == getLoggedInUserId();
        });
        $blockedByMe = array_unique(array_keys($blockedByMe));

        return [
            array_unique(array_merge($blockedUserIds, array_keys($blockedUserIds))),
            $blockedByMe,
        ];
    }
}
