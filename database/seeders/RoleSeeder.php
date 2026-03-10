<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $roles = [
            ["name" => RoleEnum::ADMIN, 'guard_name' => "web"],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate($role);
        }

        $permissions = [
            // User Management
            ["name" => PermissionEnum::USERS_ADD, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],
            ["name" => PermissionEnum::USERS_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::USERS],

            // Settings
            ["name" => PermissionEnum::SETTING_ADD, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],
            ["name" => PermissionEnum::SETTING_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::SETTING],

            // Role Management
            ["name" => PermissionEnum::MANAGE_ROLES, 'guard_name' => "web", 'page' => ''],

            // Specialties Management (Admin)
            ["name" => PermissionEnum::MANAGE_SPECIALTIES, 'guard_name' => "web", 'page' => ''],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_ADD, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_VIEW, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_UPDATE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
            ["name" => PermissionEnum::MANAGE_SPECIALTIES_DELETE, 'guard_name' => "web", 'page' => PermissionEnum::MANAGE_SPECIALTIES],
       ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // Admin gets all permissions
        $allPermissionIds = Permission::all()->pluck('id')->toArray();
        $adminRole = Role::whereName(RoleEnum::ADMIN)->first();
        $adminRole->permissions()->detach();
        $adminRole->permissions()->attach($allPermissionIds);
    }
}
