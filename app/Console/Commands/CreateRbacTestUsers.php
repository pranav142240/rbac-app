<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRbacTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:create-test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users with different roles for RBAC testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Creating RBAC Test Users...');

        try {
            // Create Super Admin Test User
            $this->info('Creating Super Admin test user...');
            $superAdmin = User::firstOrCreate([
                'email' => 'super@admin.test'
            ], [
                'name' => 'Super Admin Test',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password'
            ]);
              $superAdminRole = Role::where('name', 'Admin')->first(); // Use Admin as highest role
            if ($superAdminRole && !$superAdmin->hasRole('Admin')) {
                $superAdmin->assignRole('Admin');
            }
            $this->line("✅ Super Admin created: {$superAdmin->email} (Role: Admin)");

            // Create Admin Test User  
            $this->info('Creating Admin test user...');
            $admin = User::firstOrCreate([
                'email' => 'admin@test.com'
            ], [
                'name' => 'Admin Test User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password'
            ]);
            
            $adminRole = Role::where('name', 'Role Manager')->first();
            if ($adminRole && !$admin->hasRole('Role Manager')) {
                $admin->assignRole('Role Manager');
            }
            $this->line("✅ Admin created: {$admin->email} (Role: Role Manager)");

            // Create Manager Test User
            $this->info('Creating Manager test user...');
            $manager = User::firstOrCreate([
                'email' => 'manager@test.com'
            ], [
                'name' => 'Manager Test User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password'
            ]);
            
            $managerRole = Role::where('name', 'User Manager')->first();
            if ($managerRole && !$manager->hasRole('User Manager')) {
                $manager->assignRole('User Manager');
            }
            $this->line("✅ Manager created: {$manager->email} (Role: User Manager)");

            // Create Editor Test User
            $this->info('Creating Editor test user...');
            $editor = User::firstOrCreate([
                'email' => 'editor@test.com'
            ], [
                'name' => 'Editor Test User',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password'
            ]);
            
            $editorRole = Role::where('name', 'Post Author')->first();
            if ($editorRole && !$editor->hasRole('Post Author')) {
                $editor->assignRole('Post Author');
            }
            $this->line("✅ Editor created: {$editor->email} (Role: Post Author)");

            // Create Regular User Test User
            $this->info('Creating User test user...');
            $user = User::firstOrCreate([
                'email' => 'user@test.com'
            ], [
                'name' => 'Regular User Test',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'primary_auth_method' => 'email_password'
            ]);
            
            $userRole = Role::where('name', 'Application User')->first();
            if ($userRole && !$user->hasRole('Application User')) {
                $user->assignRole('Application User');
            }
            $this->line("✅ User created: {$user->email} (Role: Application User)");

            $this->info('');
            $this->info('🎉 All test users created successfully!');
            $this->info('');
            $this->info('Test User Credentials:');
            $this->table(['Role', 'Email', 'Password', 'Assigned Role'], [
                ['Super Admin', 'super@admin.test', 'password123', 'Admin'],
                ['Admin', 'admin@test.com', 'password123', 'Role Manager'],
                ['Manager', 'manager@test.com', 'password123', 'User Manager'],
                ['Editor', 'editor@test.com', 'password123', 'Post Author'],
                ['User', 'user@test.com', 'password123', 'Application User'],
            ]);

        } catch (\Exception $e) {
            $this->error('❌ Error creating test users: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
