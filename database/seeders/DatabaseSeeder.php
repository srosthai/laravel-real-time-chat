<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(CreatePermissionSeeder::class);
        $this->call(AddPWAIconFieldSettingSeeder::class);
        $this->call(SetIsDefaultSuperAdminSeeder::class);
        $this->call(AddDefaultSettingSeeder::class);
    }
}
