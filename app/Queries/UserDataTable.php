<?php
/**
 * Company: InfyOm Technologies, Copyright 2019, All Rights Reserved.
 */

namespace App\Queries;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserDataTable.
 */
class UserDataTable
{
    /**
     * @param  array  $input
     * @return Builder
     */
    public function get($input = [])
    {
        $users = User::with(['roles']);
        $users->when(
            isset($input['filter_user']),
            function (Builder $q) use ($input) {
                if ($input['filter_user'] == User::FILTER_ARCHIVE) {
                    $q->onlyTrashed();
                }
                if ($input['filter_user'] == User::FILTER_ALL) {
                    $q->withTrashed();
                }
                if ($input['filter_user'] == User::FILTER_ACTIVE) {
                    $q->where('is_active', '=', 1);
                }
                if ($input['filter_user'] == User::FILTER_INACTIVE) {
                    $q->where('is_active', '=', 0);
                }
            }
        )->when(isset($input['privacy_filter']), function (Builder $q) use ($input) {
            if ($input['privacy_filter'] == User::PRIVACY_FILTER_PUBLIC) {
                $q->where('privacy', '=', 1);
            }
            if ($input['privacy_filter'] == User::PRIVACY_FILTER_PRIVATE) {
                $q->where('privacy', '=', 0);
            }
        })->where('id', '!=', getLoggedInUserId())->where('is_super_admin', '=', 0);

        $users = $users->select([
            'photo_url', 'id', 'name', 'email', 'phone', 'privacy', 'is_active', 'is_super_admin', 'deleted_at',
            'email_verified_at',
        ]);

        return $users;
    }
}
