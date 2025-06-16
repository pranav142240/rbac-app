<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUserPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rbac:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check roles and permissions for test users';

    /**
     * Execute the console command.
     */    public function handle()
    {
        $this->info('🔍 Checking RBAC Test Users...');
        $this->info('');

        // First show available roles
        $roles = \Spatie\Permission\Models\Role::all();
        $this->line("📋 Available Roles in Database:");
        foreach ($roles as $role) {
            $this->line("   - {$role->name} ({$role->permissions->count()} permissions)");
        }
        $this->info('');

        $testEmails = [
            'super@admin.test',
            'admin@test.com',
            'manager@test.com',
            'editor@test.com',
            'user@test.com'
        ];

        foreach ($testEmails as $email) {
            $user = User::where('email', $email)->with('roles.permissions')->first();
            
            if ($user) {
                $this->line("👤 <info>{$user->name}</info> ({$email})");
                
                // Roles
                $roles = $user->roles->pluck('name')->toArray();
                if (!empty($roles)) {
                    $this->line("   🎭 Roles: " . implode(', ', $roles));
                } else {
                    $this->line("   🎭 Roles: <comment>None assigned</comment>");
                }
                
                // Permissions
                $permissions = $user->getAllPermissions();
                if ($permissions->count() > 0) {
                    $this->line("   🔑 Permissions: {$permissions->count()} total");
                    $permissionNames = $permissions->pluck('name')->take(8)->toArray();
                    $this->line("   📋 Sample: " . implode(', ', $permissionNames) . 
                        ($permissions->count() > 8 ? " (and " . ($permissions->count() - 8) . " more)" : ""));
                } else {
                    $this->line("   🔑 Permissions: <comment>None assigned</comment>");
                }
                
                $this->line('');
            }
        }

        $this->info('✅ Check completed!');
        return 0;
    }
}
