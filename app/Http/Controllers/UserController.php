<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Role;
use App\Models\User;
use App\Queries\UserDataTable;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use DataTables;
use Exception;
use Hash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Response;

class UserController extends AppBaseController
{
    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @return Factory|View
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Display a listing of the User.
     *
     * @param  Request  $request
     * @return Application|Factory|\Illuminate\Contracts\View\View|Response
     *
     * @throws Exception
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return Datatables::of((new UserDataTable())->get($request->only(['filter_user', 'privacy_filter'])))->make(true);
        }
        $roles = Role::pluck('name', 'id')->toArray();

        return view('users.index')->with([
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $roles = Role::all()->pluck('name', 'id')->toArray();

        return view('users.create')->with(['roles' => $roles]);
    }

    /**
     * Store a newly created User in storage.
     *
     * @param  CreateUserRequest  $request
     * @return JsonResponse
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $input = $this->validateInput($request->all());

        $this->userRepository->store($input);

        return $this->sendSuccess('User saved successfully.');
    }

    /**
     * Display the specified User.
     *
     * @param  User  $user
     * @return Application|Factory|\Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $user->roles;
        $user = $user->apiObj();

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  User  $user
     * @return JsonResponse
     */
    public function edit(User $user): JsonResponse
    {
        $user->roles;
        $user = $user->apiObj();

        return $this->sendResponse(['user' => $user], 'User retrieved successfully.');
    }

    /**
     * Update the specified User in storage.
     *
     * @param  User  $user
     * @param  UpdateUserRequest  $request
     * @return JsonResponse
     */
    public function update(User $user, UpdateUserRequest $request): JsonResponse
    {
        if (! empty($user->is_system)) {
            return $this->sendError('You can not update system generated user.');
        }

        $input = $this->validateInput($request->all());
        $this->userRepository->update($input, $user->id);

        return $this->sendSuccess('User updated successfully.');
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function updateLanguage(Request $request): JsonResponse
    {
        $language = $request->get('languageName');

        $user = getLoggedInUser();
        $user->update(['language' => $language]);

        return $this->sendSuccess('Language updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  User  $user
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function archiveUser(User $user): JsonResponse
    {
        if (! empty($user->is_system)) {
            return $this->sendError('You can not archive system generated user.');
        }

        $this->userRepository->delete($user->id);

        return $this->sendSuccess('User archived successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function restoreUser(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $this->userRepository->restore($id);

        return $this->sendSuccess('User restored successfully.');
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function destroy($id): JsonResponse
    {
        $user = User::withTrashed()->whereId($id)->first();

        if (empty($user)) {
            return $this->sendError('User not found.');
        }

        if (! empty($user->is_system)) {
            return $this->sendError('You can not delete system generated user.');
        }

        $this->userRepository->deleteUser($user->id);

        return $this->sendSuccess('User deleted successfully.');
    }

    /**
     * @param  User  $user
     * @return JsonResponse
     */
    public function activeDeActiveUser(User $user): JsonResponse
    {
        $this->userRepository->checkUserItSelf($user->id);
        $this->userRepository->activeDeActiveUser($user->id);

        return $this->sendSuccess('User status updated successfully.');
    }

    /**
     * @param $input
     * @return mixed
     */
    public function validateInput($input)
    {
        if (isset($input['password']) && ! empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $input['is_active'] = (! empty($input['is_active'])) ? 1 : 0;

        return $input;
    }

    /**
     * @param  User  $user
     * @return Application|RedirectResponse|Redirector
     */
    public function userImpersonateLogin(User $user)
    {
        Auth::user()->impersonate($user);

        if (\Auth::check() && \Auth::user()->hasPermissionTo('manage_conversations')) {
            return redirect(url('/conversations'));
        } elseif (\Auth::check()) {
            if (\Auth::user()->getAllPermissions()->count() > 0) {
                $url = getPermissionWiseRedirectTo(\Auth::user()->getAllPermissions()->first());

                return redirect(url($url));
            } else {
                return redirect(url('/conversations'));
            }
        }
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function userImpersonateLogout()
    {
        Auth::user()->leaveImpersonation();

        return redirect(url('/conversations'));
    }

    /**
     * @param  User  $user
     * @return JsonResponse
     */
    public function isEmailVerified(User $user): JsonResponse
    {
        $emailVerified = $user->email_verified_at == null ? Carbon::now() : null;
        $user->update(['email_verified_at' => $emailVerified]);

        return $this->sendSuccess('Email Verified successfully.');
    }
}
