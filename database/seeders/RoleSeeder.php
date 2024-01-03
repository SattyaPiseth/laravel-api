<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = Permission::defaultPermission();
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'api']);
        }
        $this->command->info('Default Permissions added.');

        $roles = Role::allRoles();

        foreach ($roles as $role) {
            $role = Role::firstOrCreate(['name' => trim($role), 'guard_name' => 'api']);
            switch ($role->name) {
                case Role::ADMIN:
                    $role->syncPermissions(Permission::all());
                    break;
                case Role::USER:
                    $role->syncPermissions(Permission::where(function ($query) {
                        $query->where('name', 'like', 'view_user')
                            ->orWhere('name', 'like', 'view_category')
                            ->orWhere('name', 'like', 'view_brand')
                            ->orWhere('name', 'like', '%product')
                            ->orWhere('name', 'like', '%address');
                    })->get());
            }
            $this->command->info('Adding users with teams...');
        }
        $this->command->warn('All done :');
    }
}
