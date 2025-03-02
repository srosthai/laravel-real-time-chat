<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Class LoginController
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers
    {
        logout as customLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/conversations';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @param  Request  $request
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request)
    {
        $user = User::find(\Auth::id());
        $user->update(['is_online' => 0, 'last_seen' => Carbon::now()]);
        $this->customLogout($request);

        if (\Auth::user()) {
            \Auth::logout();
        }

        return redirect('/login');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if (\Auth::check() && \Auth::user()->hasPermissionTo('manage_conversations')) {
            $this->redirectTo = '/conversations';
        } elseif (\Auth::check()) {
            if (\Auth::user()->getAllPermissions()->count() > 0) {
                $url = getPermissionWiseRedirectTo(\Auth::user()->getAllPermissions()->first());
                $this->redirectTo = $url;
            } else {
                $this->redirectTo = '/conversations';
            }
        }

        if (! isset($request->remember)) {
            return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath())
                    ->withCookie(\Cookie::forget('email'))
                    ->withCookie(\Cookie::forget('password'))
                    ->withCookie(\Cookie::forget('remember'));
        }

        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPath())
                ->withCookie(\Cookie::make('email', $request->email, '3600'))
                ->withCookie(\Cookie::make('password', $request->password, '3600'))
                ->withCookie(\Cookie::make('remember', '1', '3600'));
    }
}
