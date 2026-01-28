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

        $cdcPermissions = ['cdcs.edit', 'cdcs.delete', 'cdcs.export', 'cdcs.duplicate'];
        $formPermissions = ['form.view', 'form.create', 'form.edit', 'form.delete', 'form.publish'];
        $userPermissions = ['user.view', 'user.create', 'user.edit', 'user.delete', 'user.roles'];
        $systemPermissions = [
            'dashboard.view', 'settings.view', 'settings.edit',
            'logs.view', 'backup.create', 'backup.download'
        ];

        $allPermissions = array_merge($cdcPermissions, $formPermissions, $userPermissions, $systemPermissions);

        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'cdcs.edit', 'cdcs.delete', 'cdcs.export', 'cdcs.duplicate',
            'form.view', 'form.create', 'form.edit', 'form.delete', 'form.publish',
            'user.view', 'user.create', 'user.edit',
            'dashboard.view', 'settings.view', 'logs.view',
        ]);

        $formateur = Role::firstOrCreate(['name' => 'formateur']);
        $formateur->syncPermissions([
            'cdcs.edit', 'cdcs.export', 'cdcs.duplicate',
            'form.view', 'form.create', 'form.edit', 'form.publish',
            'dashboard.view',
        ]);

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions([
            'cdcs.export', 'form.view', 'dashboard.view',
        ]);

        $usersToCreate = [
            ['email' => 'superadmin@cdcs.com', 'name' => 'Super Admin', 'role' => 'super-admin'],
            ['email' => 'admin@cdcs.com', 'name' => 'Admin User', 'role' => 'admin'],
            ['email' => 'formateur@cdcs.com', 'name' => 'Formateur User', 'role' => 'formateur'],
            ['email' => 'user@cdcs.com', 'name' => 'Normal User', 'role' => 'user'],
        ];

        foreach ($usersToCreate as $u) {
            $userModel = User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]
            );
            $userModel->syncRoles($u['role']);
        }

        $this->command->info('✅ Rôles et permissions synchronisés avec succès !');
    }
}
