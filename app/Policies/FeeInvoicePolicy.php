<?php

namespace App\Policies;

use App\Models\FeeInvoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Response;

class FeeInvoicePolicy
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
        return $user->can('section CRUD');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return mixed
     */
    public function view(User $user, FeeInvoice $feeInvoice)
    {
        $isOwner = $feeInvoice->user_id === $user->id;

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
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return mixed
     */
    public function update(User $user, FeeInvoice $feeInvoice)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return mixed
     */
    public function delete(User $user, FeeInvoice $feeInvoice)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return mixed
     */
    public function restore(User $user, FeeInvoice $feeInvoice)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FeeInvoice  $feeInvoice
     * @return mixed
     */
    public function forceDelete(User $user, FeeInvoice $feeInvoice)
    {
        return false;
    }
}
