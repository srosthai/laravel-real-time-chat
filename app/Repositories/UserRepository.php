<?php

namespace App\Repositories;

use App;
use App\Events\UpdatesEvent;
use App\Exceptions\ApiOperationFailedException;
use App\Models\ArchivedUser;
use App\Models\BlockedUser;
use App\Models\ChatRequestModel;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\GroupMessageRecipient;
use App\Models\GroupUser;
use App\Models\LastConversation;
use App\Models\MessageAction;
use App\Models\Notification;
use App\Models\ReportedUser;
use App\Models\Role;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\UserStatus;
use App\Traits\ImageTrait;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name', 'email', 'phone', 'last_seen', 'about', 'photo_url', 'is_online',
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
        return User::class;
    }

    /**
     * @param  array  $input
     * @return User
     */
    public function store($input)
    {
        /** @var AccountRepository $accountRepo */
        $accountRepo = App::make(AccountRepository::class);

        try {
            $input['email_verified_at'] = Carbon::now();
            /** @var User $user */
            $user = User::create($input);
            $this->assignRoles($user, $input);
            $this->updateProfilePhoto($user, $input);

            $activateCode = $accountRepo->generateUserActivationToken($user->id);
            if (! $user->is_active) {
                $accountRepo->sendConfirmEmail($user->name, $user->email, $activateCode);
            }

            return $user;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  array  $input
     * @param  int  $id
     * @return User
     */
    public function update($input, $id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        try {
            $user->update($input);

            $this->assignRoles($user, $input);
            $this->updateProfilePhoto($user, $input);

            return $user;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  int  $id
     * @param  array  $input
     * @return array
     */
    public function getConversation($id, $input)
    {
        $orderBy = 'desc';
        $user = $firstUnreadMessage = $lastUnreadMsg = null;
        $group = $lastConversation = null;
        $isGroup = (isset($input['is_group'])) ? $input['is_group'] : 0;
        $authUser = getLoggedInUser();
        $limit = 30;
        $chatRequest = null;
        $authId = getLoggedInUserId();

        if ($isGroup) {
            // TODO : will use this later to load only unread messages
            $unreadCount = GroupMessageRecipient::whereNull('read_at')
                ->orderBy('conversation_id', 'asc')
                ->where('group_id', $id)
                ->where('user_id', Auth::id())
                ->count();

            $firstUnreadMessage = GroupMessageRecipient::whereNull('read_at')
                ->orderBy('conversation_id', 'asc')
                ->where('group_id', $id)
                ->where('user_id', Auth::id())
                ->first(['conversation_id', 'id']);

            $limit = ($unreadCount == 0) ? 100 : $limit;
            if ($unreadCount != 0 && $unreadCount < 20) {
                // load last 50 unread messages
                $firstUnreadMessage = GroupMessageRecipient::where('conversation_id', '<=',
                    $firstUnreadMessage->conversation_id)
                    ->orderBy('conversation_id', 'desc')
                    ->where('group_id', $id)
                    ->where('user_id', Auth::id())
                    ->limit(50)
                    ->offset(20)
                    ->first(['conversation_id']);
            }

            /** @var Group $group */
            $group = Group::with([
                'users.userStatus', 'users.roles', 'usersWithTrashed', 'lastConversations', 'createdByUser',
            ])->find($id);
            $group->append('group_created_by');
            $group->users;
            if (empty($group)) {
                throw new BadRequestHttpException('Group not found.');
            }

            if ($group->removed_from_group) {
                $id = $group->id;
                $lastConversation = $group->lastConversations->keyBy('user_id');
                if (isset($lastConversation[getLoggedInUserId()])) {
                    $lastConversation = $lastConversation[getLoggedInUserId()];
                    $group = $lastConversation->group_details;
                }
            }
        } else {
            /** @var User $user */
            $user = User::with(['roles', 'userStatus', 'reportedUser'])->find($id);
            if (empty($user)) {
                throw new BadRequestHttpException('User not found.');
            }

            $isPrivateAccount = ($user->privacy == 0) ? true : false;
            $isSuperAdmin = $user->hasRole(Role::ADMIN_ROLE_NAME);
            $isMyContact = $this->checkIsMyContact($id);
            $isReqSendReceive = ($isMyContact) ? false : true;
            if (! $isMyContact) {
                $chatRequest = $this->checkIsRequestSendOrReceive($id);
                $isReqSendReceive = ! empty($chatRequest) ? true : false;
            }
            $commonGroups = Group::with(['users' => function ($q) {
                $q->select(['users.name', 'users.id']);
            }])->whereHas('users', function ($q) use ($id, $authUser) {
                return $q->whereIn('users.id', [$id, $authUser->id]);
            }, '>=', 2)
                ->get()->toArray();

            $user = $user->toArray();

            /** @var BlockedUser $isBlocked */
            $blockedUser = BlockedUser::whereBlockedBy($authUser->id)->whereBlockedTo($user['id'])
                ->orWhere(function (Builder $query) use ($user, $authUser) {
                    $query->where('blocked_by', $user['id'])->where('blocked_to', $authUser->id);
                })->get()->keyBy('blocked_by');

            $isBlockedByAuthUser = false;
            if (! empty($blockedUser) && isset($blockedUser[Auth::id()])) {
                $isBlockedByAuthUser = true;
            }

            $user['is_blocked_by_auth_user'] = $isBlockedByAuthUser;
            $user['is_blocked'] = (! $blockedUser->isEmpty()) ? true : false;
            $user['is_super_admin'] = $isSuperAdmin;
            $user['is_my_contact'] = $isMyContact;
            $user['is_req_send_receive'] = $isReqSendReceive;
            $user['is_private_account'] = $isPrivateAccount;
            $user['common_groups'] = $commonGroups;
        }
        $allMedia = [];

        if (isset($input['before']) && empty($input['before'])) {
            return ['user' => $user, 'group' => $group, 'conversations' => [], 'media' => $allMedia];
        }
        if (isset($input['after']) && empty($input['after'])) {
            return ['user' => $user, 'group' => $group, 'conversations' => [], 'media' => $allMedia];
        }
        $query = Conversation::with(['replyMessage.sender']);
        $query->whereDoesntHave('messageAction');

        if ($isGroup) {
            $query->leftJoin('group_message_recipients as gmr', function (JoinClause $join) use ($authId) {
                $join->on('gmr.user_id', '=', DB::raw("$authId"));
                $join->on('gmr.conversation_id', '=', 'conversations.id');
            })
                ->leftJoin('users as u', 'u.id', '=', 'conversations.from_id')
                ->whereNull('u.deleted_at')
                ->withCount('readByAll')
                ->selectRaw(
                    "conversations.*, CASE WHEN gmr.read_at IS NULL and gmr.user_id = $authId THEN 0 ELSE 1 END as status");
            $query->where('conversations.to_type', '=', Group::class)
                ->where('to_id', '=', $id);

            if (! empty($lastConversation)) {
                $query->where('conversations.id', '<=', $lastConversation->conversation_id);
            }

            $query->with(['sender', 'receiver', 'readBy']);
        } else {
            $query->where(function (Builder $q) use ($authUser, $id) {
                $q->where(function (Builder $q) use ($authUser, $id) {
                    $q->where('from_id', '=', $authUser->id)->where('to_id', '=', $id);
                })->orWhere(function (Builder $q) use ($authUser, $id) {
                    $q->where('from_id', '=', $id)->where('to_id', '=', $authUser->id);
                });
            });
            $query->where('conversations.to_type', '=', Conversation::class);
            $query->with(['sender', 'receiver']);
        }

        $mediaQuery = clone $query;
        $countQuery = clone $query;
        $unreadCount = $countQuery->where('status', '=', 0)->where('to_id', '=', getLoggedInUserId())->count();
        if (! isset($input['before']) && ! isset($input['after'])) {
            $allMedia = $mediaQuery->whereIn('message_type', Conversation::MEDIA_MESSAGE_TYPES
            )->get(['conversations.*']);
        }

        $needToReverse = false;

        if (isset($input['before']) && ! empty($input['before'])) {
            $query->where('conversations.id', '<', $input['before']);
        } elseif (isset($input['after']) && ! empty($input['after'])) {
            $query->where('conversations.id', '>', $input['after']);
            $orderBy = 'ASC';
        } elseif ($unreadCount > $limit) {
            $query->where('status', '=', 0);
            $orderBy = 'ASC';
            $needToReverse = true;
        }

        if (! empty($firstUnreadMessage) && ! isset($input['before']) && ! isset($input['after'])) {
            $orderBy = 'ASC';
            $needToReverse = true;
            $query->where('conversations.id', '>=', $firstUnreadMessage->conversation_id);
        }

        $query->limit($limit);
        $query->orderBy('conversations.id', $orderBy);
        $messages = $query->get(['conversations.*'])->toArray();
        $messages = ($needToReverse) ? array_reverse($messages) : $messages;

        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = app(NotificationRepository::class);
        $notificationRepo->readNotificationWhenOpenChatWindow($id);

        return [
            'user' => $user, 'group' => $group, 'conversations' => $messages, 'media' => $allMedia,
            'chat_request' => $chatRequest,
        ];
    }

    /**
     * @param  array  $input
     * @return bool|void
     *
     * @throws ApiOperationFailedException
     */
    public function updateProfile($input)
    {
        /** @var User $user */
        $user = Auth::user();
        if (empty($user)) {
            throw new BadRequestHttpException('User not found.');
        }

        $this->updateProfilePhoto($user, $input);

        return true;
    }

    public function updateProfilePhoto(User $user, $input)
    {
        try {
            $options = ['height' => User::HEIGHT, 'width' => User::WIDTH];
            if (! empty($input['photo'])) {
                $input['photo_url'] = ImageTrait::makeImage($input['photo'], User::$PATH, $options);

                $oldImageName = $user->photo_url;
                if (! empty($oldImageName)) {
                    $user->deleteImage();
                }
            }

            $input['is_subscribed'] = isset($input['is_subscribed']) ? 1 : 0;
            $user->update($input);

            $broadcastData['type'] = User::PROFILE_UPDATES;
            $broadcastData['user'] = $user->fresh()->toArray();
            broadcast(new UpdatesEvent($broadcastData))->toOthers();
        } catch (Exception $e) {
            throw new ApiOperationFailedException($e->getMessage());
        }
    }

    /**
     * @param $user
     * @param $input
     * @return bool
     */
    public function assignRoles($user, $input)
    {
        $roles = ! empty($input['role_id']) ? $input['role_id'] : [];
        /** @var User $user */
        $user->roles()->sync($roles);

        return true;
    }

    /**
     * @param  int  $id
     * @return User
     */
    public function activeDeActiveUser($id)
    {
        /** @var User $user */
        $user = User::findOrFail($id);
        $user->update(['is_active' => ! $user->is_active]);

        return $user;
    }

    /**
     * @param  array  $input
     * @return bool
     */
    public function storeAndUpdateNotification($input)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->update($input);

        return true;
    }

    /**
     * @param $input
     * @return UserStatus|Builder|Model|object|null
     */
    public function setUserCustomStatus($input)
    {
        $input['user_id'] = getLoggedInUserId();
        $input['emoji'] = str_replace($input['emoji_short_name'], '', $input['emoji']);
        $userStatus = UserStatus::where('user_id', '=', getLoggedInUserId())->first();
        if (! empty($userStatus)) {
            $userStatus->update($input);
        } else {
            $userStatus = UserStatus::create($input);
        }

        $broadcastData['type'] = User::STATUS_UPDATE;
        $broadcastData['user_status'] = [
            'user_id' => $userStatus->user_id,
            'emoji' => $userStatus->emoji,
            'status' => $userStatus->status,
        ];
        broadcast(new UpdatesEvent($broadcastData))->toOthers();

        return $userStatus;
    }

    /**
     * @throws Exception
     */
    public function clearUserCustomStatus()
    {
        UserStatus::whereUserId(getLoggedInUserId())->delete();

        $broadcastData['type'] = User::STATUS_CLEAR;
        $broadcastData['user_id'] = getLoggedInUserId();
        broadcast(new UpdatesEvent($broadcastData))->toOthers();
    }

    /**
     * @return array
     */
    public function myContactIds()
    {
        $authId = getLoggedInUserId();
        $records = ChatRequestModel::whereOwnerType(User::class)
            ->where(function (Builder $query) use ($authId) {
                $query->where('from_id', '=', $authId)
                    ->orWhere('owner_id', '=', $authId);
            })
            ->get(DB::raw("IF(from_id=$authId, owner_id, from_id) as my_contact_id, status"))
            ->groupBy('status');

        $myContactIds = (isset($records[ChatRequestModel::STATUS_ACCEPTED])) ?
            $records[ChatRequestModel::STATUS_ACCEPTED]->pluck('my_contact_id')->toArray() :
            [];

        $declinedIds = (isset($records[ChatRequestModel::STATUS_DECLINE])) ?
            $records[ChatRequestModel::STATUS_DECLINE]->pluck('my_contact_id')->toArray() :
            [];

        $pendingIds = (isset($records[ChatRequestModel::STATUS_PENDING])) ?
            $records[ChatRequestModel::STATUS_PENDING]->pluck('my_contact_id')->toArray() :
            [];

        $myPendingRequestIds = array_merge($declinedIds, $pendingIds);

        $conversationUserIds = Conversation::whereToType(Conversation::class)
            ->where(function (Builder $query) use ($authId) {
                $query->where('from_id', '=', $authId)
                    ->orWhere('to_id', '=', $authId);
            })
            ->toBase()
            ->get(DB::raw("IF(from_id=$authId, to_id, from_id) as my_contact_id"))
            ->pluck('my_contact_id')
            ->toArray();
        $conversationUserIds = array_unique($conversationUserIds);

        $myContactIds = array_unique(array_merge($myContactIds, $conversationUserIds));
        $myContactIds = array_diff($myContactIds, $myPendingRequestIds);

        return array_unique($myContactIds);
    }

    /**
     * @return array
     */
    public function myPendingRequest()
    {
        $authId = getLoggedInUserId();
        $myContactIds = ChatRequestModel::whereIn('status',
            [ChatRequestModel::STATUS_PENDING, ChatRequestModel::STATUS_DECLINE])
            ->whereOwnerType(User::class)
            ->where(function (Builder $query) use ($authId) {
                $query->where('from_id', '=', $authId)
                    ->orWhere('owner_id', '=', $authId);
            })
            ->get(DB::raw("IF(from_id=$authId, owner_id, from_id) as my_contact_id"))
            ->pluck('my_contact_id')
            ->toArray();

        return array_unique($myContactIds);
    }

    /**
     * @param  int  $userId
     * @return bool
     */
    public function checkIsMyContact($userId)
    {
        $myContactIds = $this->myContactIds();

        return in_array($userId, $myContactIds) ? true : false;
    }

    /**
     * @param $userId
     * @return ChatRequestModel|Builder|Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function checkIsRequestSendOrReceive($userId)
    {
        $authId = getLoggedInUserId();

        return ChatRequestModel::whereOwnerType(User::class)
            ->whereIn('status', [ChatRequestModel::STATUS_PENDING, ChatRequestModel::STATUS_DECLINE])
            ->where(function (Builder $query) use ($authId, $userId) {
                $query->where(function (Builder $q) use ($authId, $userId) {
                    $q->where('from_id', '=', $authId)
                        ->where('owner_id', '=', $userId);
                })->orWhere(function (Builder $q) use ($authId, $userId) {
                    $q->where('owner_id', '=', $authId)
                        ->where('from_id', '=', $userId);
                });
            })->first();
    }

    /**
     * @param  int  $userId
     *
     * @throws Exception
     */
    public function deleteUser($userId)
    {
        try {
            DB::beginTransaction();

            ArchivedUser::whereArchivedBy($userId)->orWhere(function (Builder $q) use ($userId) {
                $q->where('owner_id', '=', $userId)
                    ->where('owner_type', '=', User::class);
            })->delete();
            BlockedUser::whereBlockedBy($userId)->orWhere('blocked_to', '=', $userId)->delete();
            ChatRequestModel::whereFromId($userId)->orWhere(function (Builder $q) use ($userId) {
                $q->where('owner_id', '=', $userId)
                    ->where('owner_type', '=', User::class);
            })->delete();
            Conversation::whereFromId($userId)->orWhere('to_id', '=', $userId)->delete();
            MessageAction::whereDeletedBy($userId)->delete();
            Notification::whereOwnerId($userId)->whereOwnerType(User::class)->forceDelete();
            GroupUser::whereUserId($userId)->forceDelete();
            GroupMessageRecipient::whereUserId($userId)->delete();
            LastConversation::whereUserId($userId)->delete();
            ReportedUser::whereReportedTo($userId)->orWhere('reported_by', '=', $userId)->delete();
            UserStatus::whereUserId($userId)->delete();
            SocialAccount::whereUserId($userId)->delete();
            User::whereId($userId)->forceDelete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage(), $exception->getCode());
        }
    }

    /**
     * @param  int  $userId
     */
    public function restore($userId)
    {
        $user = User::withTrashed()->whereId($userId)->first();
        if ($user->is_system) {
            throw new UnprocessableEntityHttpException('You can not update system generated user.');
        }
        $user->restore();
    }

    /**
     * @param  int  $userId
     * @return bool
     */
    public function checkUserItSelf($userId)
    {
        if ($userId == getLoggedInUserId()) {
            throw new UnprocessableEntityHttpException('You can not update yourself');
        }

        return false;
    }
}
