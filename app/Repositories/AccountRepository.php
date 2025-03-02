<?php

namespace App\Repositories;

use App\Mail\MarkdownMail;
use App\Models\User;
use Crypt;
use Exception;
use Mail;
use URL;

/**
 * Class AccountRepository.
 */
class AccountRepository
{
    /**
     * @param $userId
     * @return string
     */
    public function generateUserActivationToken($userId)
    {
        $activation_code = uniqid();

        /** @var User $user */
        $user = User::find($userId);
        $user->activation_code = $activation_code;
        $user->save();

        $key = $user->id.'|'.$activation_code;
        $code = Crypt::encrypt($key);

        return $code;
    }

    /**
     * @param  string  $username
     * @param  string  $email
     * @param  string  $activateCode
     * @param  string  $url
     *
     * @throws Exception
     */
    public function sendConfirmEmail($username, $email, $activateCode, $url = '')
    {
        $data['link'] = ($url != '') ? $url.'/activate?token='.$activateCode : URL::to('/activate?token='.$activateCode);
        $data['username'] = $username;

        try {
            Mail::to($email)
                ->send(new MarkdownMail('auth.emails.account_verification', 'Activate your account', $data));
        } catch (Exception $e) {
            throw new Exception('Account created, but unable to send email');
        }
    }
}
