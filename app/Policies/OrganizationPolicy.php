<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view organizations
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        // Users can view organizations they belong to, or if they have admin role
        return $user->hasRole('admin') || $organization->hasMember($user);
    }    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins or users with specific permission can create organizations
        return $user->hasRole('admin') || $user->can('create_organizations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        // Only admins or organization members with specific role can update
        return $user->hasRole('admin') || 
               ($organization->hasMember($user) && $user->can('manage_organizations'));
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        // Only admins can delete organizations
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organization $organization): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        return $user->hasRole('admin');
    }
}
