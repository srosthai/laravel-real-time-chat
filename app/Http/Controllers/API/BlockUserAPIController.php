<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\BlockedUser;
use App\Models\User;
use App\Repositories\BlockUserRepository;
use Auth;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class BlockUserController
 */
class BlockUserAPIController extends AppBaseController
{
    /**
     * @var BlockUserRepository
     */
    private $blockUserRepository;

    public function __construct(BlockUserRepository $blockUserRepository)
    {
        $this->blockUserRepository = $blockUserRepository;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function blockUnblockUser(Request $request)
    {
        $input = $request->all();

        $input['blocked_by'] = Auth::id();
        $input['is_blocked'] = ($input['is_blocked'] == 'false') ? false : true;
        $blockText = ($input['is_blocked'] == true) ? 'blocked' : 'unblocked';
        $blockedTo = User::findOrFail($input['blocked_to']);

        $this->blockUserRepository->blockUnblockUser($input);

        return $this->sendResponse(['user' => $blockedTo], "User $blockText successfully.");
    }

    /**
     * @return mixed
     */
    public function blockedUsers()
    {
        [$blockedUserIds, $blockByMeUsers] = $this->blockUserRepository->blockedUserIds();

        return $this->sendResponse(
            ['users_ids' => $blockedUserIds], 'Blocked users retrieved successfully.'
        );
    }

    /**
     * @return JsonResponse
     */
    public function blockUsersByMe()
    {
        $blockedUserIds = BlockedUser::whereBlockedBy(Auth::id())->pluck('blocked_to')->toArray();

        $users = User::whereIn('id', $blockedUserIds)
            ->select(['id', 'name', 'photo_url', 'gender', 'is_online'])
            ->get();

        return $this->sendResponse(
            ['users' => $users], 'Blocked users retrieved successfully.'
        );
    }
}
