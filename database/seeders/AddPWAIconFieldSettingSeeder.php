<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AddPWAIconFieldSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pwaIcon = ('assets/images/logo-30x30.png');
        Setting::create(['key' => 'pwa_icon', 'value' => $pwaIcon]);
    }
}
