<?php

namespace App\Policies;

use App\Models\BioPage;
use App\Models\User;

class BioPagePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BioPage $bioPage): bool
    {
        return $user->id === $bioPage->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, BioPage $bioPage): bool
    {
        return $user->id === $bioPage->user_id;
    }

    public function delete(User $user, BioPage $bioPage): bool
    {
        return $user->id === $bioPage->user_id;
    }

    public function restore(User $user, BioPage $bioPage): bool
    {
        return $user->id === $bioPage->user_id;
    }

    public function forceDelete(User $user, BioPage $bioPage): bool
    {
        return $user->id === $bioPage->user_id;
    }
}
