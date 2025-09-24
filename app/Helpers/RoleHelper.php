<?php

namespace App\Helpers;

use App\Models\User;

class RoleHelper
{
    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public static function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    /**
     * Vérifier si l'utilisateur a une permission
     */
    public static function hasPermission(User $user, string $permission): bool
    {
        return $user->hasPermissionTo($permission);
    }

    /**
     * Obtenir le rôle principal de l'utilisateur
     */
    public static function getPrimaryRole(User $user): ?string
    {
        $roles = $user->getRoleNames();

        // Priorité des rôles
        $rolePriority = [
            'super-admin',
            'admin',
            'formateur',
            'user',
            'guest'
        ];

        foreach ($rolePriority as $role) {
            if ($roles->contains($role)) {
                return $role;
            }
        }

        return $roles->first();
    }

    /**
     * Obtenir la couleur badge pour un rôle
     */
    public static function getRoleBadgeColor(string $role): string
    {
        return match($role) {
            'super-admin' => 'red',
            'admin' => 'orange',
            'formateur' => 'blue',
            'user' => 'green',
            'guest' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Obtenir le label traduit du rôle
     */
    public static function getRoleLabel(string $role): string
    {
        return match($role) {
            'super-admin' => 'Super Administrateur',
            'admin' => 'Administrateur',
            'formateur' => 'Gestionnaire',
            'user' => 'Utilisateur',
            'guest' => 'Invité',
            default => ucfirst($role)
        };
    }
}
