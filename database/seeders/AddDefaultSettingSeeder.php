<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AddDefaultSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $appName = Setting::where('key', 'app_name')->exists();

        if (! $appName) {
            Setting::create(['key' => 'app_name', 'value' => 'Chat']);
        }

        $companyName = Setting::where('key', 'company_name')->exists();

        if (! $companyName) {
            Setting::create(['key' => 'company_name', 'value' => 'InfyOm']);
        }

        $enableGroupChat = Setting::where('key', 'enable_group_chat')->exists();

        if (! $enableGroupChat) {
            Setting::create(['key' => 'enable_group_chat', 'value' => '1']);
        }

        $notificationSound = Setting::where('key', 'notification_sound')->exists();

        if (! $notificationSound) {
            Setting::create(['key' => 'notification_sound', 'value' => '']);
        }

        $showChatName = Setting::where('key', 'show_name_chat')->exists();

        if (! $showChatName) {
            Setting::create(['key' => 'show_name_chat', 'value' => '0']);
        }

        $appLogo = Setting::where('key', 'logo_url')->exists();

        if (! $appLogo) {
            Setting::create(['key' => 'logo_url', 'value' => '']);
        }

        $appFaviconLogo = Setting::where('key', 'favicon_url')->exists();

        if (! $appFaviconLogo) {
            Setting::create(['key' => 'favicon_url', 'value' => '']);
        }
    }
}
