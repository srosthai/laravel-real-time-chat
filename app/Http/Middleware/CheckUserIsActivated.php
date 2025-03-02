<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use InfyOm\Generator\Utils\ResponseUtil;
use Session;

class CheckUserIsActivated
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->is_active || ! $user->email_verified_at) {
            $errorMessage = (! $user->email_verified_at) ? 'You have registered successfully, please verified your email to login' : 'Your account has been deactivated, Please contact to admin for account activation.';

            \Auth::logout();

            $user->update(['is_online' => 0, 'last_seen' => Carbon::now()]);
            Session::flash('error', $errorMessage);

            if ($request->ajax()) {
                $userTokens = $user->tokens;
                foreach ($userTokens as $token) {
                    /** var Laravel\Passport\Token $token */
                    $token->revoke();
                }

                return JsonResponse::fromJsonString(ResponseUtil::makeError($errorMessage),
                    Response::HTTP_UNAUTHORIZED);
            }

            return redirect('login');
        }

        return $next($request);
    }
}
