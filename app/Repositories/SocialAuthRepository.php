<?php

namespace App\Repositories;

use App;
use App\Models\Role;
use App\Models\SocialAccount;
use App\Models\User;
use App\Providers\SocialAuthProviders\FacebookAuthProvider;
use Carbon\Carbon;
use Exception;
use Socialite;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SocialAuthRepository
 */
class SocialAuthRepository
{
    /** @var UserRepository */
    private $userRepo;

    public function __construct()
    {
        $this->userRepo = App::make(UserRepository::class);
    }

    /**
     * @param  string  $provider
     * @param  array  $socialUser
     * @return User
     */
    public function handleSocialUser($provider, $socialUser)
    {
        $userData = [];

        try {
            switch (strtolower($provider)) {
                case SocialAccount::GOOGLE_PROVIDER:
                    $userData = $this->prepareGoogleUserData($socialUser);
                    break;

                case SocialAccount::FACEBOOK_PROVIDER:
                    $userData = $this->prepareFacebookUserData($socialUser);
                    break;
            }

            $user = User::whereRaw('lower(email) = ?', strtolower($userData['email']))->first();

            $existingProfile = null;
            if (! empty($user)) {
                /** @var SocialAccount $existingProfile */
                $existingProfile = SocialAccount::whereProvider($provider)->whereUserId($user->id)->first();
            }

            if (empty($user)) {
                $userData['is_active'] = true;
                $userData['email_verified_at'] = Carbon::now();
                $userData['password'] = \Hash::make(uniqid());

                $memberRole = Role::whereName(Role::MEMBER_ROLE_NAME)->first();
                $userData['role_id'] = [$memberRole->id];
                /** @var User $user */
                $user = $this->userRepo->store($userData);
            }

            if (empty($existingProfile)) {
                $socialAccount = new SocialAccount();
                $socialAccount->user_id = $user->id;
                $socialAccount->provider = $provider;
                $socialAccount->provider_id = $userData['provider_id'];
                $socialAccount->save();
            }

            return $user;
        } catch (Exception $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
    }

    /**
     * @param  string  $token
     * @return array|mixed
     */
    public function getGoogleUserByToken($token)
    {
        /** @var App\Providers\SocialAuthProviders\GoogleAuthProvider $driver */
        $driver = Socialite::driver('google');
        $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
        $token = $driver->getAccessTokenResponse($token);
        $user = $driver->getUserByToken($token['access_token']);

        return $user;
    }

    /**
     * @param  array  $user
     * @return array
     */
    public function prepareGoogleUserData($user)
    {
        return [
            'name' => $user['name'],
            'email' => $user['email'],
            'provider_id' => $user['sub'],
            // extra details into $user picture (photo) ,email_verified (is email verified),name (full name)
        ];
    }

    /**
     * @param  string  $token
     * @return array|mixed
     */
    public function getFacebookUserByToken($token)
    {
        /** @var FacebookAuthProvider $driver */
        $driver = Socialite::driver('facebook');
        $driver = $driver->fields(SocialAccount::facebookFields());

        $token = $driver->getAccessTokenResponse($token);

        $user = $driver->getUserByToken($token['access_token']);

        return $user;
    }

    /**
     * @param $user
     * @return array
     */
    public function prepareFacebookUserData($user)
    {
        return [
            'name' => $user['name'],
            'email' => $user['email'],
            'provider_id' => $user['id'],
        ];
    }
}
