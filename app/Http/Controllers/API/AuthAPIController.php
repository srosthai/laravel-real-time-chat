<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Repositories\AccountRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Crypt;
use Exception;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InfyOm\Generator\Utils\ResponseUtil;

class AuthAPIController extends AppBaseController
{
    /** @var AccountRepository */
    public $accountRepo;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(AccountRepository $accountRepository, UserRepository $userRepo)
    {
        $this->accountRepo = $accountRepository;
        $this->userRepository = $userRepo;
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        if (empty($email) or empty($password)) {
            return $this->sendError('username and password required', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        /** @var User $user */
        $user = User::whereRaw('lower(email) = ?', [$email])->first();
        if (empty($user)) {
            return $this->sendError('Invalid username or password', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! Hash::check($password, $user->password)) {
            return $this->sendError('Invalid username or password', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (! $user->is_active) {
            return JsonResponse::fromJsonString(ResponseUtil::makeError('Your account is deactivated. Please verify your email for account activation.'), Response::HTTP_UNAUTHORIZED);
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        $user->update(['is_online' => 1, 'last_seen' => null]);

        return $this->sendResponse(['token' => $tokenResult->accessToken, 'user' => $user], 'Logged in successfully.');
    }

    /**
     * @param  RegisterRequest  $request
     * @return JsonResponse
     * @throws Exception
     */
    public function register(RegisterRequest $request)
    {
        $input = $request->all();

        /** @var User $user */
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $apiUrl = url('/api');

        $this->userRepository->assignRoles($user, ['role_id' => Role::MEMBER_ROLE]);
        $activateCode = $this->accountRepo->generateUserActivationToken($user->id);
        $this->accountRepo->sendConfirmEmail($user->name, $user->email, $activateCode, $apiUrl);

        return $this->sendSuccess('You have registered successfully, please verified your email to login');
    }

    /**
     * @return JsonResponse
     */
    public function logout()
    {
        $authUser = getLoggedInUser();
        $userTokens = $authUser->tokens;

        foreach ($userTokens as $token) {
            /** var Laravel\Passport\Token $token */
            $token->revoke();
        }

        $authUser->update(['is_online' => 0, 'last_seen' => Carbon::now()]);

        return $this->sendSuccess('Logged out successfully.');
    }

    public function verifyAccount(Request $request)
    {
        $token = $request->get('token', null);
        $baseUrl = url('/');

        if (empty($token)) {
            return redirect('/login?success=0&msg=token not found.');
        }

        try {
            $token = Crypt::decrypt($token);
            [$userId, $activationCode] = $result = explode('|', $token);
            $loginUrl = $baseUrl.'/login';

            if (count($result) < 2) {
                return redirect($loginUrl.'?success=0&msg=token not found.');
            }

            /** @var User $user */
            $user = User::whereActivationCode($activationCode)->findOrFail($userId);

            if (empty($user)) {
                return redirect($loginUrl.'?success=0&msg=This account activation token is invalid.');
            }
            if ($user->is_active) {
                return redirect($loginUrl.'?success=0&msg=Your account already activated. Please do a login.');
            }

            $user->is_active = 1;
            $user->save();

            return redirect($loginUrl.'?success=1&msg=Your account is successfully activated. Please do a login.');
        } catch (Exception $e) {
            return redirect('/login?success=0&msg=Something went wrong.');
        }
    }
}
