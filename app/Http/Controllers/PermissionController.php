<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{    /**
     * Constructor to add middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_permissions')->only(['index', 'show']);
        $this->middleware('permission:edit_permissions')->only(['edit', 'update']);
        $this->middleware('permission:delete_permissions')->only(['destroy']);
    }

    /**
     * Display a listing of permissions categorized
     */
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        // Categorize permissions
        $categorizedPermissions = [
            'User Management' => [
                'icon' => 'users',
                'color' => 'primary',
                'description' => 'Manage user accounts, roles, and permissions',
                'permissions' => []
            ],
            'Profile Management' => [
                'icon' => 'user-circle',
                'color' => 'success',
                'description' => 'Manage user profiles and security settings',
                'permissions' => []
            ],
            'Role Management' => [
                'icon' => 'shield-check',
                'color' => 'info',
                'description' => 'Create and manage system roles',
                'permissions' => []
            ],
            'Permission Management' => [
                'icon' => 'lock-closed',
                'color' => 'error',
                'description' => 'Define and manage system permissions',
                'permissions' => []
            ],
            'Organization Management' => [
                'icon' => 'office-building',
                'color' => 'warning',
                'description' => 'Manage organizations and groups',
                'permissions' => []
            ],
            'Content Management' => [
                'icon' => 'document-text',
                'color' => 'info',
                'description' => 'Manage posts and content creation',
                'permissions' => []
            ],
            'Own Content Management' => [
                'icon' => 'pencil-alt',
                'color' => 'success',
                'description' => 'Manage your own content and posts',
                'permissions' => []
            ],
            'Other' => [
                'icon' => 'cog',
                'color' => 'secondary',
                'description' => 'Miscellaneous system permissions',
                'permissions' => []
            ]
        ];

        foreach ($permissions as $permission) {
            $name = $permission->name;
            $categorized = false;

            // User Management
            if (in_array($name, ['create_users', 'update_users', 'view_users', 'soft_delete_users', 'delete_users', 'recover_users', 'manage_user_permissions'])) {
                $categorizedPermissions['User Management']['permissions'][] = $permission;
                $categorized = true;
            }
            // Profile Management
            elseif (in_array($name, ['update_own_profile', 'create_own_security_method', 'update_own_security_method', 'delete_own_security_method'])) {
                $categorizedPermissions['Profile Management']['permissions'][] = $permission;
                $categorized = true;
            }            // Role Management
            elseif (in_array($name, ['view_roles', 'create_roles', 'edit_roles', 'update_roles', 'delete_roles'])) {
                $categorizedPermissions['Role Management']['permissions'][] = $permission;
                $categorized = true;
            }            // Permission Management
            elseif (in_array($name, ['view_permissions', 'edit_permissions', 'update_permissions', 'delete_permissions'])) {
                $categorizedPermissions['Permission Management']['permissions'][] = $permission;
                $categorized = true;
            }
            // Organization Management
            elseif (in_array($name, ['view_organizations', 'create_organizations', 'update_organizations', 'delete_organizations', 'manage_organizations', 'join_organizations', 'leave_organizations', 'view_organization_groups', 'create_organization_groups', 'update_organization_groups', 'delete_organization_groups', 'manage_organization_groups', 'join_organization_groups', 'leave_organization_groups'])) {
                $categorizedPermissions['Organization Management']['permissions'][] = $permission;
                $categorized = true;
            }
            // Content Management
            elseif (in_array($name, ['create_posts', 'update_posts', 'delete_posts', 'publish_posts', 'unpublish_posts', 'view_posts'])) {
                $categorizedPermissions['Content Management']['permissions'][] = $permission;
                $categorized = true;
            }
            // Own Content Management
            elseif (in_array($name, ['create_own_posts', 'update_own_posts', 'delete_own_posts', 'publish_own_posts', 'unpublish_own_posts'])) {
                $categorizedPermissions['Own Content Management']['permissions'][] = $permission;
                $categorized = true;
            }

            // If not categorized, put in Other
            if (!$categorized) {
                $categorizedPermissions['Other']['permissions'][] = $permission;
            }
        }

        // Remove empty categories
        $categorizedPermissions = array_filter($categorizedPermissions, function($category) {
            return count($category['permissions']) > 0;
        });

        return view('permissions.index', compact('categorizedPermissions'));
    }    /**
     * Display the specified permission.
     */
    public function show(Permission $permission)
    {
        $permission->load('roles');
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing the specified permission.
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'guard_name' => 'nullable|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name ?? 'web'
        ]);

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission from storage.
     */
    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete permission because it is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}
