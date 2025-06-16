<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Google OAuth Configuration Debug ===\n\n";

// Check Google OAuth configuration
$googleConfig = config('services.google');
echo "Google OAuth Configuration:\n";
echo "- Client ID: " . ($googleConfig['client_id'] ?? 'MISSING') . "\n";
echo "- Client Secret: " . (isset($googleConfig['client_secret']) ? str_repeat('*', 10) : 'MISSING') . "\n";
echo "- Redirect URI: " . ($googleConfig['redirect'] ?? 'MISSING') . "\n\n";

// Check if Socialite is working
try {
    $driver = \Laravel\Socialite\Facades\Socialite::driver('google');
    echo "✅ Socialite Google driver loaded successfully\n";
    
    // Test redirect URL generation
    $redirectUrl = $driver->redirect()->getTargetUrl();
    echo "✅ Google redirect URL generated: " . substr($redirectUrl, 0, 100) . "...\n";
    
} catch (Exception $e) {
    echo "❌ Socialite error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

// Check environment variables
echo "\nEnvironment Variables:\n";
echo "- GOOGLE_CLIENT_ID: " . (env('GOOGLE_CLIENT_ID') ? 'Set' : 'MISSING') . "\n";
echo "- GOOGLE_CLIENT_SECRET: " . (env('GOOGLE_CLIENT_SECRET') ? 'Set' : 'MISSING') . "\n";
echo "- GOOGLE_REDIRECT_URI: " . (env('GOOGLE_REDIRECT_URI') ?: 'Using default') . "\n";
