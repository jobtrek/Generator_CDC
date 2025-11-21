<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $cdcPermissions = [
            'cdcs.view',
            'cdcs.edit',
            'cdcs.delete',
            'cdcs.export',
            'cdcs.duplicate',
        ];

        $formPermissions = [
            'form.view',
            'form.create',
            'form.edit',
            'form.delete',
            'form.publish',
        ];

        $userPermissions = [
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.roles',
        ];

        $systemPermissions = [
            'dashboard.view',
            'settings.view',
            'settings.edit',
            'logs.view',
            'backup.create',
            'backup.download',
        ];

        $allPermissions = array_merge(
            $cdcPermissions,
            $formPermissions,
            $userPermissions,
            $systemPermissions
        );

        foreach ($allPermissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer les rôles

        $superAdmin = Role::create(['name' => 'super-admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'cdcs.view', 'cdcs.edit', 'cdcs.delete', 'cdcs.export', 'cdcs.duplicate',
            'form.view', 'form.create', 'form.edit', 'form.delete', 'form.publish',
            'user.view', 'user.create', 'user.edit',
            'dashboard.view', 'settings.view', 'logs.view',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'cdcs.view', 'cdcs.export',
            'form.view',
            'dashboard.view',
        ]);

        $guest = Role::create(['name' => 'guest']);
        $guest->givePermissionTo([
            'cdcs.view',
            'form.view',
            'dashboard.view',
        ]);


        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@cdcs.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole('super-admin');

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@cdcs.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        $this->command->info('✅ Rôles et permissions créés avec succès !');
        $this->command->table(
            ['Email', 'Rôle', 'Mot de passe'],
            [
                ['superadmin@cdcs.com', 'Super Admin', 'password123'],
                ['admin@cdcs.com', 'Admin', 'password123'],
                ['formateur@cdcs.com', 'formateur', 'password123'],
                ['user@cdcs.com', 'User', 'password123'],
            ]
        );
    }
}
