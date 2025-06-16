<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserAuthMethod;
use Illuminate\Console\Command;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-test {email=test@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for magic link testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // Check if user already exists
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->info("User with email {$email} already exists (ID: {$existingUser->id})");
            return;
        }
        
        try {
            // Create user
            $user = User::create([
                'name' => 'Test User',
                'email' => $email,
                'password' => bcrypt('password'),
                'is_active' => true,
            ]);
            
            // Create auth method
            UserAuthMethod::create([
                'user_id' => $user->id,
                'auth_method_type' => 'email_password',
                'identifier' => $email,
                'is_active' => true,
                'is_verified' => true,
            ]);
            
            $this->info("Test user created successfully!");
            $this->info("Email: {$email}");
            $this->info("Password: password");
            $this->info("User ID: {$user->id}");
            
        } catch (\Exception $e) {
            $this->error("Failed to create test user: " . $e->getMessage());
        }
    }
}
