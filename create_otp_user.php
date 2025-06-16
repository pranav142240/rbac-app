<?php
// Temporary script to create OTP test user
require_once 'bootstrap/app.php';

use App\Models\User;
use App\Models\UserAuthMethod;
use Illuminate\Support\Facades\Hash;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    // Create user for email OTP
    $user = User::create([
        'name' => 'Email OTP Test User',
        'email' => 'test-otp@example.com',
        'password' => null, // No password for OTP-only user
        'primary_auth_method' => 'email_otp',
        'is_active' => true,
        'email_verified_at' => now(), // Mark as verified
    ]);

    // Create the email OTP auth method
    UserAuthMethod::create([
        'user_id' => $user->id,
        'auth_method_type' => 'email_otp',
        'identifier' => 'test-otp@example.com',
        'is_active' => true,
        'auth_method_verified_at' => now(),
    ]);

    echo "✅ Successfully created OTP test user!\n";
    echo "Email: test-otp@example.com\n";
    echo "Auth Method: Email + OTP\n";
    echo "You can now test OTP login with this email.\n\n";

    // Also create one with email+password for comparison
    $passwordUser = User::create([
        'name' => 'Password Test User', 
        'email' => 'test-password@example.com',
        'password' => Hash::make('password123'),
        'primary_auth_method' => 'email_password',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    UserAuthMethod::create([
        'user_id' => $passwordUser->id,
        'auth_method_type' => 'email_password',
        'identifier' => 'test-password@example.com',
        'is_active' => true,
        'auth_method_verified_at' => now(),
    ]);

    echo "✅ Also created password test user!\n";
    echo "Email: test-password@example.com\n";
    echo "Password: password123\n";
    echo "Auth Method: Email + Password\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
