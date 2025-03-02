<?php

namespace App\Repositories;

use App\Events\GroupEvent;
use App\Events\UserEvent;
use App\Models\Conversation;
use App\Models\Group;
use App\Models\GroupMessageRecipient;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Container\Container as Application;

/**
 * Class ChatRepository
 */
class NotificationRepository extends BaseRepository
{
    /**
     * NotificationRepository constructor.
     *
     * @param  Application  $app
     *
     * @throws Exception
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);
    }

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'owner_id',
        'owner_type',
        'notification',
        'to_id',
        'is_read',
        'read_at',
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
        return Notification::class;
    }

    /**
     * @param  array  $notificationInput
     * @param  int  $receiverId
     */
    public function sendNotification($notificationInput, $receiverId, $userEventType = 0)
    {
        /** @var Notification $notification */
        $notification = $this->create($notificationInput);
        $notificationArray = $notification->toArray();

        $notificationCount = Notification::whereOwnerId($notificationInput['owner_id'])->whereToId($notificationInput['to_id'])->whereIsRead(0)->get()->count();

        $sender = getLoggedInUser();
        if ($notification->owner_type == User::class) {
            $sender = [
                'id' => $sender->id,
                'name' => $sender->name,
                'is_online' => $sender->is_online,
                'profile_url' => $sender->photo_url,
            ];
        } else {
            $sender = [
                'id' => $notification->group->id,
                'name' => $notification->group->name,
                'is_online' => 0,
                'profile_url' => $notification->group->photo_url,
            ];
        }
        $notificationArray['unread_count'] = $notificationCount;
        $notificationArray['senderUser'] = $sender;
        $notificationArray['type'] = ($userEventType > 0) ? $userEventType : User::MESSAGE_NOTIFICATION;

        broadcast(new UserEvent($notificationArray, $receiverId))->toOthers();
    }

    /**
     * @return array
     */
    public function getNotifications()
    {
        $notifications = Notification::whereIsRead(0)->whereToId(getLoggedInUserId())->with([
            'sender', 'group', 'latestMsg',
        ])->selectRaw(
            'max(notifications.id) as latest_id,
            sum(if(notifications.is_read = 0, 1, 0)) as unread_count,
            notifications.*'
        )->orderBy('notifications.created_at', 'desc')->groupBy('owner_id')->get();

        $notificationsArray = [];
        foreach ($notifications as $notification) {
            /** @var Notification $notification */
            $notificationArray = $notification->latestMsg->toArray();
            $notificationArray['unread_count'] = $notification->unread_count;
            if ($notification->owner_type == User::class) {
                $sender = [
                    'id' => $notification->sender->id,
                    'name' => $notification->sender->name,
                    'is_online' => $notification->sender->is_online,
                    'profile_url' => $notification->sender->photo_url,
                ];
            } else {
                $sender = [
                    'id' => $notification->group->id,
                    'name' => $notification->group->name,
                    'is_online' => 0,
                    'profile_url' => $notification->group->photo_url,
                ];
            }
            $notificationArray['senderUser'] = $sender;
            $notificationsArray[] = $notificationArray;
        }

        return $notificationsArray;
    }

    /**
     * @param  int  $notificationId
     */
    public function readNotification($notificationId)
    {
        $notification = Notification::find($notificationId);

        Notification::whereToId($notification->to_id)->whereOwnerId($notification->owner_id)->update([
            'is_read' => true, 'read_at' => Carbon::now(),
        ]);
    }

    /**
     * @param  int  $senderId
     */
    public function readNotificationWhenOpenChatWindow($senderId)
    {
        Notification::whereOwnerId($senderId)->whereToId(getLoggedInUserId())->update([
            'is_read' => 1, 'read_at' => Carbon::now(),
        ]);
    }

    /**
     * @return array
     */
    public function readAllNotification()
    {
        $authId = getLoggedInUserId();
        $groupMessageQuery = Notification::whereToId($authId)->whereOwnerType(Group::class)->whereIsRead(0);
        $groupIds = $groupMessageQuery->select(['owner_id'])->groupBy('owner_id')->pluck('owner_id')->toArray();

        Notification::whereToId($authId)->update(['is_read' => true, 'read_at' => Carbon::now()]);

        $groupMsgRecipient = GroupMessageRecipient::whereUserId($authId)->whereNull('read_at')->whereIn('group_id', $groupIds);
        $groupMessageIds = array_unique($groupMsgRecipient->select(['conversation_id'])->pluck('conversation_id')->toArray());
        $groupMsgRecipient->update(['read_at' => Carbon::now()]);

        foreach ($groupIds as $groupId) {
            broadcast(new GroupEvent(
                [
                    'group' => ['id' => $groupId],
                    'type' => Group::GROUP_MESSAGE_READ_BY_MEMBER,
                    'conversation_ids' => $groupMessageIds,
                    'read_by_user_id' => $authId,
                    'read_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]))->toOthers();
        }

        // Group by given conversations record
        $records = GroupMessageRecipient::with('conversation')->whereIn('conversation_id', $groupMessageIds)->get([
            'read_at', 'conversation_id',
        ])->groupBy('conversation_id');
        // If given conversation id read by all members than fire read all msg event
        foreach ($groupMessageIds as $id) {
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

        $query = Conversation::whereToId($authId)->where('to_type', '=', Conversation::class)->where('status', '=', 0);
        $messageIds = array_unique($query->select(['id'])->pluck('id')->toArray());
        $messageSenderIds = $query->select(['from_id'])->groupBy('from_id')->pluck('from_id')->toArray();
        $query->update(['status' => 1]);

        foreach ($messageSenderIds as $senderId) {
            broadcast(new UserEvent(
                [
                    'user_id' => $authId,
                    'ids' => $messageIds,
                    'type' => User::PRIVATE_MESSAGE_READ,
                ], $senderId))->toOthers();
        }

        return array_merge($messageSenderIds, $groupIds);
    }
}
