<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Services\AuthService;

class OtpTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authService = new AuthService();

        // Create a test user with email OTP
        $emailOtpData = [
            'name' => 'Email OTP User',
            'email' => 'test-email-otp@example.com',
            'auth_method_type' => 'email_otp',
            'identifier' => 'test-email-otp@example.com'
        ];

        try {
            $result = $authService->register($emailOtpData);
            $this->command->info('Created Email OTP user: test-email-otp@example.com');
        } catch (\Exception $e) {
            $this->command->error('Failed to create Email OTP user: ' . $e->getMessage());
        }

        // Create a test user with phone OTP
        $phoneOtpData = [
            'name' => 'Phone OTP User',
            'phone' => '+1234567890',
            'auth_method_type' => 'phone_otp',
            'identifier' => '+1234567890'
        ];

        try {
            $result = $authService->register($phoneOtpData);
            $this->command->info('Created Phone OTP user: +1234567890');
        } catch (\Exception $e) {
            $this->command->error('Failed to create Phone OTP user: ' . $e->getMessage());
        }

        // Create a regular email+password user for comparison
        $passwordData = [
            'name' => 'Password User',
            'email' => 'test-password@example.com',
            'password' => 'password123',
            'auth_method_type' => 'email_password',
            'identifier' => 'test-password@example.com'
        ];

        try {
            $result = $authService->register($passwordData);
            $this->command->info('Created Password user: test-password@example.com');
        } catch (\Exception $e) {
            $this->command->error('Failed to create Password user: ' . $e->getMessage());
        }
    }
}
