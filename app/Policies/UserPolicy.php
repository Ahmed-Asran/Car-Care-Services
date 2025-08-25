<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     * only Admin can list all users.
     */
    public function viewAny(User $user): bool
    {
        return $user->role=='admin';
    }

    /**
     * Determine whether the user can view the model.
     * A user can view their own profile, or admin can view any.
     */
    public function view(User $user, User $model): bool
    {
        return $user->id==$model->id||$user->role=='admin';
    }

    /**
     * Determine whether the user can create models.
     * Users cannot create other users manually.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     * A user can update their own profile, or admin can update any.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id==$model->id||$user->role=='admin';
    }

    /**
     * Determine whether the user can delete the model.
     * Only Admin can delete users.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->role=='admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
