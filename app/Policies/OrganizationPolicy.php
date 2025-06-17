<?php

namespace App\Policies;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrganizationPolicy
{    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can view organizations if they have the proper permission
        return $user->can('view_organizations');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Organization $organization): bool
    {
        // Super admins and admins can view all organizations
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            return true;
        }
        
        // Users can view organizations they belong to
        return $organization->hasMember($user);
    }    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins or users with specific permission can create organizations
        return $user->hasRole(['Super Admin', 'Admin']) || $user->can('create_organizations');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Organization $organization): bool
    {
        // Super admins and admins can update any organization
        if ($user->hasRole(['Super Admin', 'Admin'])) {
            return true;
        }
        
        // Organization members with specific permission can update
        return $organization->hasMember($user) && $user->can('manage_organizations');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Organization $organization): bool
    {
        // Only super admins and admins can delete organizations
        return $user->hasRole(['Super Admin', 'Admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Organization $organization): bool
    {
        return $user->hasRole(['Super Admin', 'Admin']);
    }    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Organization $organization): bool
    {
        return $user->hasRole(['Super Admin', 'Admin']);
    }
}
