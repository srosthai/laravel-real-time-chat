<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class RoleRepository
 *
 * @version November 12, 2019, 11:13 am UTC
 */
class RoleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'guard_name',
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
        return Role::class;
    }

    /**
     * @param $input
     * @return Role
     *
     * @throws \Exception
     */
    public function storeRole($input)
    {
        try {
            DB::beginTransaction();
            /** @var Role $role */
            $role = Role::create([
                'name' => $input['name'],
                'guard_name' => 'web',
            ]);
            if (isset($input['permissions'])) {
                $role->syncPermissions($input['permissions']);
            }
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param $input
     * @param $role
     * @return Role
     *
     * @throws \Exception
     */
    public function updateRole($input, $role)
    {
        try {
            DB::beginTransaction();
            /** @var Role $role */
            $role->update($input);
            if (isset($input['permissions'])) {
                $role->syncPermissions($input['permissions']);
            } else {
                $role->revokePermissionTo($role->getAllPermissions());
            }
            DB::commit();

            return $role;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }
}
