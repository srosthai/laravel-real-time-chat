<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use App\Rules\NoSpaceContaine;
use Exception;
use Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Redirect;
use Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /** @var AccountRepository */
    public $accountRepo;

    /** @var UserRepository */
    private $userRepository;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/conversations';

    /**
     * Create a new controller instance.
     *
     * @param  AccountRepository  $accountRepository
     * @param  UserRepository  $userRepo
     */
    public function __construct(AccountRepository $accountRepository, UserRepository $userRepo)
    {
        $this->accountRepo = $accountRepository;
        $this->userRepository = $userRepo;
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $data['name'] = htmlspecialchars($data['name']);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'regex:/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'],
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed', new NoSpaceContaine()],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     *
     * @throws Exception
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => htmlspecialchars($data['name']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            // 'email_verified_at' => now(),
        ]);

        $this->userRepository->assignRoles($user, ['role_id' => Role::MEMBER_ROLE]);
        $activateCode = $this->accountRepo->generateUserActivationToken($user->id);
        $this->accountRepo->sendConfirmEmail($user->name, $user->email, $activateCode);
        return $user;
    }

    /**
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }
}
