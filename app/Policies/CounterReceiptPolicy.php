<?php

namespace App\Policies;

use App\Models\CounterReceipt;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounterReceiptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // If user has role of admin, then they can view any counter receipt.
        if ($user->hasRole('admin')) {
            return true;
        }
        // Only users with the role of accountant can view any counter receipt.
        return $user->hasRole('accountant');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, CounterReceipt $counterReceipt)
    {
        // If user has role of admin, then they can view any counter receipt.
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only users with the role of accountant can view any counter receipt.
        return $user->hasRole('accountant');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Only users with the role of accountant can create a counter receipt.
        return $user->hasRole('accountant');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, CounterReceipt $counterReceipt)
    {
        // Allow users with role of admin to update any counter receipt.
        if ($user->hasRole('admin')) {
            return true;
        }

        // If user has role of accountant and counter receipt is not completed then they can update the counter receipt.
        if ($user->hasRole('accountant') && !$counterReceipt->completed) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, CounterReceipt $counterReceipt)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, CounterReceipt $counterReceipt)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CounterReceipt  $counterReceipt
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, CounterReceipt $counterReceipt)
    {
        //
    }
}
