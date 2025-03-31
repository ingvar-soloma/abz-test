<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{

    final public function viewAny(): bool
    {
        return true;

    }

    final public function view(User $user): bool
    {
        return true;

    }

    final public function create(): bool
    {
        return true;

    }

    final public function update(User $user): bool
    {
        return true;

    }

    final public function delete(User $user): bool
    {
        return false;
    }

    final public function restore(User $user): bool
    {
        return false;

    }

    final public function forceDelete(User $user): bool
    {
        return false;
    }
}
