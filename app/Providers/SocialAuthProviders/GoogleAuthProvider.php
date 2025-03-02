<?php

namespace App\Providers\SocialAuthProviders;

/**
 * Class GoogleAuthProvider
 */
class GoogleAuthProvider extends \Laravel\Socialite\Two\GoogleProvider
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
