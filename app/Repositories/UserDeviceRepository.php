<?php

namespace App\Repositories;

use App\Models\UserDevice;

/**
 * Class UserDeviceRepository
 */
class UserDeviceRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'player_id',
        'user_id',
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
     * Configure the Model.
     **/
    public function model()
    {
        return UserDevice::class;
    }

    /**
     * @param  array  $input
     * @return UserDevice|bool
     */
    public function store($input)
    {
        $isExist = UserDevice::wherePlayerId($input['player_id'])->whereUserId($input['user_id'])->exists();

        if ($isExist) {
            return false;
        }

        return UserDevice::create($input);
    }
}
