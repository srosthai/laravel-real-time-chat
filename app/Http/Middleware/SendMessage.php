<?php

namespace App\Http\Middleware;

use App\Models\BlockedUser;
use Auth;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class SendMessage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        if (isset($input['is_group']) && $input['is_group'] == 1) {
            return $next($request);
        }

        $isBlocked = BlockedUser::whereBlockedBy(Auth::id())->whereBlockedTo($input['to_id'])
            ->orWhere(function (Builder $query) use ($input) {
                $query->where('blocked_by', $input['to_id'])->where('blocked_to', Auth::id());
            })->get();

        if (! $isBlocked->isEmpty()) {
            throw new UnprocessableEntityHttpException('You are not allow to send this message');
        }

        return $next($request);
    }
}
