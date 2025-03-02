<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Crypt;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;
use Session;

/**
 * Class AuthController.
 */
class AuthController extends AppBaseController
{
    /**
     * @param  Request  $request
     * @return RedirectResponse|Redirector|View
     */
    public function verifyAccount(Request $request)
    {
        $token = $request->get('token', null);

        if (empty($token)) {
            Session::flash('error', 'token not found');

            return redirect('login');
        }

        try {
            $token = Crypt::decrypt($token);
            [$userId, $activationCode] = $result = explode('|', $token);

            if (count($result) < 2) {
                Session::flash('error', 'token not found');

                return redirect('login');
            }

            /** @var User $user */
            $user = User::whereActivationCode($activationCode)->findOrFail($userId);

            if (empty($user)) {
                Session::flash('msg', 'This account activation token is invalid');

                return redirect('login');
            }
            if ($user->is_active) {
                Session::flash('success', 'Your account already activated. Please do a login');

                return redirect('login');
            }

            $user->is_active = true;
            $user->email_verified_at = Carbon::now();
            $user->save();

            Session::flash('success', 'Your account is successfully activated. Please do a login');

            return redirect('login');
        } catch (Exception $e) {
            Session::flash('msg', 'Something went wrong');

            return redirect('login');
        }
    }
}
