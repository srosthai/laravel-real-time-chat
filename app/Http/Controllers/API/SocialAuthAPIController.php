<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\SocialAccount;
use App\Repositories\SocialAuthRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class SocialAuthAPIController
 */
class SocialAuthAPIController extends AppBaseController
{
    /**
     * @var SocialAuthRepository
     */
    private $socialAuthRepository;

    public function __construct(SocialAuthRepository $socialAuthRepository)
    {
        $this->socialAuthRepository = $socialAuthRepository;
    }

    /**
     * @param  string  $provider
     * @param  Request  $request
     * @return JsonResponse
     */
    public function socialLogin($provider, Request $request)
    {
        $provider = strtolower($provider);
        if (! in_array($provider, SocialAccount::SOCIAL_PROVIDERS)) {
            throw new UnprocessableEntityHttpException('Invalid provider');
        }

        switch ($provider) {
            case SocialAccount::FACEBOOK_PROVIDER:
                $accessToken = $request->get('code');
                $socialUser = $this->socialAuthRepository->getFacebookUserByToken($accessToken);
                break;

            case SocialAccount::GOOGLE_PROVIDER:
                $accessToken = $request->get('code');
                $socialUser = $this->socialAuthRepository->getGoogleUserByToken($accessToken);
                break;

            default:
                throw new UnprocessableEntityHttpException('invalid provider');
        }

        $user = $this->socialAuthRepository->handleSocialUser($provider, $socialUser);

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $token->save();

        $user->update(['is_online' => 1, 'last_seen' => null]);

        return $this->sendResponse(['token' => $tokenResult->accessToken, 'user' => $user], 'Logged in successfully.');
    }
}
