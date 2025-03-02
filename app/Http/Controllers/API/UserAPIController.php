<?php

namespace App\Http\Controllers\API;

use App;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\CreateUserStatusRequest;
use App\Http\Requests\UpdateUserNotificationRequest;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Models\ArchivedUser;
use App\Models\BlockedUser;
use App\Models\Group;
use App\Models\User;
use App\Models\UserDevice;
use App\Repositories\UserDeviceRepository;
use App\Repositories\UserRepository;
use Auth;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class UserAPIController
 */
class UserAPIController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return JsonResponse
     */
    public function getUsersList(): JsonResponse
    {
        $myContactIds = $this->userRepository->myContactIds();
        $userIds = BlockedUser::orwhere('blocked_by', getLoggedInUserId())
            ->orWhere('blocked_to', getLoggedInUserId())
            ->pluck('blocked_by', 'blocked_to')
            ->toArray();

        $userIds = array_unique(array_merge($userIds, array_keys($userIds)));
        $userIds = array_unique(array_merge($userIds, $myContactIds));

        $users = User::whereNotIn('id', $userIds)
            ->orderBy('name', 'asc')
            ->select(['id', 'is_online', 'gender', 'photo_url', 'name'])
            ->limit(50)
            ->get()
            ->except(getLoggedInUserId());

        return $this->sendResponse(['users' => $users], 'Users retrieved successfully.');
    }

    /**
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = User::orderBy('name', 'asc')->get()->except(getLoggedInUserId());

        return $this->sendResponse(['users' => $users], 'Users retrieved successfully.');
    }

    /**
     * @return JsonResponse
     */
    public function getProfile(): JsonResponse
    {
        $authUser = getLoggedInUser();
        $authUser->roles;
        $authUser = $authUser->apiObj();

        return $this->sendResponse(['user' => $authUser], 'Users retrieved successfully.');
    }

    /**
     * @param  UpdateUserProfileRequest  $request
     * @return JsonResponse
     */
    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        try {
            $this->userRepository->updateProfile($request->all());

            return $this->sendSuccess('Profile updated successfully.');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateLastSeen(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $lastSeen = ($request->has('status') && $request->get('status') > 0) ? null : Carbon::now();

        $user->update(['last_seen' => $lastSeen, 'is_online' => $request->get('status')]);

        return $this->sendResponse(['user' => $user], 'Last seen updated successfully.');
    }

    /**
     * @param  int  $id
     * @param  Request  $request
     * @return JsonResponse
     */
    public function getConversation($id, Request $request): JsonResponse
    {
        $input = $request->all();
        $data = $this->userRepository->getConversation($id, $input);

        return $this->sendResponse($data, 'Conversation retrieved successfully.');
    }

    /**
     * @param  ChangePasswordRequest  $request
     * @return JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $input = $request->all();

        $input['password'] = Hash::make($input['password']);

        $user->update(['password' => $input['password']]);

        return $this->sendSuccess('Password updated successfully.');
    }

    /**
     * @param  UpdateUserNotificationRequest  $request
     * @return JsonResponse
     */
    public function updateNotification(UpdateUserNotificationRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['is_subscribed'] = ($input['is_subscribed'] == 'true') ? true : false;

        $this->userRepository->storeAndUpdateNotification($input);

        return $this->sendSuccess('Notification updated successfully.');
    }

    /**
     * @return JsonResponse
     */
    public function removeProfileImage(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $user->deleteImage();

        return $this->sendSuccess('Profile image deleted successfully.');
    }

    /**
     * @param $ownerId
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function archiveChat($ownerId): JsonResponse
    {
        $archivedUser = ArchivedUser::whereOwnerId($ownerId)->whereArchivedBy(getLoggedInUserId())->first();
        if (is_string($ownerId) && ! is_numeric($ownerId)) {
            $ownerType = Group::class;
        } else {
            $ownerType = User::class;
        }

        if (empty($archivedUser)) {
            ArchivedUser::create([
                'owner_id' => $ownerId,
                'owner_type' => $ownerType,
                'archived_by' => getLoggedInUserId(),
            ]);
        }

        return $this->sendSuccess('Chat archived successfully.');
    }

    /**
     * @param $ownerId
     * @return JsonResponse
     */
    public function unArchiveChat($ownerId): JsonResponse
    {
        $archivedUser = ArchivedUser::whereOwnerId($ownerId)->whereArchivedBy(getLoggedInUserId())->first();
        $archivedUser->delete();

        return $this->sendSuccess('Chat unarchived successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updatePlayerId(Request $request): JsonResponse
    {
        $playerId = $request->get('player_id');
        $input['user_id'] = Auth::id();
        $input['player_id'] = $playerId;

        /** @var UserDeviceRepository $deviceRepo */
        $deviceRepo = App::make(UserDeviceRepository::class);
        $deviceRepo->store($input);

        $myPlayerIds = UserDevice::whereUserId(Auth::id())->get();

        return $this->sendResponse($myPlayerIds, 'Player updated successfully.');
    }

    /**
     * @param  CreateUserStatusRequest  $request
     * @return JsonResponse
     */
    public function setUserCustomStatus(CreateUserStatusRequest $request): JsonResponse
    {
        $input = $request->only(['emoji', 'status', 'emoji_short_name']);

        $userStatus = $this->userRepository->setUserCustomStatus($input);

        return $this->sendResponse($userStatus, 'Your status set successfully.');
    }

    /**
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function clearUserCustomStatus(): JsonResponse
    {
        $this->userRepository->clearUserCustomStatus();

        return $this->sendSuccess('Your status cleared successfully.');
    }

    /*
    * @return JsonResponse
    */
    public function myContacts(): JsonResponse
    {
        $myContactIds = $this->userRepository->myContactIds();

        $users = User::with(['userStatus' => function (HasOne $query) {
            $query->select(['status', 'emoji']);
        }])
            ->whereIn('id', $myContactIds)
            ->select(['id', 'name', 'photo_url', 'is_online'])
            ->orderBy('name', 'asc')
            ->limit(100)
            ->get();

        return $this->sendResponse([
            'users' => $users,
            'myContactIds' => $myContactIds,
        ], 'Users retrieved successfully.');
    }
}
