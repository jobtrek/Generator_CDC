<?php

namespace App\Helpers;

use App\Models\User;

class RoleHelper
{
    public const ROLE_SUPER_ADMIN = 'super-admin';
    public const ROLE_USER = 'user';

    public static function getAvailableRoles(): array
    {
        return [
            self::ROLE_SUPER_ADMIN,
            self::ROLE_USER,
        ];
    }

    public static function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    public static function getPrimaryRole(User $user): ?string
    {
        return $user->getRoleNames()->first();
    }

    public static function getRoleBadgeColor(?string $role): string
    {
        return match($role) {
            self::ROLE_SUPER_ADMIN => 'red',
            self::ROLE_USER => 'green',
            default => 'gray'
        };
    }

    public static function getRoleLabel(?string $role): string
    {
        return match($role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrateur',
            self::ROLE_USER => 'Utilisateur',
            default => ucfirst((string) $role)
        };
    }
}
