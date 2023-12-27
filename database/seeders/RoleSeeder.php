<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $role_admin = Role::create(['name' => 'admin']);
        $role_standard = Role::create(['name' => 'User']);

        $permission_read = Permission::create(['name' => 'list-user']);
        $permission_edit = Permission::create(['name' => 'edit-user']);
        $permission_write = Permission::create(['name' => 'create-user']);
        $permission_delete = Permission::create(['name' => 'delete-user']);

        $permissions_admin = [$permission_read, $permission_edit, $permission_write, $permission_delete];

        $role_admin->syncPermissions($permissions_admin);
        $role_standard->givePermissionTo($permission_read);
    }
}
