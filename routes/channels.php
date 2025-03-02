<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Models\Group;

Broadcast::channel('chat', function () {
    return Auth::check();
});

Broadcast::channel('user-status', function ($user) {
    return (Auth::check()) ? $user : false;
});

Broadcast::channel('updates', function ($user) {
    return (Auth::check()) ? $user : false;
});

Broadcast::channel('group.{group}', function ($user, Group $group) {
    $groupUser = $group->users()->where('user_id', '=', $user->id)->get()->toArray();

    return (count($groupUser) > 0) ? true : false;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
