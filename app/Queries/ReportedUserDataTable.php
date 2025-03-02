<?php
/**
 * Company: InfyOm Technologies, Copyright 2019, All Rights Reserved.
 *
 * User: Monika Vaghasiya
 * Email: monika.vaghasiya@infyom.com
 * Date: 11/13/2019
 * Time: 01:14 PM
 */

namespace App\Queries;

use App\Models\ReportedUser;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserDataTable.
 */
class ReportedUserDataTable
{
    /**
     * @return Builder
     */
    public function get($input = [])
    {
        return ReportedUser::with(['reportedBy', 'reportedTo'])
            ->whereHas('reportedBy')
            ->whereHas('reportedTo', function (Builder $q) use ($input) {
                $q->when(isset($input['is_active_filter']), function (Builder $q) use ($input) {
                    if ($input['is_active_filter'] == ReportedUser::IS_ACTIVE_FILTER_ACTIVE) {
                        $q->where('is_active', '=', 1);
                    }
                    if ($input['is_active_filter'] == ReportedUser::IS_ACTIVE_FILTER_INACTIVE) {
                        $q->where('is_active', '=', 0);
                    }
                });
            })->select('reported_users.*');
    }
}
