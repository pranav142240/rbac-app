<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        // Define all permissions
        $permissions = [
            // User Management Permissions
            'create_users',
            'update_users',
            'view_users',
            'soft_delete_users',
            'delete_users',
            'recover_users',
            'manage_user_permissions',
            
            // Profile Management Permissions
            'update_own_profile',
            'create_own_security_method',
            'update_own_security_method',
            'delete_own_security_method',
              // Role Management Permissions
            'view_roles',
            'create_roles',
            'edit_roles',
            'update_roles',
            'delete_roles',
              // Permission Management Permissions
            'view_permissions',
            'edit_permissions',
            'update_permissions',
            'delete_permissions',
            
            // Organization Management Permissions
            'view_organizations',
            'create_organizations',
            'update_organizations',
            'delete_organizations',
            'manage_organizations',
            'join_organizations',
            'leave_organizations',
            
            // Organization Group Management Permissions
            'view_organization_groups',
            'create_organization_groups',
            'update_organization_groups',
            'delete_organization_groups',
            'manage_organization_groups',
            'join_organization_groups',
            'leave_organization_groups',
            
            // Post Management Permissions
            'create_posts',
            'update_posts',
            'delete_posts',
            'publish_posts',
            'unpublish_posts',
            'view_posts',
            
            // Own Post Management Permissions
            'create_own_posts',
            'update_own_posts',
            'delete_own_posts',
            'publish_own_posts',
            'unpublish_own_posts',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }        // Define roles and their permissions
        $rolesWithPermissions = [
            'Admin' => $permissions, // Admin has all permissions
            'Application User' => [
                'update_own_profile',
                'create_own_security_method',
                'update_own_security_method',
                'delete_own_security_method',
                'view_organizations',
                'join_organizations',
                'leave_organizations',
                'view_organization_groups',
                'join_organization_groups',
                'leave_organization_groups',
            ],
            'User Manager' => [
                'create_users',
                'update_users',
                'view_users',
            ],
            'Post Manager' => [
                'create_posts',
                'update_posts',
                'delete_posts',
                'publish_posts',
                'unpublish_posts',
                'view_posts',
            ],            'Role Manager' => [
                'view_roles',
                'create_roles',
                'edit_roles',
                'update_roles',
                'delete_roles',
                'view_permissions',
                'edit_permissions',
                'update_permissions',
                'delete_permissions',
                'manage_user_permissions',
            ],
            'Post Author' => [
                'create_own_posts',
                'update_own_posts',
                'delete_own_posts',
                'publish_own_posts',
                'unpublish_own_posts',
                'view_posts',
            ],
            'Organization Manager' => [
                'view_organizations',
                'create_organizations',
                'update_organizations',
                'manage_organizations',
                'view_organization_groups',
                'create_organization_groups',
                'update_organization_groups',
                'delete_organization_groups',
                'manage_organization_groups',
            ],
        ];        // Create roles and assign permissions
        foreach ($rolesWithPermissions as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin@12345'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password',
            ]
        );        // Create auth method for admin user if it doesn't exist
        \App\Models\UserAuthMethod::firstOrCreate(
            [
                'user_id' => $adminUser->id,
                'auth_method_type' => 'email_password',
                'identifier' => 'admin@example.com'
            ],
            [
                'is_active' => true,
                'auth_method_verified_at' => now(),
            ]
        );

        // Assign admin role to the default admin user
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole && !$adminUser->hasRole('Admin')) {
            $adminUser->assignRole($adminRole);
        }

        $this->command->info('Permissions and roles seeded successfully!');
        $this->command->info('Default admin user created: admin@example.com (Password: Admin@12345)');
    }
}
