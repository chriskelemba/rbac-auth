<?php

namespace RbacAuth\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use RbacAuth\Models\User;
use RbacAuth\Models\Role;
use RbacAuth\Models\Permission;

class BasicSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today()->toDateString();

        // --- Permissions with type and model ---
        $permissions = [
            ['name' => 'view.user',   'description' => 'Can view users',       'type' => 'policy', 'model' => User::class],
            ['name' => 'create.user', 'description' => 'Can create users',     'type' => 'policy', 'model' => User::class],
            ['name' => 'update.user', 'description' => 'Can update users',     'type' => 'policy', 'model' => User::class],
            ['name' => 'delete.user', 'description' => 'Can delete users',     'type' => 'policy', 'model' => User::class],
            ['name' => 'manage.user', 'description' => 'Can manage users',     'type' => 'policy', 'model' => User::class],

            ['name' => 'view.role',   'description' => 'Can view roles',       'type' => 'policy', 'model' => Role::class],
            ['name' => 'create.role', 'description' => 'Can create roles',     'type' => 'policy', 'model' => Role::class],
            ['name' => 'update.role', 'description' => 'Can update roles',     'type' => 'policy', 'model' => Role::class],
            ['name' => 'delete.role', 'description' => 'Can delete roles',     'type' => 'policy', 'model' => Role::class],

            ['name' => 'view.permission',   'description' => 'Can view permissions',  'type' => 'policy', 'model' => Permission::class],
            ['name' => 'create.permission', 'description' => 'Can create permissions','type' => 'policy', 'model' => Permission::class],
            ['name' => 'update.permission', 'description' => 'Can update permissions','type' => 'policy', 'model' => Permission::class],
            ['name' => 'delete.permission', 'description' => 'Can delete permissions','type' => 'policy', 'model' => Permission::class],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm['name']], $perm);
        }

        // --- Roles ---
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $admin      = Role::firstOrCreate(['name' => 'admin']);
        $userRole   = Role::firstOrCreate(['name' => 'user']);

        $allPermissions = Permission::pluck('id')->toArray();

        $superAdmin->permissions()->sync(
            collect($allPermissions)->mapWithKeys(fn($id) => [$id => ['added_on' => $today]])->toArray()
        );

        // --- Users ---
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password')]
        );
        $superAdminUser->roles()->sync([$superAdmin->id => ['start_date' => $today]]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => Hash::make('password')]
        );
        $adminUser->roles()->sync([$admin->id => ['start_date' => $today]]);

        $normalUser = User::firstOrCreate(
            ['email' => 'user@gmail.com'],
            ['name' => 'Normal User', 'password' => Hash::make('password')]
        );
        $normalUser->roles()->sync([$userRole->id => ['start_date' => $today]]);
    }
}
