<?php

namespace App\Policies;

use App\Models\CustomerCar;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class customerCarPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'customer';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomerCar $customerCar): bool
    {
        return $user->id === $customerCar->customer_id && $user->role == "customer";
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role =="customer"?
        Response::allow()
        : Response::deny('You have to be customer');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomerCar $customerCar): bool
    {
        return $user->id === $customerCar->customer_id && $user->role === 'customer';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomerCar $customerCar): bool
    {
       return $user->id === $customerCar->customer_id && $user->role === 'customer';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomerCar $customerCar): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomerCar $customerCar): bool
    {
        return false;
    }
}
