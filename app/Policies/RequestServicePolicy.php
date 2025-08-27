<?php

namespace App\Policies;

use App\Models\RequestService;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;
class RequestServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role=="admin";
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RequestService $request): bool
    {
         switch ($user->role) {
            case 'customer':
                // Customer can view their own requests
                return $user->id === $request->customerCar->customer_id;
                
            case 'provider':
                // Provider can view assigned requests
                return $user->provider && $user->provider->id === $request->provider_id;
                
            case 'admin':
                // Admin can view all requests
                return true;
                
            default:
                return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        $ok=$user->role=="customer";
        Log::info("user role is ". $user->role. " ok is ". $ok);
        return $ok;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RequestService $requestService): bool
    {
        return $user->role=="provider" && $requestService->provider_id!=null && $requestService->provider_id==$user->provider->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RequestService $requestService): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RequestService $requestService): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RequestService $requestService): bool
    {
        return false;
    }
public function accept(User $user, RequestService $requestService)
{
    // Only providers can accept
    return $user->role === 'provider' && $requestService->status === 'pending';
}
/**
     * Determine whether the user can view incoming requests.
     * Only providers can view incoming unassigned requests
     */
    public function viewIncoming(User $user): bool
    {
        return $user->role === 'provider' && $user->provider;
    }
     public function viewAssigned(User $user): bool
    {
        return $user->role === 'provider' && $user->provider;
    }
}
