<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\AppBaseController;
use App\Models\SocialAccount;
use App\Providers\SocialAuthProviders\GoogleAuthProvider;
use App\Repositories\SocialAuthRepository;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Socialite;

/**
 * Class SocialAuthController
 */
class SocialAuthController extends AppBaseController
{
    /** @var SocialAuthRepository */
    private $socialAuthRepo;

    public function __construct(SocialAuthRepository $socialAuthRepo)
    {
        $this->socialAuthRepo = $socialAuthRepo;
    }

    /**
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($provider)
    {
        /** @var GoogleAuthProvider $driver */
        return Socialite::driver($provider)->redirect();
    }

    /**
     * @param  string  $provider
     * @param  Request  $request
     * @return RedirectResponse|Redirector
     */
    public function callback($provider, Request $request)
    {
        $socialUser = null;
        switch ($provider) {

            case SocialAccount::GOOGLE_PROVIDER:
                $accessToken = $request->get('code');
                $socialUser = $this->socialAuthRepo->getGoogleUserByToken($accessToken);
                break;

            case SocialAccount::FACEBOOK_PROVIDER:
                $accessToken = $request->get('code');
                $socialUser = $this->socialAuthRepo->getFacebookUserByToken($accessToken);
                break;
        }

        $user = $this->socialAuthRepo->handleSocialUser($provider, $socialUser);

        Auth::loginUsingId($user->id, true);

        return redirect('/conversations');
    }
}
