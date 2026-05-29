<?php

namespace App\Helpers;

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

    public static function getRoleLabel(?string $role): string
    {
        return match ($role) {
            self::ROLE_SUPER_ADMIN => 'Super Administrateur',
            self::ROLE_USER => 'Utilisateur',
            default => ucfirst((string) $role)
        };
    }
}
