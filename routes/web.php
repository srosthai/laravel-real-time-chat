<?php

use App\Http\Controllers\API;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ReportUserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FrontCMSController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.register');
});

Auth::routes();
Route::get('activate', [AuthController::class, 'verifyAccount']);

Route::get('/home', [HomeController::class, 'index']);
Route::post('update-language', [UserController::class, 'updateLanguage'])->middleware('auth')->name('update-language');

// Impersonate Logout
Route::get('/users/impersonate-logout',
    [UserController::class, 'userImpersonateLogout'])->name('impersonate.userLogout');
// 'user.activated',
Route::middleware([ 'auth'])->group(function () {
    //view routes
    Route::get('/conversations',
        [ChatController::class, 'index'])->name('conversations')->middleware('permission:manage_conversations');
    Route::get('profile', [UserController::class, 'getProfile']);
    Route::get('logout', [LoginController::class, 'logout']);

    //get all user list for chat
    Route::get('users-list', [API\UserAPIController::class, 'getUsersList']);
    Route::get('get-users', [API\UserAPIController::class, 'getUsers'])->name('get-users')->name('get-users');
    Route::delete('remove-profile-image',
        [API\UserAPIController::class, 'removeProfileImage'])->name('remove-profile-image');
    /** Change password */
    Route::post('change-password', [API\UserAPIController::class, 'changePassword'])->name('change-password');
    Route::get('conversations/{ownerId}/archive-chat', [API\UserAPIController::class, 'archiveChat'])->name('conversations.archive-chat');
    Route::get('conversations/{ownerId}/un-archive-chat', [API\UserAPIController::class, 'unArchiveChat'])->name('conversations.un-archive-chat');

    Route::get('get-profile', [API\UserAPIController::class, 'getProfile']);
    Route::post('profile', [API\UserAPIController::class, 'updateProfile'])->name('update.profile');
    Route::post('update-last-seen', [API\UserAPIController::class, 'updateLastSeen'])->name('update-last-seen');

    Route::post('send-message',
        [API\ChatAPIController::class, 'sendMessage'])->name('conversations.store')->middleware('sendMessage');
    Route::get('users/{id}/conversation', [API\UserAPIController::class, 'getConversation'])->name('users.conversation');
    Route::get('conversations-list', [API\ChatAPIController::class, 'getLatestConversations'])->name('conversations-list');
    Route::get('archive-conversations', [API\ChatAPIController::class, 'getArchiveConversations'])->name('archive-conversations');
    Route::post('read-message', [API\ChatAPIController::class, 'updateConversationStatus'])->name('read-message');
    Route::post('file-upload', [API\ChatAPIController::class, 'addAttachment'])->name('file-upload');
    Route::post('image-upload', [API\ChatAPIController::class, 'imageUpload'])->name('image-upload');
    Route::get('conversations/{userId}/delete', [API\ChatAPIController::class, 'deleteConversation'])->name('conversations.destroy');
    Route::post('conversations/message/{conversation}/delete', [API\ChatAPIController::class, 'deleteMessage'])->name('conversations.message-conversation.delete');
    Route::post('conversations/{conversation}/delete', [API\ChatAPIController::class, 'deleteMessageForEveryone']);
    Route::get('/conversations/{conversation}', [API\ChatAPIController::class, 'show']);
    Route::post('send-chat-request', [API\ChatAPIController::class, 'sendChatRequest'])->name('send-chat-request');
    Route::post('accept-chat-request',
        [API\ChatAPIController::class, 'acceptChatRequest'])->name('accept-chat-request');
    Route::post('decline-chat-request',
        [API\ChatAPIController::class, 'declineChatRequest'])->name('decline-chat-request');

    /** Web Notifications */
    Route::put('update-web-notifications', [API\UserAPIController::class, 'updateNotification'])->name('update-web-notifications');

    /** BLock-Unblock User */
    Route::put('users/{user}/block-unblock', [API\BlockUserAPIController::class, 'blockUnblockUser'])->name('users.block-unblock');
    Route::get('blocked-users', [API\BlockUserAPIController::class, 'blockedUsers']);

    /** My Contacts */
    Route::get('my-contacts', [API\UserAPIController::class, 'myContacts'])->name('my-contacts');

    /** Groups API */
    Route::post('groups', [API\GroupAPIController::class, 'create'])->name('groups.create');
    Route::post('groups/{group}', [API\GroupAPIController::class, 'update'])->name('groups.update');
    Route::get('groups', [API\GroupAPIController::class, 'index'])->name('groups.index');
    Route::get('groups/{group}', [API\GroupAPIController::class, 'show'])->name('group.show');
    Route::put('groups/{group}/add-members', [API\GroupAPIController::class, 'addMembers'])->name('groups-group.add-members');
    Route::delete('groups/{group}/members/{user}', [API\GroupAPIController::class, 'removeMemberFromGroup'])->name('group-from-member-remove');
    Route::delete('groups/{group}/leave', [API\GroupAPIController::class, 'leaveGroup'])->name('groups.leave');
    Route::delete('groups/{group}/remove', [API\GroupAPIController::class, 'removeGroup'])->name('group-remove');
    Route::put('groups/{group}/members/{user}/make-admin', [API\GroupAPIController::class, 'makeAdmin'])->name('groups.members.make-admin');
    Route::put('groups/{group}/members/{user}/dismiss-as-admin', [API\GroupAPIController::class, 'dismissAsAdmin'])->name('groups.members.dismiss-as-admin');
    Route::get('users-blocked-by-me', [API\BlockUserAPIController::class, 'blockUsersByMe']);

    Route::get('notification/{notification}/read', [API\NotificationController::class, 'readNotification'])->name('notification.read-notification');
    Route::get('notification/read-all', [API\NotificationController::class, 'readAllNotification'])->name('read-all-notification');

    Route::put('update-player-id', [API\UserAPIController::class, 'updatePlayerId'])->name('update-player-id');
    //set user custom status route
    Route::post('set-user-status', [API\UserAPIController::class, 'setUserCustomStatus'])->name('set-user-status');
    Route::get('clear-user-status', [API\UserAPIController::class, 'clearUserCustomStatus'])->name('clear-user-status');

    //report user
    Route::post('report-user', [API\ReportUserController::class, 'store'])->name('report-user.store');
});

// users
Route::middleware(['permission:manage_users', 'auth', 'user.activated'])->group(function () {
    Route::resource('users', UserController::class);
    Route::post('users/{user}/active-de-active', [UserController::class, 'activeDeActiveUser'])
        ->name('active-de-active-user');
    Route::post('users/{user}/update', [UserController::class, 'update'])->name('user.update');
    Route::delete('users/{user}/archive', [UserController::class, 'archiveUser'])->name('archive-user');
    Route::post('users/restore', [UserController::class, 'restoreUser'])->name('user.restore-user');
    Route::get('users/{user}/login', [UserController::class, 'userImpersonateLogin'])->name('user-impersonate-login');
    Route::post('users/{user}/email-verified', [UserController::class, 'isEmailVerified'])->name('user.email-verified');
});

// roles
Route::middleware(['permission:manage_roles', 'auth', 'user.activated'])->group(function () {
    Route::resource('roles', RoleController::class)->except('update');
    Route::post('roles/{role}/update', [RoleController::class, 'update'])->name('roles.update');
});

// settings
Route::middleware(['permission:manage_settings', 'auth', 'user.activated'])->group(function () {
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');
});

// reported-users
Route::middleware(['permission:manage_reported_users', 'auth', 'user.activated'])->group(function () {
    Route::resource('reported-users', ReportUserController::class);
});

// meetings
Route::middleware(['permission:manage_meetings', 'auth', 'user.activated'])->group(function () {
    Route::resource('meetings', MeetingController::class);
    Route::get('meetings/{meeting}/change-status/{status}',
        [MeetingController::class, 'changeMeetingStatus'])->name('meeting.change-meeting-status');
    Route::get('member/meetings', [MeetingController::class, 'showMemberMeetings'])->name('meetings.member_index');
});

Route::middleware('web')->group(function () {
    Route::get('login/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('login/{provider}/callback', [SocialAuthController::class, 'callback']);
});

Route::middleware(['permission:manage_front_cms', 'auth', 'user.activated'])->group(function () {
    Route::get('front-cms', [FrontCMSController::class, 'frontCms'])->name('front.cms');
    Route::post('front-cms', [FrontCMSController::class, 'updateFrontCms'])->name('front.cms.update');
});

require __DIR__.'/upgrade.php';
