<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserAuthMethod;
use App\Models\Otp;
use App\Models\MagicLink;
use App\Models\SocialAccount;
use App\Mail\OtpMail;
use App\Mail\MagicLinkMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use GuzzleHttp\Client;
use Spatie\Permission\Models\Role;

class AuthService
{
    /**
     * Register a new user with primary authentication method
     */    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            'primary_auth_method' => $data['auth_method_type'],
        ]);        
        
        // Assign the default "Application User" role
        $this->assignDefaultRole($user);
        
        $authMethod = $this->createAuthMethod($user, $data);

        return [
            'user' => $user,
            'auth_method' => $authMethod,
        ];
    }/**
     * Create authentication method for user
     */
    public function createAuthMethod(User $user, array $data)
    {
        // If adding a password-based method, hash and store the password in user table
        if (in_array($data['auth_method_type'], ['email_password', 'phone_password']) && !empty($data['password'])) {
            // Only update password if user doesn't have one or if explicitly updating
            if (empty($user->password)) {
                $user->update(['password' => Hash::make($data['password'])]);
            }
        }

        $authMethod = UserAuthMethod::create([
            'user_id' => $user->id,
            'auth_method_type' => $data['auth_method_type'],
            'identifier' => $data['identifier'] ?? $data['email'] ?? $data['phone'],
            'is_active' => true,
            'provider_id' => $data['provider_id'] ?? null,
            'provider_data' => $data['provider_data'] ?? null,
        ]);

        // Auto-verify password-based methods during registration
        if (in_array($data['auth_method_type'], ['email_password', 'phone_password'])) {
            $authMethod->markAsVerified();
        }

        return $authMethod;
    }    /**
     * Authenticate user with different methods
     */
    public function authenticate(array $credentials)
    {
        $authType = $credentials['auth_type'];
        
        switch ($authType) {
            case 'email_password':
                $identifier = $credentials['identifier'];
                return $this->authenticateWithPassword($identifier, $credentials['password'], 'email');
            
            case 'phone_password':
                $identifier = $credentials['identifier'];
                return $this->authenticateWithPassword($identifier, $credentials['password'], 'phone');
            
            case 'email_otp':
                $identifier = $credentials['identifier'];
                return $this->authenticateWithOTP($identifier, $credentials['otp'], 'email');
              case 'phone_otp':
                $identifier = $credentials['identifier'];
                return $this->authenticateWithOTP($identifier, $credentials['otp'], 'phone');
              case 'magic_link':
                return $this->authenticateWithMagicLink($credentials['token']);
            
            case 'google_sso':
                // Google SSO should use handleGoogleCallback instead
                throw new \Exception('Use handleGoogleCallback for Google authentication');
            
            default:
                throw new \Exception('Invalid authentication type');
        }
    }

    /**
     * Authenticate with password
     */
    private function authenticateWithPassword($identifier, $password, $type)
    {
        $user = $type === 'email' 
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (!$user) {
            throw new \Exception('No user found with this ' . $type . '. Please check your ' . $type . ' or register a new account.');
        }

        if (!$user->password) {
            throw new \Exception('This account is set up for passwordless authentication. Please use OTP login instead.');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Exception('Invalid password. Please check your password and try again.');
        }

        // Check if user has this auth method
        $authMethod = $user->authMethods()
            ->where('auth_method_type', $type . '_password')
            ->where('identifier', $identifier)
            ->verified()
            ->active()
            ->first();

        if (!$authMethod) {
            throw new \Exception('Password authentication not configured for this ' . $type . '. Please use your registered authentication method.');
        }

        if (!$user->is_active) {
            throw new \Exception('Your account has been deactivated. Please contact support.');
        }

        return $user;
    }

    /**
     * Authenticate with OTP
     */
    private function authenticateWithOTP($identifier, $otpCode, $type)
    {
        $user = $type === 'email' 
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();

        if (!$user) {
            throw new \Exception('No user found with this ' . $type . '. Please check your ' . $type . ' or register a new account.');
        }

        $otp = $user->otps()
            ->where('identifier', $identifier)
            ->where('type', $type . '_otp')
            ->where('otp_code', $otpCode)
            ->valid()
            ->first();

        if (!$otp) {
            throw new \Exception('Invalid or expired OTP code. Please request a new OTP and try again.');
        }

        if (!$user->is_active) {
            throw new \Exception('Your account has been deactivated. Please contact support.');
        }

        $otp->markAsVerified();
        return $user;
    }    /**
     * Authenticate with magic link
     */
    private function authenticateWithMagicLink($token)
    {
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        
        // Verify the magic link token
        $magicLink = MagicLink::verifyToken($token, $ipAddress, $userAgent);
        
        if (!$magicLink) {
            return false;
        }
        
        // Get the user associated with this magic link
        $user = $magicLink->user;
        
        if (!$user || !$user->is_active) {
            return false;
        }
        
        return $user;
    }/**
     * Send OTP
     */
    public function sendOTP($identifier, $type)
    {
        $user = $type === 'email' 
            ? User::where('email', $identifier)->first()
            : User::where('phone', $identifier)->first();        if (!$user) {
            return ['success' => false, 'message' => 'No user found with this ' . $type . '. Please check your ' . $type . ' or register a new account.'];
        }

        // Check if user has this auth method
        $authMethodType = $type . '_otp';
        $authMethod = $user->authMethods()
            ->where('auth_method_type', $authMethodType)
            ->where('identifier', $identifier)
            ->first();

        if (!$authMethod) {
            return ['success' => false, 'message' => 'OTP authentication not configured for this ' . $type . '. Please register with OTP or add OTP method to your account.'];
        }

        if (!$authMethod->is_active) {
            return ['success' => false, 'message' => 'OTP authentication is disabled for this account'];
        }

        // Invalidate previous OTPs
        $user->otps()
            ->where('identifier', $identifier)
            ->where('type', $authMethodType)
            ->whereNull('verified_at')
            ->delete();

        // Generate new OTP
        $otpCode = Otp::generateCode();
        
        $otp = Otp::create([
            'user_id' => $user->id,
            'identifier' => $identifier,
            'otp_code' => $otpCode,
            'type' => $authMethodType,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP via email or SMS
        $sent = false;
        if ($type === 'email') {
            $sent = $this->sendOTPEmail($identifier, $otpCode);
        } else {
            $sent = $this->sendOTPSMS($identifier, $otpCode);
        }

        if (!$sent) {
            $otp->delete(); // Clean up if sending failed
            return ['success' => false, 'message' => 'Failed to send OTP. Please try again.'];
        }        return ['success' => true, 'message' => 'OTP sent successfully to your ' . $type];
    }    /**
     * Send Magic Link for passwordless authentication
     */
    public function sendMagicLink($email)
    {
        \Log::info("Magic link request for email: {$email}");
        
        // Find user by email
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            \Log::info("No user found for email: {$email}");
            // For security, don't reveal if email exists or not
            return ['success' => true, 'message' => 'If this email is registered, a magic link has been sent.'];
        }
        
        \Log::info("User found: {$user->id}, active: " . ($user->is_active ? 'yes' : 'no'));
        
        if (!$user->is_active) {
            return ['success' => false, 'message' => 'Account is not active'];
        }
        
        // Check if user has email auth method
        $authMethod = $user->authMethods()
            ->whereIn('auth_method_type', ['email_password', 'email_otp'])
            ->where('identifier', $email)
            ->where('is_active', true)
            ->first();
            
        if (!$authMethod) {
            \Log::info("No active email auth method found for user {$user->id} with email {$email}");
            // For security, don't reveal if email exists or not
            return ['success' => true, 'message' => 'If this email is registered, a magic link has been sent.'];
        }
        
        \Log::info("Auth method found: {$authMethod->auth_method_type}");
        
        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();
        
        // Create magic link
        try {
            $magicLink = MagicLink::createForUser($user, $email, $ipAddress, $userAgent);
            \Log::info("Magic link created: {$magicLink->token}");
        } catch (\Exception $e) {
            \Log::error("Failed to create magic link: " . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to create magic link. Please try again.'];
        }
        
        // Send magic link email
        try {
            Mail::to($email)->send(new \App\Mail\MagicLinkMail($user, $magicLink));
            
            \Log::info("Magic link sent to email {$email} for user {$user->id}");
            
            return ['success' => true, 'message' => 'Magic link sent to your email. Please check your inbox.'];
        } catch (\Exception $e) {
            \Log::error("Failed to send magic link email to {$email}: " . $e->getMessage());
            \Log::error("Stack trace: " . $e->getTraceAsString());
            $magicLink->delete(); // Clean up if sending failed
            return ['success' => false, 'message' => 'Failed to send magic link. Please try again.'];
        }
    }/**
     * Send OTP via email
     */
    private function sendOTPEmail($email, $otp)
    {
        try {
            // Send actual email
            Mail::to($email)->send(new OtpMail($otp));
            
            // Also log for development/debugging
            \Log::info("OTP sent to email {$email}: {$otp}");
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email to {$email}: " . $e->getMessage());
            return false;
        }
    }    /**
     * Send OTP via SMS
     */
    private function sendOTPSMS($phone, $otp)
    {        try {
            // Create dedicated SMS OTP log
            $this->logSMSOTPForTesting($phone, $otp);
            
            // Check if we're in testing mode or Twilio is not configured
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.token');
            $from = config('services.twilio.from');
            
            $isTestMode = config('app.env') === 'local' || config('app.env') === 'testing';
            $hasTwilioConfig = $sid && $token && $from && 
                              $sid !== 'your_twilio_sid' && 
                              $token !== 'your_twilio_token';
            
            if ($hasTwilioConfig && !$isTestMode) {
                // Production SMS sending with Twilio
                $twilio = new \Twilio\Rest\Client($sid, $token);
                $message = $twilio->messages->create($phone, [
                    'from' => $from,
                    'body' => "Your OTP code is: {$otp}"
                ]);
                
                \Log::info("SMS sent with Twilio: " . $message->sid);
            } else {
                // Testing mode - just log
                \Log::info("SMS OTP (Testing Mode): Phone {$phone}, OTP: {$otp}");
            }
            
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP SMS to {$phone}: " . $e->getMessage());
            return false;
        }
    }/**
     * Add additional auth method to existing user
     */
    public function addAuthMethod(User $user, $authMethodOrData, $identifier = null)
    {
        // Handle different parameter formats
        if (is_string($authMethodOrData)) {
            // Called with: addAuthMethod($user, $auth_method, $identifier)
            $data = [
                'auth_method_type' => $authMethodOrData,
                'identifier' => $identifier,
            ];
        } else {
            // Called with: addAuthMethod($user, $data)
            $data = $authMethodOrData;
        }

        // Check if method already exists
        $existingMethod = $user->authMethods()
            ->where('auth_method_type', $data['auth_method_type'])
            ->where('identifier', $data['identifier'])
            ->first();

        if ($existingMethod) {
            throw new \Exception('Authentication method already exists for this user');
        }

        // Update user's main table fields if adding phone/email
        $this->updateUserMainFields($user, $data);

        // Create the auth method record
        return $this->createAuthMethod($user, $data);
    }

    /**
     * Update user's main fields when adding new auth methods
     */
    private function updateUserMainFields(User $user, array $data)
    {
        $updateFields = [];

        // If adding phone-based auth method and user doesn't have phone
        if (in_array($data['auth_method_type'], ['phone_password', 'phone_otp'])) {
            if (empty($user->phone) && !empty($data['identifier'])) {
                // Validate phone format
                if (preg_match('/^\+?[1-9]\d{1,14}$/', $data['identifier'])) {
                    $updateFields['phone'] = $data['identifier'];
                }
            }
        }

        // If adding email-based auth method and user doesn't have email
        if (in_array($data['auth_method_type'], ['email_password', 'email_otp'])) {
            if (empty($user->email) && !empty($data['identifier'])) {
                // Validate email format
                if (filter_var($data['identifier'], FILTER_VALIDATE_EMAIL)) {
                    $updateFields['email'] = $data['identifier'];
                }
            }
        }

        // Update user if there are fields to update
        if (!empty($updateFields)) {
            $user->update($updateFields);
        }
    }

    /**
     * Verify auth method
     */
    public function verifyAuthMethod($userId, $authMethodId, $verificationData = [])
    {
        $authMethod = UserAuthMethod::where('user_id', $userId)
            ->where('id', $authMethodId)
            ->first();

        if (!$authMethod) {
            return false;
        }

        $authMethod->markAsVerified();
        return true;
    }    /**
     * Log SMS OTP for testing purposes
     */
    private function logSMSOTPForTesting($phone, $otp)
    {
        try {
            // Create a dedicated SMS OTP log file
            $logFile = storage_path('logs/sms_otp_testing.log');
            $readableFile = storage_path('logs/sms_otp_readable.log');
            $timestamp = now()->format('Y-m-d H:i:s');
            
            $logEntry = [
                'timestamp' => $timestamp,
                'phone' => $phone,
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10)->format('Y-m-d H:i:s'),
                'status' => 'sent_for_testing'
            ];
            
            // Prepare new log lines
            $jsonLogLine = json_encode($logEntry) . PHP_EOL;
            $readableLine = "[{$timestamp}] SMS OTP for {$phone}: {$otp} (expires at " . 
                           now()->addMinutes(10)->format('H:i:s') . ")" . PHP_EOL;
            
            // Read existing content and prepend new entry (newest first)
            $existingJsonContent = file_exists($logFile) ? file_get_contents($logFile) : '';
            $existingReadableContent = file_exists($readableFile) ? file_get_contents($readableFile) : '';
            
            // Write new entry at the beginning
            file_put_contents($logFile, $jsonLogLine . $existingJsonContent, LOCK_EX);
            file_put_contents($readableFile, $readableLine . $existingReadableContent, LOCK_EX);
            
            // Clean up old entries (keep only last 10 entries)
            $this->cleanupOTPTestingLogs($logFile, 10);
            $this->cleanupOTPTestingLogs($readableFile, 10);
            
        } catch (\Exception $e) {
            \Log::error("Failed to log SMS OTP for testing: " . $e->getMessage());
        }
    }    /**
     * Clean up old OTP testing log entries
     */    private function cleanupOTPTestingLogs($logFile, $maxEntries = 50)
    {
        try {
            if (file_exists($logFile)) {
                $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                if (count($lines) > $maxEntries) {
                    // Keep the first $maxEntries lines (newest entries)
                    $keepLines = array_slice($lines, 0, $maxEntries);
                    file_put_contents($logFile, implode(PHP_EOL, $keepLines) . PHP_EOL, LOCK_EX);
                }
            }
        } catch (\Exception $e) {
            \Log::error("Failed to cleanup OTP testing logs: " . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth authentication
     */
    public function handleGoogleAuth($providerUser)
    {
        \Log::info('Google auth attempt', [
            'provider_id' => $providerUser->getId(),
            'email' => $providerUser->getEmail(),
            'name' => $providerUser->getName()
        ]);

        // Check if user already has a Google account linked
        $socialAccount = SocialAccount::findByProvider('google', $providerUser->getId());
        
        if ($socialAccount) {
            // Existing Google account - log them in
            $user = $socialAccount->user;
            
            if (!$user->is_active) {
                throw new \Exception('Your account has been deactivated. Please contact support.');
            }
            
            // Update social account data
            $socialAccount->update([
                'provider_email' => $providerUser->getEmail(),
                'provider_name' => $providerUser->getName(),
                'provider_avatar' => $providerUser->getAvatar(),
                'provider_data' => ['raw' => $providerUser->getRaw()],
            ]);
            
            \Log::info('Existing Google user logged in', ['user_id' => $user->id]);
            return $user;
        }

        // Check if user exists with this email
        $existingUser = User::where('email', $providerUser->getEmail())->first();
        
        if ($existingUser) {
            // Auto-link Google account to existing user
            return $this->linkGoogleToExistingUser($existingUser, $providerUser);
        }

        // Create new user with Google account
        return $this->createUserFromGoogle($providerUser);
    }

    /**
     * Link Google account to existing user
     */
    private function linkGoogleToExistingUser(User $user, $providerUser)
    {
        if (!$user->is_active) {
            throw new \Exception('Your account has been deactivated. Please contact support.');
        }

        // Verify email ownership by checking if user has email auth method
        $hasEmailAuth = $user->authMethods()
            ->whereIn('auth_method_type', ['email_password', 'email_otp'])
            ->where('identifier', $providerUser->getEmail())
            ->where('is_active', true)
            ->exists();

        if (!$hasEmailAuth) {
            throw new \Exception('Unable to link Google account. Email verification required.');
        }

        // Link the Google account
        $user->linkSocialAccount('google', $providerUser);
        
        // Add Google SSO auth method
        $this->addGoogleAuthMethod($user, $providerUser->getEmail());
        
        \Log::info('Google account linked to existing user', [
            'user_id' => $user->id,
            'email' => $providerUser->getEmail()
        ]);
        
        return $user;
    }

    /**
     * Create new user from Google OAuth
     */    private function createUserFromGoogle($providerUser)
    {        // Create user
        $user = User::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            'email_verified_at' => now(), // Google email is already verified
            'primary_auth_method' => 'google_sso',
            'is_active' => true,
        ]);

        // Assign the default "Application User" role
        $this->assignDefaultRole($user);

        // Create social account record
        $user->linkSocialAccount('google', $providerUser);
          // Add Google SSO auth method
        $this->addGoogleAuthMethod($user, $providerUser->getEmail());
        
        \Log::info('New user created from Google', [
            'user_id' => $user->id,
            'email' => $providerUser->getEmail()
        ]);
        
        return $user;
    }

    /**
     * Add Google SSO auth method to user
     */
    private function addGoogleAuthMethod(User $user, $email)
    {        // Check if Google SSO method already exists
        $existingMethod = $user->authMethods()
            ->where('auth_method_type', 'google_sso')
            ->where('identifier', $email)
            ->first();

        if (!$existingMethod) {
            $authMethod = UserAuthMethod::create([
                'user_id' => $user->id,
                'auth_method_type' => 'google_sso',
                'identifier' => $email,
                'is_active' => true,
                'auth_method_verified_at' => now(), // Google accounts are pre-verified
            ]);
            
            \Log::info('Google SSO auth method added', [
                'user_id' => $user->id,
                'auth_method_id' => $authMethod->id
            ]);
        }
    }    /**
     * Get Google OAuth redirect URL
     */
    public function getGoogleRedirectUrl()
    {
        $socialiteDriver = Socialite::driver('google')->scopes(['email', 'profile']);
        
        // For local development, disable SSL verification
        if (app()->environment('local')) {
            $socialiteDriver->setHttpClient(
                new \GuzzleHttp\Client([
                    'verify' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ]
                ])
            );
        }
        
        return $socialiteDriver->redirect()->getTargetUrl();
    }/**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Log the incoming request parameters for debugging
            \Log::info('Google OAuth callback received', [
                'query_params' => request()->query(),
                'all_params' => request()->all(),
                'url' => request()->fullUrl()
            ]);

            // Check if we have the required OAuth parameters
            if (!request()->has('code') && !request()->has('error')) {
                throw new \Exception('Missing OAuth response parameters');
            }

            if (request()->has('error')) {
                $error = request()->get('error');
                $errorDescription = request()->get('error_description', 'No description provided');
                throw new \Exception("OAuth error: {$error} - {$errorDescription}");
            }            // Configure cURL options for local development to handle SSL issues
            $socialiteDriver = Socialite::driver('google');
            
            // For local development, disable SSL verification
            if (app()->environment('local')) {
                $socialiteDriver->setHttpClient(
                    new \GuzzleHttp\Client([
                        'verify' => false,
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ]
                    ])
                );
            }

            $providerUser = $socialiteDriver->user();
            return $this->handleGoogleAuth($providerUser);
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_params' => request()->all()
            ]);
            throw new \Exception('Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Assign the default "Application User" role to a user
     */
    private function assignDefaultRole(User $user)
    {
        // Check if user already has any roles
        if ($user->roles()->count() === 0) {
            $applicationUserRole = Role::where('name', 'Application User')->first();
            if ($applicationUserRole) {
                $user->assignRole($applicationUserRole);
                \Log::info('Default Application User role assigned', [
                    'user_id' => $user->id,
                    'role' => 'Application User'
                ]);
            } else {
                \Log::warning('Application User role not found', [
                    'user_id' => $user->id,
                    'available_roles' => Role::pluck('name')->toArray()
                ]);
            }
        }
    }
}
