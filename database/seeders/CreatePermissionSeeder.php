<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CreatePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'manage_users',
                'display_name' => 'Manage Users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_roles',
                'display_name' => 'Manage Roles',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_reported_users',
                'display_name' => 'Manage Reported Users',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_conversations',
                'display_name' => 'Manage Conversations',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_settings',
                'display_name' => 'Manage Settings',
                'guard_name' => 'web',
            ],
            [
                'name' => 'manage_meetings',
                'display_name' => 'Manage Meetings',
                'guard_name' => 'web',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $roles = Role::all();
        foreach ($roles as $role) {
            $role->update(['guard_name' => 'web']);
            if ($role->name == 'Admin') {
                $role->syncPermissions(Permission::pluck('name'));
            } elseif ($role->name == 'Member') {
                $role->syncPermissions(['manage_conversations', 'manage_meetings']);
            } else {
                $role->syncPermissions(['manage_conversations']);
            }
        }
    }
}
