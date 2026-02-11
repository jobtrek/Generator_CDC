<?php

namespace App\Helpers;

use App\Models\User;

class RoleHelper
{
    protected const ROLE_HIERARCHY = [
        'super-admin' => 100,
        'user'        => 10,
    ];
    public static function canAssignRole(User $actor, string $targetRoleName): bool
    {
        $actorRole = self::getPrimaryRole($actor);
        if (!$actorRole) return false;

        $actorWeight = self::getRoleWeight($actorRole);
        $targetWeight = self::getRoleWeight($targetRoleName);

        if ($actorRole === 'super-admin') {
            return true;
        }
        return $actorWeight > $targetWeight;
    }

    public static function getRoleWeight(string $role): int
    {
        return self::ROLE_HIERARCHY[$role] ?? 0;
    }


    public static function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    public static function getPrimaryRole(User $user): ?string
    {
        $roles = $user->getRoleNames();
        $sortedRoles = $roles->sortByDesc(function ($role) {
            return self::getRoleWeight($role);
        });

        return $sortedRoles->first();
    }

    public static function getRoleBadgeColor(string $role): string
    {
        return match($role) {
            'super-admin' => 'red',
            'user' => 'green',
            default => 'gray'
        };
    }

    public static function getRoleLabel(string $role): string
    {
        return match($role) {
            'super-admin' => 'Super Administrateur',
            'user' => 'Utilisateur',
            default => ucfirst($role)
        };
    }
}
