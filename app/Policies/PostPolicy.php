<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("view Any Post");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->hasPermissionTo("view Post");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create Post");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->hasPermissionTo("update Post");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->hasPermissionTo("delete Post");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->hasPermissionTo("restore Post");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->hasPermissionTo("force Delete Post");
    }
}
