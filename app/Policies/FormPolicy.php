<?php

namespace App\Policies;

use App\Models\Form;
use App\Models\User;

class FormPolicy
{
    /**
     * Determine if the user can view the form.
     */

    public function view(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    /**
     * Determine if the user can update the form.
     */
    public function update(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }

    /**
     * Determine if the user can delete the form.
     */
    public function delete(User $user, Form $form): bool
    {
        return $user->id === $form->user_id;
    }
}
