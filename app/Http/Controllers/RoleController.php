<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Queries\RoleDataTable;
use App\Repositories\RoleRepository;
use DataTables;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Laracasts\Flash\Flash;

class RoleController extends AppBaseController
{
    /** @var RoleRepository */
    private $roleRepository;

    public function __construct(RoleRepository $roleRepo)
    {
        $this->roleRepository = $roleRepo;
    }

    /**
     * @param  Request  $request
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new RoleDataTable())->get())->make(true);
        }

        return view('roles.index');
    }

    /**
     * @return Factory|View
     */
    public function create()
    {
        $permissions = Permission::toBase();

        return view('roles.create', compact('permissions'));
    }

    /**
     * @param  CreateRoleRequest  $request
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function store(CreateRoleRequest $request)
    {
        $input = $request->all();
        $this->roleRepository->storeRole($input);
        Flash::success('Role saved successfully.');

        return redirect()->route('roles.index');
    }

    /**
     * @param  Role  $role
     * @return Application|Factory|View
     */
    public function show(Role $role)
    {
        return redirect()->back();
//        return \view('roles.show',compact('role'));
    }

    /**
     * @param  Role  $role
     * @return Application|Factory|View
     */
    public function edit(Role $role)
    {
        if ($role->is_default) {
            return redirect()->back();
        }
        $permissions = Permission::toBase();

        return view('roles.edit', compact('permissions', 'role'));
    }

    /**
     * @param  Role  $role
     * @param  UpdateRoleRequest  $request
     * @return RedirectResponse
     *
     * @throws Exception
     */
    public function update(Role $role, UpdateRoleRequest $request)
    {
        if ($role->is_default) {
            Flash::error('You can not update default role.');

            return  redirect()->back();
        }
        $this->roleRepository->updateRole($request->all(), $role);
        Flash::success('Role updated successfully.');

        return redirect()->route('roles.index');
    }

    /**
     * @param  Role  $role
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy(Role $role)
    {
        if ($role->is_default) {
            return $this->sendError('You can not delete default role.');
        }
        if ($role->users->count() > 0) {
            return $this->sendError('This role is already assigned.');
        }
        $this->roleRepository->delete($role->id);

        return $this->sendSuccess('Role deleted successfully');
    }
}
