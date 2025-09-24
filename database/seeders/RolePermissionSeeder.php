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
            'cdc.view',
            'cdc.create',
            'cdc.edit',
            'cdc.delete',
            'cdc.export',
            'cdc.duplicate',
        ];

        $formPermissions = [
            'form.view',
            'form.create',
            'form.edit',
            'form.delete',
            'form.publish',
        ];

        $templatePermissions = [
            'template.view',
            'template.create',
            'template.edit',
            'template.delete',
            'template.import',
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
            $templatePermissions,
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
            'cdc.view', 'cdc.create', 'cdc.edit', 'cdc.delete', 'cdc.export', 'cdc.duplicate',
            'form.view', 'form.create', 'form.edit', 'form.delete', 'form.publish',
            'template.view', 'template.create', 'template.edit', 'template.delete', 'template.import',
            'user.view', 'user.create', 'user.edit',
            'dashboard.view', 'settings.view', 'logs.view',
        ]);

        $formateur = Role::create(['name' => 'formateur']);
        $formateur->givePermissionTo([
            'cdc.view', 'cdc.create', 'cdc.edit', 'cdc.delete', 'cdc.export', 'cdc.duplicate',
            'form.view', 'form.create', 'form.edit', 'form.delete', 'form.publish',
            'template.view', 'template.create', 'template.edit',
            'user.view',
            'dashboard.view',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'cdc.view', 'cdc.create', 'cdc.export',
            'form.view',
            'template.view',
            'dashboard.view',
        ]);

        $guest = Role::create(['name' => 'guest']);
        $guest->givePermissionTo([
            'cdc.view',
            'form.view',
            'template.view',
            'dashboard.view',
        ]);


        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@cdc.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdminUser->assignRole('super-admin');

        // Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@cdc.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        $formateurUser = User::firstOrCreate(
            ['email' => 'formateur@cdc.com'],
            [
                'name' => 'formateur User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $formateurUser->assignRole('formateur');

        $normalUser = User::firstOrCreate(
            ['email' => 'user@cdc.com'],
            [
                'name' => 'Normal User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $normalUser->assignRole('user');

        // Guest
        $guestUser = User::firstOrCreate(
            ['email' => 'guest@cdc.com'],
            [
                'name' => 'Guest User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]
        );
        $guestUser->assignRole('guest');

        $this->command->info('✅ Rôles et permissions créés avec succès !');
        $this->command->table(
            ['Email', 'Rôle', 'Mot de passe'],
            [
                ['superadmin@cdc.com', 'Super Admin', 'password123'],
                ['admin@cdc.com', 'Admin', 'password123'],
                ['formateur@cdc.com', 'formateur', 'password123'],
                ['user@cdc.com', 'User', 'password123'],
                ['guest@cdc.com', 'Guest', 'password123'],
            ]
        );
    }
}
