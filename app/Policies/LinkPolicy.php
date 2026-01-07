<?php

namespace App\Policies;

use App\Models\Link;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LinkPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admin can see all links, users can only see their own
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Link $link): bool
    {
        // Admin can see any link, users can only see their own
        return $user->isAdmin() || $user->id === $link->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create links
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Link $link): bool
    {
        // Admin can update any link, users can only update their own
        return $user->isAdmin() || $user->id === $link->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Link $link): bool
    {
        // Admin can delete any link, users can only delete their own
        return $user->isAdmin() || $user->id === $link->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Link $link): bool
    {
        // Admin can restore any link, users can only restore their own
        return $user->isAdmin() || $user->id === $link->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Link $link): bool
    {
        // Admin can permanently delete any link, users can only delete their own
        return $user->isAdmin() || $user->id === $link->user_id;
    }
}
