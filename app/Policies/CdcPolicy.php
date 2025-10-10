<?php

namespace App\Policies;

use App\Models\Cdc;
use App\Models\User;

class CdcPolicy
{
    public function view(User $user, Cdc $cdc): bool
    {
        return $user->id === $cdc->user_id;
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
