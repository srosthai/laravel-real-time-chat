<?php

use Illuminate\Support\Facades\Route;

Route::get('upgrade-to-v3-4-0', function () {
    try {
        Artisan::call('migrate', [
            '--path' => '/database/migrations/2020_10_19_133700_move_all_existing_devices_to_new_table.php',
            '--force' => true,
        ]);

        return 'You are successfully migrated to v3.4.0';
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
});

Route::get('/upgrade-to-v4-3-0', function () {
    try {
        Artisan::call('db:seed', ['--class' => 'CreatePermissionSeeder', '--force' => true]);

        return 'You are successfully seeded to v4.3.0';
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
});

Route::get('upgrade-to-v5-0-0', function () {
    try {
        Artisan::call('migrate', [
            '--path' => '/database/migrations/2021_07_12_000000_add_uuid_to_failed_jobs_table.php',
            '--force' => true,
        ]);

        return 'You are successfully migrated to v5.0.0';
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
});

Route::get('upgrade-to-v6-0-0', function () {
    try {
        Artisan::call('migrate', [
            '--path' => '/database/migrations/2022_03_02_120506_change_duration_field_type_in_zoom_meetings_table.php',
            '--force' => true,
        ]);

        Artisan::call('migrate', [
            '--path' => '/database/migrations/2022_03_04_085620_add_is_super_admin_field_in_users_table.php',
            '--force' => true,
        ]);

        Artisan::call('db:seed', ['--class' => 'SetIsDefaultSuperAdminSeeder', '--force' => true]);

        return 'You are successfully migrated and seeded to v6.0.0';
    } catch (Exception $exception) {
        return $exception->getMessage();
    }
});

Route::get('upgrade/database', function () {
    if (config('app.upgrade_mode')) {
        Artisan::call('migrate', ['--force' => true]);
    }
});
