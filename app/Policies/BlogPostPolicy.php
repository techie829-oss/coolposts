<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BlogPostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view blog posts
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BlogPost $blogPost): bool
    {
        // Admin can view any blog post, users can only view their own
        return $user->isAdmin() || $user->id === $blogPost->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create blog posts
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BlogPost $blogPost): bool
    {
        // Admin can update any blog post, users can only update their own
        return $user->isAdmin() || $user->id === $blogPost->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BlogPost $blogPost): bool
    {
        // Admin can delete any blog post, users can only delete their own
        return $user->isAdmin() || $user->id === $blogPost->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BlogPost $blogPost): bool
    {
        // Admin can restore any blog post, users can only restore their own
        return $user->isAdmin() || $user->id === $blogPost->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BlogPost $blogPost): bool
    {
        // Admin can permanently delete any blog post, users can only delete their own
        return $user->isAdmin() || $user->id === $blogPost->user_id;
    }
}
