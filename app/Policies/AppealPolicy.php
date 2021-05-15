<?php

namespace App\Policies;

use App\Models\Appeal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appeal  $appeal
     * @return mixed
     */
    public function view(User $user, Appeal $appeal)
    {
        $isOwner = $appeal->user_id === $user->id;

        $hasPermission = $user->can('section CRUD');

        return $isOwner || $hasPermission;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appeal  $appeal
     * @return mixed
     */
    public function update(User $user, Appeal $appeal)
    {
        $isOwner = $appeal->user_id === $user->id;

        $updatable = $appeal->appealState->name === "Created";

        return $isOwner && $updatable;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appeal  $appeal
     * @return mixed
     */
    public function delete(User $user, Appeal $appeal)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appeal  $appeal
     * @return mixed
     */
    public function restore(User $user, Appeal $appeal)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Appeal  $appeal
     * @return mixed
     */
    public function forceDelete(User $user, Appeal $appeal)
    {
        return false;
    }
}
