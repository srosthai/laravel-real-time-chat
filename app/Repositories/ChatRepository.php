<?php

namespace App\Repositories;

use App\Events\GroupEvent;
use App\Events\UserEvent;
use App\Exceptions\ApiOperationFailedException;
use App\Jobs\SendOneSignalPushJob;
use App\Models\ArchivedUser;
use App\Models\ChatRequestModel;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\GroupMessageRecipient;
use App\Models\MessageAction;
use App\Models\User;
use App\Traits\ImageTrait;
use Auth;
use Carbon\Carbon;
use DB;
use Embed\Embed;
use Exception;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class ChatRepository
 */
class ChatRepository extends BaseRepository
{
    private $groupRepo;

    public function __construct(Application $app, GroupRepository $groupRepository)
    {
        parent::__construct($app);
        $this->groupRepo = $groupRepository;
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'from_id', 'to_id', 'message', 'status', 'file_name',
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
        return Conversation::class;
    }

    /**
     * @param  array  $input
     * @return array
     */
    public function getLatestConversations($input = [])
    {
        $offset = isset($input['offset']) ? $input['offset'] : 0;
        $isArchived = isset($input['isArchived']) ? 1 : 0;
        $authId = getLoggedInUserId();
        $isGroupChatEnabled = isGroupChatEnabled();

        $subQuery = Conversation::leftJoin('users as u', 'u.id', '=', DB::raw("if(from_id = $authId, to_id, from_id)"))
            ->leftJoin('message_action as ma', function (JoinClause $join) use ($authId) {
                $join->on('ma.deleted_by', '=', DB::raw("$authId"));
                $join->on('ma.conversation_id', '=', 'conversations.id');
            })
            ->whereNull('u.deleted_at')
            ->where(function (Builder $q) use ($authId) {
                $q->where('from_id', '=', $authId)->orWhere('to_id', '=', $authId);
            })
            ->where(function (Builder $q) {
                $q->whereColumn('ma.conversation_id', '!=', 'conversations.id')
                    ->orWhereNull('ma.conversation_id');
            })
            ->where('conversations.to_type', '=', Conversation::class)
            ->selectRaw(
                "max(conversations.id) as latest_id , u.id as user_id, 0 as group_id,
                 sum(if(conversations.status = 0 and from_id != $authId, 1, 0)) as unread_count"
            )
            ->groupBy(DB::raw("if(from_id = $authId, to_id, from_id)"));

        if ($isGroupChatEnabled) {
            $groupSubQuery = Conversation::leftJoin('message_action as ma', function (JoinClause $join) use ($authId) {
                $join->on('ma.deleted_by', '=', DB::raw("$authId"));
                $join->on('ma.conversation_id', '=', 'conversations.id');
            });
            if (! $isArchived) {
                $groupSubQuery->has('archiveConversation', '=', 0);
            }
            $groupSubQuery->leftJoin('group_message_recipients as gmr', function (JoinClause $join) use ($authId) {
                $join->on('gmr.user_id', '=', DB::raw("$authId"));
                $join->on('gmr.conversation_id', '=', 'conversations.id');
            })
                ->leftJoin('users as u', 'u.id', '=', 'conversations.from_id')
                ->whereNull('u.deleted_at')
                ->whereRaw('to_id IN (select group_id from group_users where user_id = ?)', [$authId])
                ->where('conversations.to_type', '=', Group::class)
                ->where(function (Builder $q) {
                    $q->whereColumn('ma.conversation_id', '!=', 'conversations.id')
                        ->orWhereNull('ma.conversation_id');
                })
                ->selectRaw(
                    "max(conversations.id) as latest_id , 0 as user_id, to_id as group_id,
             SUM(CASE WHEN gmr.read_at IS NULL and user_id = $authId THEN 1 END) as unread_count"

                )->whereHas('group.usersWithTrashed', function (Builder $q) use ($authId) {
                    $q->where('user_id', $authId); // To get conversations in which user is
                })
                ->groupBy('conversations.to_id');

            $bindings = array_merge($subQuery->getBindings(), $groupSubQuery->getBindings());
            $groupSubQueryStr = $groupSubQuery->toSql();
            $relations = ['group.lastConversations.conversation', 'user.userStatus', 'group.usersWithTrashed'];
        } else {
            $bindings = $subQuery->getBindings();
            $relations = ['user.userStatus'];
        }
        $subQueryStr = $subQuery->toSql();

        $archiveUsers = ArchivedUser::toBase()
            ->select('owner_id')
            ->whereArchivedBy(getLoggedInUserId())
            ->whereOwnerType(User::class)
            ->pluck('owner_id')->toArray();

        $chatList = Conversation::with($relations)->newQuery();
        $chatList = $chatList->select('temp.*', 'cc.*');
        if ($isGroupChatEnabled) {
            $chatList->from(DB::raw("($subQueryStr union $groupSubQueryStr) as temp"));
        } else {
            $chatList->from(DB::raw("($subQueryStr) as temp"));
        }
        $chatList->setBindings($bindings)
            ->leftJoin('conversations as cc', 'cc.id', '=', 'temp.latest_id');
        if (! $isArchived) {
            $chatList = $chatList->whereNotIn('temp.user_id', $archiveUsers);
        } else {
            $archiveGroups = [];
            if ($isGroupChatEnabled) {
                $archiveGroups = ArchivedUser::whereArchivedBy(getLoggedInUserId())->whereOwnerType(Group::class)->get()->pluck('owner_id')->toArray();
            }
            $chatList = $chatList->where(function ($query) use ($archiveUsers, $archiveGroups, $isGroupChatEnabled) {
                $query->whereIn('temp.user_id', $archiveUsers);
                if ($isGroupChatEnabled) {
                    $query->orWhereIn('temp.group_id', $archiveGroups);
                }
            });
        }
        $chatList = $chatList->orderBy('cc.created_at', 'desc')->offset($offset)->limit(10)
            ->get()->keyBy('id');

        // TODO : refactor this later
        // To replace user's last conversation when he/she leave the group
        $groupsConversation = [];
        if ($isGroupChatEnabled) {
            $groupsConversation = $chatList->where('is_group')->all();
        }

        $chatList = $chatList->toArray();
        if (! $isGroupChatEnabled) {
            return array_values($chatList);
        }

        foreach ($groupsConversation as $conversation) {
            if ($conversation->group->lastConversations->isEmpty()) {
                continue;
            }

            $lastConversations = $conversation->group->lastConversations->keyBy('user_id');
            if (! isset($lastConversations[getLoggedInUserId()])) {
                continue;
            }
            $newConversation = $lastConversations[getLoggedInUserId()]->conversation;
            $chatList[$conversation->id]['id'] = $newConversation->id;
            $chatList[$conversation->id]['message'] = $newConversation->message;
            $chatList[$conversation->id]['from_id'] = $newConversation->from_id;
            $chatList[$conversation->id]['to_id'] = $newConversation->to_id;
            $chatList[$conversation->id]['unread_message'] = 0;
            $chatList[$conversation->id]['group_details'] = $lastConversations[getLoggedInUserId()]->group_details;
        }

        return array_values($chatList);
    }

    /**
     * @param  array  $input
     * @return Conversation
     *
     * @throws Exception
     */
    public function sendMessage($input)
    {
        if (isset($input['is_archive_chat']) && $input['is_archive_chat'] == 1) {
            $archivedUser = ArchivedUser::whereOwnerId($input['to_id'])->whereArchivedBy(getLoggedInUserId())->first();
            if (! empty($archivedUser)) {
                $archivedUser->delete();
            }
        }

        $input['to_type'] = Conversation::class;
        $input['from_id'] = getLoggedInUserId();
        if (isValidURL($input['message'])) {
            $input['message_type'] = detectURL($input['message']);
        }

        $pattern = '~[a-z]+://\S+~';
        $message = $input['message'];

        if ($num_found = preg_match_all($pattern, $message, $out)) {
            $link = $out[0];
            try {
                $info = Embed::create($link[0]);

                $input['url_details'] = [
                    'title' => $info->title,
                    'image' => $info->image,
                    'description' => $info->description,
                    'url' => $info->url,
                ];
            } catch (Exception $e) {
            }
        }

        if (isset($input['is_group']) && $input['is_group']) {
            return $this->sendGroupMessage($input);
        }

        /** @var $conversation Conversation */
        $conversation = $this->create($input)->fresh();
        $conversation->sender;

        $broadcastData = $conversation->toArray();
        if (! empty($conversation->reply_to)) {
            $broadcastData['reply_message']['id'] = $conversation->replyMessage->id;
            $broadcastData['reply_message']['message'] = $conversation->replyMessage->message;
            $broadcastData['reply_message']['sender']['id'] = $conversation->replyMessage->sender->id;
            $broadcastData['reply_message']['sender']['name'] = $conversation->replyMessage->sender->name;
        }
        $broadcastData['type'] = User::NEW_PRIVATE_CONVERSATION;
        broadcast(new UserEvent($broadcastData, $conversation->to_id))->toOthers();

        /** @var User $receiver */
        $receiver = $conversation->receiver;
        if ($receiver->is_subscribed && ! $receiver->devices->isEmpty()) {
            // Send OneSignal Push of user is subscribed
            $headings = $receiver->name.' | '.getAppName();

            foreach ($receiver->devices as $device) {
                $options = [];
                if ($conversation->message_type == 1) {
                    $options['image'] = $conversation->message;
                }
                dispatch(new SendOneSignalPushJob([$device->player_id], $headings, $input['message'], $options));
            }
        }

        $notificationInput = [
            'owner_id' => $conversation['from_id'],
            'owner_type' => User::class,
            'notification' => $conversation['message'],
            'to_id' => $conversation['to_id'],
            'message_type' => $conversation['message_type'],
            'file_name' => $conversation['file_name'],
        ];
        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = app(NotificationRepository::class);
        $notificationRepo->sendNotification($notificationInput, $conversation['to_id']);

        return $conversation;
    }

    /**
     * @param  array  $groupUsers
     * @return bool
     */
    public function groupMessageValidations($groupUsers)
    {
        // If user is removed/leave from group, then her can not able to send message in group
        if (! in_array(getLoggedInUserId(), $groupUsers)) {
            throw new UnprocessableEntityHttpException('Only active member of this group can send message.');
        }

        return true;
    }

    /**
     * @param  array  $input
     * @return Conversation
     */
    public function sendGroupMessage($input)
    {
        $input['to_type'] = Group::class;

        /** @var Group $group */
        $group = Group::with('users')->findOrFail($input['to_id']);
        $groupUsers = $group->users->pluck('id', 'id')->toArray();
        $this->groupMessageValidations($groupUsers);

        /** @var $conversation Conversation */
        $conversation = $this->create($input)->fresh();
        $conversation->sender;
        $broadcastData = $this->prepareGroupChatBroadCastData($conversation->toArray(), $group->toArray());
        if (! empty($conversation->reply_to)) {
            $broadcastData['reply_message']['id'] = $conversation->replyMessage->id;
            $broadcastData['reply_message']['message'] = $conversation->replyMessage->message;
            $broadcastData['reply_message']['sender']['id'] = $conversation->replyMessage->sender->id;
            $broadcastData['reply_message']['sender']['name'] = $conversation->replyMessage->sender->name;
        }
        broadcast(new GroupEvent($broadcastData))->toOthers();
        $this->groupRepo->addRecordsToGroupMessageRecipients($groupUsers, $conversation->id, $group->id);

        // Send PushNotifications to group users who's push are enabled
        $headings = $group->name.' | '.getAppName();
        $playerIds = [];
        $group->users->filter(function (User $user) use (&$playerIds) {
            if ($user->id == getLoggedInUserId()) {
                return;
            }

            if ($user->is_subscribed) {
                foreach ($user->devices as $device) {
                    $playerIds[] = $device->player_id;
                }
            }
        });

        if (count($playerIds)) {
            $options = [];
            if ($conversation->message_type == 1) {
                $options['image'] = $conversation->message;
            }
            dispatch(new SendOneSignalPushJob($playerIds, $headings, $input['message'], $options));
        }
        $conversation->readBy;

        $this->sendGroupMessageNotification($conversation, $group->users);

        return $conversation;
    }

    /**
     * @param  Conversation  $conversation
     * @param  array  $groupMembers
     */
    public function sendGroupMessageNotification($conversation, $groupMembers)
    {
        /** @var User $groupMember */
        foreach ($groupMembers as $groupMember) {
            if ($groupMember->id == getLoggedInUserId()) {
                continue;
            }
            $notificationInput = [
                'owner_id' => $conversation->to_id,
                'owner_type' => Group::class,
                'notification' => $conversation->message,
                'to_id' => $groupMember->id,
                'message_type' => $conversation->message_type,
                'file_name' => $conversation->file_name,
            ];

            /** @var NotificationRepository $notificationRepo */
            $notificationRepo = app(NotificationRepository::class);
            $notificationRepo->sendNotification($notificationInput, $groupMember->id);
        }
    }

    /**
     * @param  array  $conversation
     * @param  array  $group
     * @return mixed
     */
    public function prepareGroupChatBroadCastData($conversation, $group)
    {
        $groupInfo = $group;
        $senderInfo = Auth::user();

        // Remove Unused Information
        unset($conversation['sender']);
        unset($conversation['group']);
        unset($conversation['receiver']);

        $conversation['group']['id'] = $groupInfo['id'];
        $conversation['group']['name'] = $groupInfo['name'];
        $conversation['group']['photo_url'] = $groupInfo['photo_url'];
        $conversation['sender']['id'] = $senderInfo['id'];
        $conversation['sender']['photo_url'] = $senderInfo['photo_url'];
        $conversation['sender']['name'] = $senderInfo['name'];
        $conversation['sender']['role_name'] = $senderInfo['role_name'];
        $conversation['type'] = Group::NEW_GROUP_MESSAGE_ARRIVED;

        return $conversation;
    }

    /**
     * @param  UploadedFile  $file
     * @return string|void
     *
     * @throws ApiOperationFailedException
     */
    public function addAttachment($file)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (! in_array($extension,
            [
                'xls', 'pdf', 'doc', 'docx', 'xlsx', 'jpg', 'gif', 'jpeg', 'png', 'mp4', 'mkv', 'avi', 'txt', 'mp3',
                'ogg', 'wav', 'aac', 'alac',
                'zip', 'rar',
            ])) {
            throw new ApiOperationFailedException('You can not upload this file.', Response::HTTP_BAD_REQUEST);
        }

        if (in_array($extension, ['jpg', 'gif', 'png', 'jpeg'])) {
            $fileName = ImageTrait::makeImage($file, Conversation::PATH, []);

            return $fileName;
        }

        if (in_array($extension, ['xls', 'pdf', 'doc', 'docx', 'xlsx', 'txt'])) {
            $fileName = ImageTrait::makeAttachment($file, Conversation::PATH);

            return $fileName;
        }

        if (in_array($extension, ['mp4', 'mkv', 'avi'])) {
            $fileName = ImageTrait::uploadVideo($file, Conversation::PATH);

            return $fileName;
        }

        if (in_array($extension, ['mp3', 'ogg', 'wav', 'aac', 'alac', 'zip', 'rar'])) {
            $fileName = ImageTrait::uploadFile($file, Conversation::PATH);

            return $fileName;
        }
    }

    /**
     * @param  string  $extension
     * @return int
     */
    public function getMessageTypeByExtension($extension)
    {
        $extension = strtolower($extension);
        if (in_array($extension, ['jpg', 'gif', 'png', 'jpeg'])) {
            return Conversation::MEDIA_IMAGE;
        } elseif (in_array($extension, ['doc', 'docx'])) {
            return Conversation::MEDIA_DOC;
        } elseif ($extension == 'pdf') {
            return Conversation::MEDIA_PDF;
        } elseif (in_array($extension, ['mp3', 'ogg', 'wav', 'aac', 'alac'])) {
            return Conversation::MEDIA_VOICE;
        } elseif (in_array($extension, ['mp4', 'mkv', 'avi'])) {
            return Conversation::MEDIA_VIDEO;
        } elseif (in_array($extension, ['txt'])) {
            return Conversation::MEDIA_TXT;
        } elseif (in_array($extension, ['xls', 'xlsx'])) {
            return Conversation::MEDIA_XLS;
        } elseif (in_array($extension, ['zip', 'rar'])) {
            return Conversation::MEDIA_ZIP;
        } else {
            return 0;
        }
    }

    /**
     * @param  array  $input
     * @return array
     */
    public function markMessagesAsRead($input)
    {
        $senderId = Auth::id();
        $isGroup = (isset($input['is_group']) && $input['is_group'] == '1') ? true : false;
        $remainingUnread = 0;

        if ($isGroup && ! empty($input['ids'])) {
            $this->readGroupMessages($input['ids']);
            $remainingUnread = $this->getUnreadMessageCount($input['group_id'], true);
        } elseif (! $isGroup && ! empty($input['ids'])) {
            $unreadIds = $input['ids'];
            $unreadIds = (is_array($unreadIds)) ? $unreadIds : [$unreadIds];
            $firstUnreadConversationId = $unreadIds[0];
            Conversation::whereIn('id', $unreadIds)->update(['status' => 1]);

            $conversation = Conversation::find($firstUnreadConversationId);
            $senderId = ($conversation->from_id == getLoggedInUserId()) ? $conversation->to_id : $conversation->from_id;
            $receiverId = ($conversation->from_id == getLoggedInUserId()) ? $conversation->from_id : $conversation->to_id;
            $remainingUnread = $this->getUnreadMessageCount($senderId);

            broadcast(new UserEvent(
                [
                    'user_id' => $receiverId,
                    'ids' => $unreadIds,
                    'type' => User::PRIVATE_MESSAGE_READ,
                ], $conversation->from_id))->toOthers();
        }

        return ['senderId' => $senderId, 'remainingUnread' => $remainingUnread];
    }

    /**
     * @param  array  $ids
     * @return bool
     */
    public function readGroupMessages($ids)
    {
        // First Read Given Messages
        $query = GroupMessageRecipient::with('conversation')->whereIn('conversation_id', $ids)
            ->where('user_id', getLoggedInUserId());
        $query->update(['read_at' => Carbon::now()]);

        // Group by given conversations record
        $records = GroupMessageRecipient::with('conversation')->whereIn('conversation_id', $ids)->get([
            'read_at', 'conversation_id',
        ])->groupBy('conversation_id');

        $conversationIds = $query->pluck('conversation_id');
        $readMessages = $query->first();
        if (! empty($readMessages)) {
            broadcast(new GroupEvent(
                [
                    'group' => ['id' => $readMessages->conversation->to_id],
                    'type' => Group::GROUP_MESSAGE_READ_BY_MEMBER,
                    'conversation_ids' => $conversationIds,
                    'read_by_user_id' => Auth::id(),
                    'read_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]))->toOthers();
        }
        // If given conversation id read by all members than fire read all msg event
        foreach ($ids as $id) {
            $readByAll = collect($records[$id])->map(function ($record) {
                if (empty($record->read_at)) {
                    return $record;
                }
            })->toArray();

            if (empty(array_filter($readByAll))) {
                broadcast(new GroupEvent(
                    [
                        'group' => ['id' => $records[$id][0]->conversation->to_id],
                        'type' => Group::GROUP_MESSAGE_READ_BY_ALL_MEMBERS,
                        'conversation_id' => $id,
                        'group_id' => $records[$id][0]->conversation->to_id,
                    ]))->toOthers();
            }
        }

        return true;
    }

    /**
     * @param  int  $senderId
     * @param  bool  $isGroup
     * @return int
     */
    public function getUnreadMessageCount($senderId, $isGroup = false)
    {
        if ($isGroup) {
            return GroupMessageRecipient::whereUserId(Auth::id())->where('group_id', $senderId)
                ->whereNull('read_at')->count();
        }

        return Conversation::where(function (Builder $q) use ($senderId) {
            $q->where('from_id', '=', $senderId)->where('to_id', '=', getLoggedInUserId());
        })->where('status', '=', 0)->message()->count();
    }

    /**
     * @param $userId
     */
    public function deleteConversation($userId)
    {
        $chatIds = Conversation::leftJoin('message_action as ma', function (JoinClause $join) {
            $authUserId = getLoggedInUserId();
            $join->on('ma.deleted_by', '=', DB::raw("$authUserId"));
            $join->on('ma.conversation_id', '=', 'conversations.id');
        })
            ->where(function (Builder $q) use ($userId) {
                $q->where(function (Builder $q) use ($userId) {
                    $q->where('from_id', '=', $userId)
                        ->where('to_id', '=', getLoggedInUserId());
                })->orWhere(function (Builder $q) use ($userId) {
                    $q->where('from_id', '=', getLoggedInUserId())
                        ->where('to_id', '=', $userId);
                });
            })
            ->where(function (Builder $q) {
                $q->whereColumn('ma.conversation_id', '!=', 'conversations.id')
                    ->orWhereNull('ma.conversation_id');
            })
            ->get(['conversations.*'])
            ->pluck('id')
            ->toArray();

        $input = [];
        foreach ($chatIds as $chatId) {
            $input[] = [
                'conversation_id' => $chatId,
                'deleted_by' => getLoggedInUserId(),
            ];
        }

        MessageAction::insert($input);
    }

    /**
     * @param  string  $groupId
     * @return bool
     */
    public function deleteGroupConversation($groupId)
    {
        $conversationIds = Conversation::whereToId($groupId)->pluck('id')->toArray();

        $input = [];
        foreach ($conversationIds as $chatId) {
            $input[] = [
                'conversation_id' => $chatId,
                'deleted_by' => getLoggedInUserId(),
            ];
        }

        MessageAction::insert($input);

        return true;
    }

    /**
     * @param $id
     */
    public function deleteMessage($id)
    {
        MessageAction::create([
            'conversation_id' => $id,
            'deleted_by' => getLoggedInUserId(),
            'is_hard_delete' => 1,
        ]);
    }

    /**
     * @param  array  $input
     * @return Conversation|bool|Model
     *
     * @throws Exception
     */
    public function sendChatRequest($input)
    {
        $toId = $input['to_id'];
        $message = $input['message'];
        $chatRequest = ChatRequestModel::whereFromId(getLoggedInUserId())
            ->whereOwnerId($toId)
            ->where('status', '!=', ChatRequestModel::STATUS_DECLINE)
            ->first();

        //chat request already send
        if (! empty($chatRequest)) {
            return false;
        }

        //check for user has public/private account
        $user = User::find($toId);
        if ($user->privacy > 0) {
            return false;
        }

        // Delete Old declined request
        ChatRequestModel::whereFromId(getLoggedInUserId())
            ->whereOwnerId($toId)
            ->where('owner_type', '=', User::class)
            ->delete();

        // Create new request
        ChatRequestModel::create([
            'from_id' => getLoggedInUserId(),
            'owner_id' => $toId,
            'owner_type' => User::class,
        ]);

        $conversation = Conversation::create([
            'from_id' => getLoggedInUserId(),
            'to_id' => $toId,
            'to_type' => Conversation::class,
            'message' => $message,
            'status' => 0,
            'message_type' => 0,
        ]);
        $conversation->sender;
        $broadcastData = $conversation->toArray();
        $broadcastData['type'] = User::CHAT_REQUEST;
        $broadcastData['owner_type'] = User::class;
        broadcast(new UserEvent($broadcastData, $toId))->toOthers();

        $notificationInput = [
            'owner_id' => $conversation['from_id'],
            'owner_type' => User::class,
            'notification' => $conversation['message'],
            'to_id' => $conversation['to_id'],
            'message_type' => $conversation['message_type'],
            'file_name' => $conversation['file_name'],
        ];
        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = app(NotificationRepository::class);
        $notificationRepo->sendNotification($notificationInput, $conversation['to_id']);

        return $conversation;
    }

    /**
     * @param  array  $chatRequest
     * @param  int  $userEventType
     */
    public function sendAcceptDeclineChatRequestNotification($chatRequest, $userEventType = 0)
    {
        $notificationInput = [
            'owner_id' => $chatRequest['owner_id'],
            'owner_type' => $chatRequest['owner_type'],
            'notification' => $chatRequest['message'],
            'to_id' => $chatRequest['from_id'],
            'message_type' => 0,
        ];

        /** @var NotificationRepository $notificationRepo */
        $notificationRepo = app(NotificationRepository::class);
        $notificationRepo->sendNotification($notificationInput, $chatRequest['from_id'], $userEventType);
    }
}
