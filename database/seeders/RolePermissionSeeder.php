<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'cdcs.view', 'cdcs.create', 'cdcs.edit', 'cdcs.delete', 'cdcs.export',
            'users.view', 'users.create', 'users.edit', 'users.delete', 'users.manage_roles',
            'settings.view', 'settings.edit'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdminRole->syncPermissions(Permission::all());

        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->syncPermissions([
            'cdcs.view',
            'cdcs.create',
        ]);

        $usersToCreate = [
            [
                'email' => 'superadmin@cdcs.com',
                'name' => 'Super Admin',
                'role' => 'super-admin',
                'password' => 'password123'
            ],
        ];

        foreach ($usersToCreate as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'email_verified_at' => now(),
                ]
            );

            if ($user->wasRecentlyCreated) {
                $user->assignRole($u['role']);
            }
        }

        $this->command->info('✅ Rôles et permissions synchronisés (les utilisateurs existants n\'ont pas été modifiés).');
    }
}
