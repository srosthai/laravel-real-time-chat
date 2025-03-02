<?php

namespace App\Providers\SocialAuthProviders;

/**
 * Class FacebookAuthProvider
 */
class FacebookAuthProvider extends \Laravel\Socialite\Two\FacebookProvider
{
    /**
     * {@inheritdoc}
     */
    public function getUserByToken($token)
    {
        return parent::getUserByToken($token);
    }

    /**
     * {@inheritdoc}
     */
    public function mapUserToObject(array $user)
    {
        return parent::mapUserToObject($user);
    }
}
