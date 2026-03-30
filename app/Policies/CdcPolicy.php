<?php

namespace App\Policies;

use App\Helpers\RoleHelper;
use App\Models\Cdc;
use App\Models\User;

class CdcPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole(RoleHelper::ROLE_SUPER_ADMIN) ? true : null;
    }
    public function view(User $user, Cdc $cdc): bool
    {
        return $cdc->form->user_id === $user->id;
    }

    public function update(User $user, Cdc $cdc): bool
    {
        return $user->id === $cdc->user_id;
    }

    public function delete(User $user, Cdc $cdc): bool
    {
        return $user->id === $cdc->user_id;
    }
}
