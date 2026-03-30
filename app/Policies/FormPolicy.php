<?php

namespace App\Policies;

use App\Helpers\RoleHelper;
use App\Models\Form;
use App\Models\User;

class FormPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        return $user->hasRole(RoleHelper::ROLE_SUPER_ADMIN) ? true : null;
    }

    public function view(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    public function update(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    public function delete(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }
}
