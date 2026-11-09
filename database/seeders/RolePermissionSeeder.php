<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view dashboard',
            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($permissions);

        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operator->syncPermissions(['view dashboard']);

        $user = User::firstOrCreate(
            ['email' => 'rheadebora@gmail.com'],
            [
                'name' => 'Rhea Debora Sianturi',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        $user->forceFill([
            'name' => 'Rhea Debora Sianturi',
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        $user->syncRoles([$admin]);
    }
}
