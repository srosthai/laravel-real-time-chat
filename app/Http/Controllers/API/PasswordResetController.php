<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Mail\MarkdownMail;
use App\Models\PasswordReset;
use App\Models\User;
use App\Rules\NoSpaceContaine;
use Crypt;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mail;

class PasswordResetController extends AppBaseController
{
    /**
     * Create token password reset
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse [string] message
     */
    public function sendResetPasswordLink(Request $request)
    {
        $url = $request->url;
        $request->validate([
            'email' => 'required|string|email',
        ]);
        $user = User::where('email', $request->get('email'))->first();

        if (! $user) {
            return $this->sendError("We can't find a user with that e-mail address.", Response::HTTP_NOT_FOUND);
        }

        $key = $user->email.'|'.date('Y-m-d H:i:s');
        $token = Crypt::encrypt($key);

        $passwordReset = PasswordReset::where('email', '=', $user->email)->first();
        if (empty($passwordReset)) {
            $passwordReset = PasswordReset::create(
                [
                    'email' => $user->email,
                    'token' => $token,
                ]
            );
        } else {
            $passwordReset->token = $token;
            $passwordReset->save();
        }

        if ($user && $passwordReset) {
            $encodedToken = urlencode($token);
            $resetPwdUrl = $url.'?token='.$encodedToken;

            $data['email'] = $user->email;
            $data['token'] = $passwordReset->token;
            $data['name'] = $user->name;
            $data['url'] = $resetPwdUrl;

            try {
                Mail::to($user->email)
                    ->send(new MarkdownMail('auth.emails.password_reset', 'Activate your account', $data));
            } catch (Exception $e) {
                throw new Exception('Account created, but unable to send email');
            }
        }

        return $this->sendSuccess('We have e-mailed your password reset link!');
    }

    /**
     * Reset password
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse [string] message
     *
     * @throws \Exception
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => ['required', 'string', 'confirmed', 'min:8', new NoSpaceContaine()],
            'token' => 'required|string',
        ]);

        $input = $request->all();
        $encodedToken = urldecode($input['token']);
        $token = Crypt::decrypt($encodedToken);
        [$email, $registerTime] = explode('|', $token);

        $passwordReset = PasswordReset::where([
            ['token', $encodedToken],
            ['email', $request->email],
        ])->first();

        if (! $passwordReset) {
            return $this->sendError('This password reset token is invalid.', Response::HTTP_NOT_FOUND);
        }

        $user = User::whereEmail($email)->first();
        if (! $user) {
            return $this->sendError('User with given email not available.', Response::HTTP_NOT_FOUND);
        }

        //check activated link has expired in 1 hour
        if ((strtotime(date('Y-m-d H:i:s')) - strtotime($registerTime)) / (60 * 60) > 1) {
            return $this->sendError('The password reset token has expired.');
        }

        $user->update(['password' => Hash::make($input['password'])]);
        $passwordReset->delete();

        return $this->sendSuccess('Password has been reset successfully.');
    }
}
