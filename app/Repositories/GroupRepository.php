<?php

namespace App\Repositories;

use App\Events\GroupEvent;
use App\Events\UserEvent;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\GroupMessageRecipient;
use App\Models\GroupUser;
use App\Models\LastConversation;
use App\Models\User;
use App\Traits\ImageTrait;
use Arr;
use Auth;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class GroupRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name', 'description', 'photo_url', 'group_type', 'privacy',
    ];

    /**
     * Return searchable fields.
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return Group::class;
    }

    /**
     * @param  array  $search
     * @param  int|null  $skip
     * @param  int|null  $limit
     * @param  array  $columns
     * @return Group[]|Collection
     */
    public function all($search = [], $skip = null, $limit = null, $columns = ['*'])
    {
        $query = Group::with('users')->whereHas('users', function (Builder $query) {
            $query->where('user_id', getLoggedInUserId());
        });

        return $query->orderBy('name')->get();
    }

    /**
     * @param  array  $input
     * @return Group
     */
    public function store($input)
    {
        try {
            if (! empty($input['photo'])) {
                $input['photo_url'] = ImageTrait::makeImage($input['photo'], Group::$PATH);
            }

            /** @var Group $group */
            $group = Group::create($input);

            $users = $input['users'];
            $users[] = getLoggedInUserId();
            $this->addMembersToGroup($group, $users, false);

            $userIds = $group->fresh()->users->pluck('id')->toArray();
            $broadcastData = $this->prepareDataForMemberAddedToGroup($group);
            broadcast(new UserEvent($broadcastData, $userIds))->toOthers();

            $msgInput = [
                'to_id' => $group->id,
                'message' => Auth::user()->name.' created group "'.$group->name.'"',
                'is_group' => true,
                'message_type' => Conversation::MESSAGE_TYPE_BADGES,
            ];
            $this->sendMessage($msgInput);

            return $group;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  array  $input
     * @param  int  $id
     * @return array
     */
    public function update($input, $id)
    {
        $conversation = null;
        /** @var Group $group */
        $group = Group::findOrFail($id);

        try {
            if (! empty($input['photo'])) {
                $input['photo_url'] = ImageTrait::makeImage($input['photo'], Group::$PATH);
            }

            unset($input['created_by']);
            $group->update($input);
            $changes = $group->getChanges();
            if (! empty($changes)) {
                $msgInput = [
                    'to_id' => $group->id,
                    'message' => 'Group details updated by '.Auth::user()->name,
                    'is_group' => true,
                    'message_type' => Conversation::MESSAGE_TYPE_BADGES,
                ];
                $conversation = $this->sendMessage($msgInput);
            }

            if (! empty($input['users'])) {
                $this->addMembersToGroup($group, $input['users']);
            }

            $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(['group' => $group]);
            broadcast(new GroupEvent($broadcastData))->toOthers();

            return [$group, $conversation];
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  Group  $group
     * @param  array  $users
     * @param  bool  $fireEvent
     * @return array|void
     *
     * @throws Exception
     */
    public function addMembersToGroup($group, $users, $fireEvent = true)
    {
        $groupUsers = $group->users->pluck('id')->toArray();
        $newAddedUsers = [];
        $newUserNames = '';
        $users = array_unique($users);

        $userRecords = User::whereIn('id', $users)->get()->keyBy('id');
        GroupUser::withTrashed()->whereIn('user_id', $users)->where('group_id', $group->id)->forceDelete();
        LastConversation::whereIn('user_id', $users)->where('group_id', $group->id)->delete();

        foreach ($users as $userId) {
            if (in_array($userId, $groupUsers)) { // if already in group
                continue;
            }

            if (! isset($userRecords[$userId])) {
                continue;
            }

            /** @var User $user */
            $user = $userRecords[$userId];
            $newAddedUsers[] = $user->toArray();
            $newUserNames .= $user->name.', ';

            GroupUser::create([
                'user_id' => $user->id,
                'group_id' => $group->id,
                'added_by' => getLoggedInUserId(),
                'role' => $group->created_by == $user->id ? GroupUser::ROLE_ADMIN : GroupUser::ROLE_MEMBER,
            ]);

            if ($fireEvent) {
                $broadCastData = $this->prepareDataForMemberAddedToGroup($group);
                broadcast(new UserEvent($broadCastData, $user->id))->toOthers();
            }
        }

        if (! $fireEvent) {
            return;
        }

        $newUserNames = substr($newUserNames, 0, strlen($newUserNames) - 2);
        $msgInput = [
            'to_id' => $group->id,
            'message' => Auth::user()->name." added : $newUserNames",
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
            'add_members' => true,
        ];
        $conversation = $this->sendMessage($msgInput);

        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            ['group' => $group, 'users' => $newAddedUsers], Group::GROUP_NEW_MEMBERS_ADDED
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        return [$newAddedUsers, $conversation];
    }

    /**
     * @param  Group  $group
     * @return mixed
     */
    public function prepareDataForMemberAddedToGroup($group)
    {
        $groupArr = $group->toArray();
        $groupArr['group_created_by'] = $group->group_created_by;
        $groupArr['type'] = User::ADDED_TO_GROUP;
        unset($groupArr['users']);
        unset($groupArr['created_by_user']);
        unset($groupArr['users_with_trashed']);

        return $groupArr;
    }

    /**
     * @param  Group  $group
     * @param  User  $user
     * @return Conversation
     *
     * @throws Exception
     */
    public function removeMemberFromGroup($group, $user)
    {
        /** @var User $authUser */
        $authUser = Auth::user();
        $groupUser = GroupUser::whereGroupId($group->id)->whereUserId($user->id);

        if (getLoggedInUserId() != $group->created_by && $user->id == $group->created_by) {
            throw new UnprocessableEntityHttpException('You can not remove group owner.');
        }

        $message = $authUser->name." removed $user->name.";

        $msgInput = [
            'to_id' => $group->id,
            'message' => $message,
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
        ];
        $conversation = $this->sendMessage($msgInput);

        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            ['group' => $group, 'user_id' => $user->id], Group::GROUP_MEMBER_REMOVED
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        $groupUser->update(['removed_by' => getLoggedInUserId(), 'deleted_at' => Carbon::now()]);

        // Store last group details info when user leave the group
        $groupDetails = $group->toArray();
        $groupDetails['removed_from_group'] = true;
        $groupDetails['users'] = $group->fresh()->users->toArray();
        LastConversation::create([
            'conversation_id' => $conversation->id, 'group_id' => $group->id, 'user_id' => $user->id,
            'group_details' => $groupDetails,
        ]);

        return $conversation;
    }

    /**
     * @param  array  $groupUsers
     * @param  int  $messageId
     * @param  int  $groupId
     * @return bool
     */
    public function addRecordsToGroupMessageRecipients($groupUsers, $messageId, $groupId)
    {
        $users = Arr::except($groupUsers, getLoggedInUserId());

        $inputs = [];
        foreach ($users as $userId) {
            $inputs[] = [
                'user_id' => $userId,
                'conversation_id' => $messageId,
                'group_id' => $groupId,
            ];
        }

        GroupMessageRecipient::insert($inputs);

        return true;
    }

    /**
     * @param  Group  $group
     * @param  int  $userId
     * @return Conversation
     *
     * @throws Exception
     */
    public function leaveGroup($group, $userId)
    {
        $msgInput = [
            'to_id' => $group->id,
            'message' => Auth::user()->name.' left the group',
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
        ];

        $conversation = $this->sendMessage($msgInput);
        GroupUser::whereGroupId($group->id)->whereUserId($userId)->delete();

        // Store last group details info when user leave the group
        $group->append('group_created_by');
        $groupDetails = $group->toArray();
        $groupDetails['removed_from_group'] = true;
        $groupDetails['users'] = $group->fresh()->users->toArray();
        LastConversation::create([
            'conversation_id' => $conversation->id, 'group_id' => $group->id, 'user_id' => $userId,
            'group_details' => $groupDetails,
        ]);

        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            ['group' => $group, 'user_id' => $userId], Group::GROUP_MEMBER_REMOVED
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        return $conversation;
    }

    /**
     * @param  Group  $group
     * @param  int  $userId
     * @return bool
     *
     * @throws Exception
     */
    public function removeGroup($group, $userId)
    {
        if ($group->created_by != $userId) {
            GroupUser::whereGroupId($group->id)->whereUserId($userId)->forceDelete();

            return true;
        }

        $msgInput = [
            'to_id' => $group->id,
            'message' => Auth::user()->name.' deleted this group',
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
        ];
        $conversation = $this->sendMessage($msgInput);

        // broadcast event for all group members
        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            ['group' => $group], Group::GROUP_DELETED_BY_OWNER
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        $userIds = $group->users->pluck('id', 'id')->except($group->created_by)->toArray();
        // All members of group should leaved the group
        GroupUser::whereGroupId($group->id)->whereIn('user_id', $userIds)->delete();
        // Group deleted for owner of group
        GroupUser::whereGroupId($group->id)->where('user_id', $group->created_by)->forceDelete();

        // Store last group details info when user leave the group
        $group->append('');
        $groupDetails = $group->toArray();
        $groupDetails['group_created_by'] = $group->group_created_by;
        $groupDetails['removed_from_group'] = true;
        $groupDetails['group_deleted_by_owner'] = true;
        $groupDetails['users'] = $group->fresh()->users->toArray();
        foreach ($group->users as $user) {
            LastConversation::create([
                'conversation_id' => $conversation->id, 'group_id' => $group->id, 'user_id' => $user->id,
                'group_details' => $groupDetails,
            ]);
        }

        return true;
    }

    /**
     * @param  Group  $group
     * @param  User  $member
     * @return Conversation
     */
    public function makeMemberToGroupAdmin($group, $member)
    {
        $memberIds = $group->users->pluck('id', 'id')->except($group->created_by)->toArray();

        $this->assignRole($group->id, $member->id, GroupUser::ROLE_ADMIN);

        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            [
                'group' => $group, 'user_id' => $member->id, 'is_admin' => true, 'userIds' => $memberIds,
            ], Group::GROUP_MEMBER_ROLE_UPDATED
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        $msgInput = [
            'to_id' => $group->id,
            'message' => Auth::user()->name.' assigned admin role to '.$member->name,
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
        ];

        return $this->sendMessage($msgInput);
    }

    /**
     * @param  Group  $group
     * @param  User  $member
     * @return Conversation
     */
    public function dismissAsAdmin($group, $member)
    {
        if ($group->created_by == $member->id) {
            throw new UnprocessableEntityHttpException('You can not change group owner role.');
        }

        $memberIds = $group->users->pluck('id', 'id')->except($group->created_by)->toArray();
        $this->assignRole($group->id, $member->id, GroupUser::ROLE_MEMBER);

        $broadcastData = $this->prepareDataBroadcastWhenGroupUpdated(
            [
                'group' => $group, 'user_id' => $member->id, 'is_admin' => false, 'userIds' => $memberIds,
            ], Group::GROUP_MEMBER_ROLE_UPDATED
        );
        broadcast(new GroupEvent($broadcastData))->toOthers();

        $msgInput = [
            'to_id' => $group->id,
            'message' => Auth::user()->name." dismissed $member->name from admin",
            'is_group' => true,
            'message_type' => Conversation::MESSAGE_TYPE_BADGES,
        ];

        return $this->sendMessage($msgInput);
    }

    /**
     * @param  array  $input
     * @return Conversation
     */
    public function sendMessage($input)
    {
        /** @var ChatRepository $chatRepo */
        $chatRepo = app(ChatRepository::class);
        $conversation = $chatRepo->sendGroupMessage($input);

        return $conversation;
    }

    /**
     * @param  int  $groupId
     * @param  int  $memberId
     * @param  int  $role
     * @return bool
     */
    public function assignRole($groupId, $memberId, $role)
    {
        GroupUser::whereGroupId($groupId)->whereUserId($memberId)->update(['role' => $role]);

        return true;
    }

    /**
     * @param  string  $groupId
     * @return bool
     */
    public function isAuthUserGroupAdmin($groupId)
    {
        /** @var GroupUser $groupUser */
        $groupUser = GroupUser::whereGroupId($groupId)->whereUserId(getLoggedInUserId())->first();

        return ($groupUser->role === GroupUser::ROLE_ADMIN) ? true : false;
    }

    /**
     * @param  array  $data
     * @param  int  $type
     * @return mixed
     */
    public function prepareDataBroadcastWhenGroupUpdated($data, $type = Group::GROUP_DETAILS_UPDATED)
    {
        $result['group'] = $data['group']->toArray();
        unset($result['group']['users_with_trashed']);
        $result['type'] = $type;

        if (isset($data['user_id'])) {
            $result['user_id'] = $data['user_id'];
        }

        if (isset($data['is_admin'])) {
            $result['is_admin'] = $data['is_admin'];
        }

        if (isset($data['userIds'])) {
            $result['userIds'] = $data['userIds'];
        }

        $users = [];
        if (isset($data['users'])) {
            $users = $this->prepareUsersData($data['users']);
        }

        $result['group']['users'] = $users;

        return $result;
    }

    public function getGroupMembersIds($group)
    {
        return $group->users->pluck('id')->toArray();
    }

    /**
     * @param  array  $users
     * @return array
     */
    public function prepareUsersData($users)
    {
        $result = [];
        foreach ($users as $user) {
            $data['id'] = $user['id'];
            $data['name'] = $user['name'];
            $data['email'] = $user['email'];
            $data['photo_url'] = $user['photo_url'];

            $result[] = $data;
        }

        return $result;
    }
}
