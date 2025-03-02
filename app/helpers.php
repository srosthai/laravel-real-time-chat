<?php

use App\Models\Conversation;
use App\Models\FrontCms;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\NotificationRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;

/**
 * @return int
 */
function getLoggedInUserId()
{
    return Auth::id();
}

/**
 * @return string[]
 */
function getUserLanguages()
{
    return User::LANGUAGES;
}

/**
 * @return HigherOrderBuilderProxy|mixed
 */
function getCurrentLanguageName()
{
    static $language;

    if (! empty($language)) {
        return $language;
    }

    $language = User::whereId(Auth::id())->first()->language;

    return $language;
}

/**
 * @return User
 */
function getLoggedInUser()
{
    return Auth::user();
}

function detectURL($url)
{
    if (strpos($url, 'youtube.com/watch?v=') > 0) {
        return Conversation::YOUTUBE_URL;
    }

    return 0;
}

function isValidURL($url)
{
    return filter_var($url, FILTER_VALIDATE_URL);
}

function getDefaultAvatar()
{
    return asset('assets/images/avatar.png');
}

/**
 * return random color.
 *
 * @param  int  $userId
 * @return string
 */
function getRandomColor($userId)
{
    $colors = ['329af0', 'fc6369', 'ffaa2e', '42c9af', '7d68f0'];
    $index = $userId % 5;

    return $colors[$index];
}

/**
 * return avatar url.
 *
 * @return string
 */
function getAvatarUrl()
{
    return 'https://ui-avatars.com/api/';
}

/**
 * return avatar full url.
 *
 * @param  int  $userId
 * @param  string  $name
 * @return string
 */
function getUserImageInitial($userId, $name)
{
    return getAvatarUrl()."?name=$name&size=100&rounded=true&color=fff&background=".getRandomColor($userId);
}

/**
 * @return array
 */
function getNotifications()
{
    /** @var NotificationRepository $notificationRepo */
    $notificationRepo = app(NotificationRepository::class);

    return $notificationRepo->getNotifications();
}

/**
 * @return mixed|string
 */
function getAppName()
{
    static $appNameSetting;

    if (! empty($appNameSetting)) {
        return $appNameSetting;
    }

    $record = Setting::where('key', '=', 'app_name')->first();
    $appNameSetting = (! empty($record)) ? $record->value : config('app.name');

    return $appNameSetting;
}

/**
 * @return mixed|string
 */
function getCompanyName()
{
    static $companyName;

    if (! empty($companyName)) {
        return $companyName;
    }

    $record = Setting::where('key', '=', 'company_name')->first();
    $companyName = (! empty($record)) ? $record->value : config('app.name');

    return config('app.name');
}

/**
 * @return string
 */
function getLogoUrl()
{
    static $logoURL;

    if (! empty($logoURL)) {
        return $logoURL;
    }

    $setting = Setting::where('key', '=', 'logo_url')->first();
    $logoURL = (! empty($setting) && ! empty($setting->value)) ? app(Setting::class)->getLogoUrl($setting->value) : asset('assets/images/logo.png');

    return $logoURL;
}

/**
 * @return string
 */
function getThumbLogoUrl()
{
    static $thumbLogo;

    if (! empty($thumbLogo)) {
        return $thumbLogo;
    }

    $setting = Setting::where('key', '=', 'logo_url')->first();
    $thumbLogo = (! empty($setting) && ! empty($setting->value)) ? app(Setting::class)->getLogoUrl($setting->value,
        Setting::THUMB_PATH) : asset('assets/images/logo-30x30.png');

    return $thumbLogo;
}

/**
 * @return string
 */
function getFaviconUrl()
{
    $setting = Setting::where('key', '=', 'favicon_url')->first();
    $favicon = (! empty($setting) && ! empty($setting->value)) ? $setting->value : asset('assets/images/favicon/favicon-16x16.ico');

    return url('/uploads').'/'.$favicon;
}

/**
 * @return int|mixed
 */
function isGroupChatEnabled()
{
    static $groupChatEnabled;

    if (isset($groupChatEnabled)) {
        return $groupChatEnabled;
    }

    $setting = Setting::where('key', '=', 'enable_group_chat')->first();
    $groupChatEnabled = ! empty($setting) ? $setting->value : true;

    return $groupChatEnabled;
}

/**
 * @return int|mixed
 */
function canMemberAddGroup()
{
    static $membersCanAddGroup;

    if (isset($membersCanAddGroup)) {
        return $membersCanAddGroup;
    }

    $setting = Setting::where('key', '=', 'members_can_add_group')->first();
    $membersCanAddGroup = ! empty($setting) ? $setting->value : true;

    return $membersCanAddGroup;
}

/**
 * @return bool
 */
function checkUserStatusForGroupMember($userStatus)
{
    return ($userStatus != null) ? true : false;
}

/**
 * @param  int  $gender
 * @return string
 */
function getGender($gender)
{
    if ($gender == 1) {
        return 'male';
    }
    if ($gender == 2) {
        return 'female';
    }

    return '';
}

/**
 * @param  int  $status
 * @return string
 */
function getOnOffClass($status)
{
    if ($status == 1) {
        return 'online';
    }

    return 'offline';
}

/**
 * @return array
 */
function getTimeZone(): array
{
    return DateTimeZone::listIdentifiers();
}

/**
 * @param $data
 * @return Application|UrlGenerator|string
 */
function getPermissionWiseRedirectTo($data)
{
    $redirect = '/conversations';

    if ($data->name == 'manage_users') {
        $redirect = url('/users');
    } elseif ($data->name == 'manage_roles') {
        $redirect = '/roles';
    } elseif ($data->name == 'manage_reported_users') {
        $redirect = '/reported-users';
    } elseif ($data->name == 'manage_meetings') {
        $redirect = '/meetings';
    } elseif ($data->name == 'manage_settings') {
        $redirect = '/settings';
    }

    return $redirect;
}

/**
 * @return string
 */
function getNotificationSetting()
{
    static $notification;

    if (! empty($notification)) {
        return $notification;
    }

    $setting = Setting::where('key', '=', 'notification_sound')->first();
    $notification = (! empty($setting) && ! empty($setting->value)) ? $setting->value : null;

    return $notification;
}

function getNotificationSound()
{
    static $notification_sound;

    if (! empty($notification_sound)) {
        return $notification_sound;
    }

    /** @var Setting $setting */
    $setting = Setting::where('key', 'notification_sound')->pluck('value', 'key')->toArray();

    if (! empty($setting['notification_sound'])) {
        $notification_sound = app(Setting::class)->getNotificationSound($setting['notification_sound']);
    }

    return $notification_sound;
}

/**
 * @return mixed
 */
function getCurrentVersion()
{
    $composerFile = file_get_contents('../composer.json');
    $composerData = json_decode($composerFile, true);
    $currentVersion = $composerData['version'];

    return $currentVersion;
}

/**
 * @return bool|HigherOrderBuilderProxy|mixed
 */
function checkShowNameChat()
{
    $setting = Setting::where('key', '=', 'show_name_chat')->first();

    $showNameOnChat = ! empty($setting) ? $setting->value : true;

    return $showNameOnChat;
}

/**
 * @return mixed
 */
function version()
{
    $composerFile = file_get_contents('../composer.json');
    $composerData = json_decode($composerFile, true);
    $currentVersion = $composerData['version'];

    return $currentVersion;
}

function getFrontCmsValue($key){
    return FrontCms::where('key', '=', $key)->first()->value;
}

function getFrontCmsImage($key): string
{
    $frontCms = FrontCms::all()->pluck('value', 'key');
    if (isset($frontCms[$key])) {
     return $frontCms[$key] = app(FrontCms::class)->getImageUrl($frontCms[$key]);
    }
    return '';
}
