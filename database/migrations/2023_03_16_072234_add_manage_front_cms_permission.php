<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use App\Models\Role;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      $permission =  Permission::create([
            'name'         => 'manage_front_cms',
            'display_name' => 'Manage Front CMS',
            'guard_name'   => 'web',
        ]);
        $role = Role::where('name', 'Admin')->first();
        $role->givePermissionTo($permission);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $role = Role::where('name', 'Admin')->first();
        $role->revokePermissionTo('manage_front_cms');
        Permission::where('name', 'manage_front_cms')->delete();
    }
};
