<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionService
{
    /**
     * Check if the current user has permission to view organizations.
     */
    public function canViewOrganizations(): bool
    {
        return Auth::user()?->can('view_organizations') ?? false;
    }

    /**
     * Check if the current user has permission to create organizations.
     */
    public function canCreateOrganizations(): bool
    {
        return Auth::user()?->can('create_organizations') ?? false;
    }

    /**
     * Check if the current user has permission to update organizations.
     */
    public function canUpdateOrganizations(): bool
    {
        return Auth::user()?->can('update_organizations') ?? false;
    }

    /**
     * Check if the current user has permission to delete organizations.
     */
    public function canDeleteOrganizations(): bool
    {
        return Auth::user()?->can('delete_organizations') ?? false;
    }

    /**
     * Check if the current user has permission to manage organizations.
     */
    public function canManageOrganizations(): bool
    {
        return Auth::user()?->can('manage_organizations') ?? false;
    }

    /**
     * Get all permissions for the current user.
     */
    public function getUserPermissions(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        return $user->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        return $user->hasAnyPermission($permissions);
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $user = Auth::user();
        
        if (!$user) {
            return false;
        }

        return $user->hasAllPermissions($permissions);
    }

    /**
     * Get roles with specific permissions.
     */
    public function getRolesWithPermissions(array $permissions): array
    {
        return Role::whereHas('permissions', function ($query) use ($permissions) {
            $query->whereIn('name', $permissions);
        })->get()->toArray();
    }

    /**
     * Get permissions by category.
     */
    public function getPermissionsByCategory(string $category): array
    {
        return Permission::where('name', 'like', $category . '%')->get()->toArray();
    }

    /**
     * Check if current user can access admin panel.
     */
    public function canAccessAdmin(): bool
    {
        return $this->hasAnyPermission([
            'view_users',
            'create_users', 
            'update_users',
            'delete_users',
            'manage_user_permissions'
        ]);
    }
}
