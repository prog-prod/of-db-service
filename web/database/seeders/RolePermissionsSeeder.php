<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'edit admins', 'guard_name' => 'admin']);
        Permission::create(['name' => 'create OF tags', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete OF tags', 'guard_name' => 'admin']);
        Permission::create(['name' => 'edit OF tags', 'guard_name' => 'admin']);
        Permission::create(['name' => 'view OF tags', 'guard_name' => 'admin']);
        Permission::create(['name' => 'edit OF users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'view OF users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete OF users', 'guard_name' => 'admin']);
        Permission::create(['name' => 'create OF users', 'guard_name' => 'admin']);

        $roleSuperAdmin = Role::create(['name' => 'super-admin', 'guard_name' => 'admin']);
        $roleAdmin = Role::create(['name' => 'admin', 'guard_name' => 'admin']);
        $roleUser = Role::create(['name' => 'user', 'guard_name' => 'web']);
        $roleAdvertiser = Role::create(['name' => 'advertiser', 'guard_name' => 'advertiser']);
        $roleSuperAdmin->syncPermissions(Permission::where('guard_name','admin')->get()->pluck('id'));
        $roleAdmin->syncPermissions(Permission::query()->where('guard_name','admin')->whereNotIn('name',  [
            'edit admins',
        ])->get()->pluck('id'));

    }
}
