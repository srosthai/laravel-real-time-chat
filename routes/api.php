<?php

use App\Http\Controllers\API\AdminUsersAPIController;
use App\Http\Controllers\API\AuthAPIController;
use App\Http\Controllers\API\ChatAPIController;
use App\Http\Controllers\API\GroupAPIController;
use App\Http\Controllers\API\PasswordResetController;
use App\Http\Controllers\API\RoleAPIController;
use App\Http\Controllers\API\SocialAuthAPIController;
use App\Http\Controllers\API\UserAPIController;
use Illuminate\Broadcasting\BroadcastController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// User Login API
Route::post('/login', [AuthAPIController::class, 'login']);
Route::post('/register', [AuthAPIController::class, 'register']);
Route::post('password/reset', [PasswordResetController::class, 'sendResetPasswordLink']);
Route::post('password/update', [PasswordResetController::class, 'reset']);
Route::get('activate', [AuthAPIController::class, 'verifyAccount']);

Route::middleware(['auth:api', 'user.activated'])->group(function () {
    Route::post('broadcasting/auth', [BroadcastController::class, 'authenticate']);
    Route::get('logout', [AuthAPIController::class, 'logout']);

    //get all user list for chat
    Route::get('users-list', [UserAPIController::class, 'getUsersList']);
    Route::post('change-password', [UserAPIController::class, 'changePassword']);

    Route::get('profile', [UserAPIController::class, 'getProfile'])->name('my-profile');
    Route::post('profile', [UserAPIController::class, 'updateProfile']);
    Route::post('update-last-seen', [UserAPIController::class, 'updateLastSeen']);

    Route::post('send-message', [ChatAPIController::class, 'sendMessage'])->name('conversations.store');
    Route::get('users/{id}/conversation', [UserAPIController::class, 'getConversation']);
    Route::get('conversations', [ChatAPIController::class, 'getLatestConversations']);
    Route::post('read-message', [ChatAPIController::class, 'updateConversationStatus']);
    Route::post('file-upload', [ChatAPIController::class, 'addAttachment'])->name('file-upload');
    Route::get('conversations/{userId}/delete', [ChatAPIController::class, 'deleteConversation']);

    /** Update Web-push */
    Route::put('update-web-notifications', [UserAPIController::class, 'updateNotification']);

    /** create group **/
    Route::post('groups', [GroupAPIController::class, 'create'])->name('create-group');
    /** Social Login */
    Route::post('social-login/{provider}', [SocialAuthAPIController::class, 'socialLogin'])->middleware('web');
});

Route::middleware(['role:Admin', 'auth:api', 'user.activated'])->group(function () {
    Route::resource('users', AdminUsersAPIController::class);
    Route::post('users/{user}/update', [AdminUsersAPIController::class, 'update']);
    Route::post('users/{user}/active-de-active', [AdminUsersAPIController::class, 'activeDeActiveUser'])
        ->name('active-de-active-user');

    Route::resource('roles', RoleAPIController::class);
    Route::post('roles/{role}/update', [RoleAPIController::class, 'update']);
});
