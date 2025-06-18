<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\MultiAuthLoginRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\AddAuthMethodRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MultiAuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }    /**
     * Handle registration
     */    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();
        
        try {
            $result = $this->authService->register($validated);
              // Send verification if needed
            if (in_array($validated['auth_method_type'], ['email_otp', 'phone_otp'])) {
                $type = str_contains($validated['auth_method_type'], 'email') ? 'email' : 'phone';
                $otpResult = $this->authService->sendOTP($validated['identifier'], $type);
                
                if ($otpResult['success']) {
                    return redirect()->route('auth.verify-otp')
                        ->with('message', $otpResult['message'])
                        ->with('identifier', $validated['identifier'])
                        ->with('type', $type);
                } else {
                    return back()->withErrors(['error' => 'Registration successful but failed to send OTP: ' . $otpResult['message']])->withInput();
                }
            }

            Auth::login($result['user']);
            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auth_type' => 'required|in:email_password,phone_password,email_otp,phone_otp',
            'identifier' => 'required',
            'password' => 'required_if:auth_type,email_password,phone_password',
            'otp' => 'required_if:auth_type,email_otp,phone_otp',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = $this->authService->authenticate($request->all());

            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard')
                    ->with('success', 'Login successful! Welcome back, ' . $user->name . '.');
            }

            return back()->withErrors(['identifier' => 'Invalid credentials provided.'])->withInput();

        } catch (\Exception $e) {
            return back()->withErrors(['identifier' => $e->getMessage()])->withInput();
        }
    }    /**
     * Send OTP
     */
    public function sendOtp(Request $request)
    {
        try {
            \Log::info('Send OTP request received', [
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);

            $validator = Validator::make($request->all(), [
                'identifier' => 'required',
                'type' => 'required|in:email,phone',
            ]);

            if ($validator->fails()) {
                \Log::warning('Send OTP validation failed', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            \Log::info('Calling AuthService sendOTP', [
                'identifier' => $request->identifier,
                'type' => $request->type
            ]);

            $result = $this->authService->sendOTP($request->identifier, $request->type);

            \Log::info('AuthService sendOTP result', $result);

            if ($result['success']) {
                return response()->json(['message' => $result['message']]);
            }

            return response()->json(['error' => $result['message']], 400);

        } catch (\Exception $e) {
            \Log::error('Send OTP exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal server error occurred while sending OTP',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }    /**
     * Verify OTP
     */
    public function verifyOtp(VerifyOtpRequest $request)
    {
        $validated = $request->validated();
        
        try {
            \Log::info('OTP verification attempt', [
                'identifier' => $validated['identifier'],
                'type' => $validated['type'],
                'otp' => $validated['otp']
            ]);
            
            $user = $this->authService->authenticate([
                'auth_type' => $validated['type'] . '_otp',
                'identifier' => $validated['identifier'],
                'otp' => $validated['otp'],
            ]);

            if ($user) {
                Auth::login($user);
                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            \Log::error('OTP verification failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return back()->withErrors(['error' => 'Invalid or expired OTP']);
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth.login');
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        $user->load(['authMethods', 'roles', 'organizations', 'organizationGroups.organization']);
        
        $authMethods = $user->authMethods;
        $roles = $user->roles->pluck('name');
        $permissions = $user->getAllPermissions();

        // Calculate available auth methods
        $allAuthMethods = [
            'email_password' => 'Email + Password',
            'email_otp' => 'Email + OTP',
            'phone_password' => 'Phone + Password',
            'phone_otp' => 'Phone + OTP',
            'google_sso' => 'Google SSO'
        ];
        
        $existingMethodTypes = $authMethods->pluck('auth_method_type')->toArray();
        $availableAuthMethods = array_diff_key($allAuthMethods, array_flip($existingMethodTypes));
        $hasAvailableMethods = count($availableAuthMethods) > 0;

        return view('auth.profile', compact(
            'user', 
            'authMethods', 
            'roles', 
            'permissions',
            'availableAuthMethods',
            'hasAvailableMethods'
        ));
    }    /**
     * Add new auth method
     */
    public function addAuthMethod(AddAuthMethodRequest $request)
    {
        \Log::info('Add auth method request', [
            'data' => $request->all(),
            'user' => Auth::id()
        ]);

        try {
            $user = Auth::user();
            $validated = $request->validated();
            
            // For OTP-based methods, verify the OTP before adding the method
            if ($validated['auth_method_type'] === 'email_otp' || $validated['auth_method_type'] === 'phone_otp') {
                $type = str_contains($validated['auth_method_type'], 'email') ? 'email' : 'phone';
                
                // Verify the OTP
                $otpVerified = $this->verifyOTPForAuthMethod($validated['identifier'], $validated['otp'], $type);
                
                if (!$otpVerified) {
                    return response()->json(['error' => 'Invalid or expired OTP code. Please request a new OTP and try again.'], 400);
                }
            }
            
            $authMethod = $this->authService->addAuthMethod($user, $validated);

            // For OTP-based methods, mark as verified since OTP was verified
            // For password-based methods, mark as verified immediately since no OTP verification is needed
            if (in_array($validated['auth_method_type'], ['email_otp', 'phone_otp', 'email_password', 'phone_password'])) {
                $authMethod->markAsVerified();
            }

            \Log::info('Auth method added successfully', [
                'user_id' => $user->id,
                'auth_method_id' => $authMethod->id,
                'auth_method_type' => $authMethod->auth_method_type
            ]);

            return response()->json([
                'message' => 'Authentication method added successfully', 
                'auth_method' => $authMethod,
                'user_updated' => $user->fresh() // Return updated user data
            ]);        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database error adding auth method', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
            
            // Handle specific database constraint violations
            if ($e->getCode() === '23000') {
                if (str_contains($e->getMessage(), 'users_email_unique')) {
                    return response()->json(['error' => 'This email address is already registered with another account.'], 400);
                } elseif (str_contains($e->getMessage(), 'users_phone_unique')) {
                    return response()->json(['error' => 'This phone number is already registered with another account.'], 400);
                } elseif (str_contains($e->getMessage(), 'user_auth_methods')) {
                    return response()->json(['error' => 'This authentication method already exists for your account.'], 400);
                }
            }
            
            return response()->json(['error' => 'Database error occurred. Please try again.'], 500);
            
        } catch (\Exception $e) {
            \Log::error('Failed to add auth method', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Show magic link form
     */
    public function showMagicLinkForm()
    {
        return view('auth.magic-link');
    }    /**
     * Send magic link
     */
    public function sendMagicLink(Request $request)
    {
        \Log::info("Magic link request received", ['email' => $request->email, 'expectsJson' => $request->expectsJson()]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            \Log::info("Magic link validation failed", ['errors' => $validator->errors()->toArray()]);
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $result = $this->authService->sendMagicLink($request->email);
        \Log::info("Magic link service result", $result);

        if ($request->expectsJson()) {
            if ($result['success']) {
                return response()->json(['message' => $result['message']]);
            }
            return response()->json(['error' => $result['message']], 400);
        }

        if ($result['success']) {
            return back()->with('status', $result['message']);
        }

        return back()->withErrors(['email' => $result['message']])->withInput();
    }    /**
     * Verify magic link and authenticate user
     */
    public function verifyMagicLink($token)
    {
        try {
            $user = $this->authService->authenticate([
                'auth_type' => 'magic_link',
                'token' => $token
            ]);            if ($user) {
                // Log the user in
                Auth::login($user);
                
                return redirect()->intended('/dashboard')->with('status', 'Successfully logged in with magic link!');
            }

            return redirect()->route('auth.login')->withErrors(['token' => 'Invalid or expired magic link. Please try again.']);
            
        } catch (\Exception $e) {
            \Log::error('Magic link verification failed: ' . $e->getMessage());            return redirect()->route('auth.login')->withErrors(['token' => 'Magic link verification failed. Please try again.']);
        }
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        try {
            return redirect($this->authService->getGoogleRedirectUrl());
        } catch (\Exception $e) {
            \Log::error('Google OAuth redirect failed: ' . $e->getMessage());
            return redirect()->route('auth.login')->withErrors(['error' => 'Google authentication is temporarily unavailable. Please try again later.']);
        }
    }    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            \Log::info('Google OAuth callback started', [
                'query' => request()->query(),
                'session_id' => session()->getId()
            ]);

            $user = $this->authService->handleGoogleCallback();
            
            if ($user) {
                Auth::login($user);
                
                // Check if this is a new user (just created)
                $isNewUser = $user->created_at->diffInMinutes(now()) < 5;
                
                $message = $isNewUser 
                    ? 'Welcome! Your account has been created and you are now logged in with Google.'
                    : 'Successfully logged in with Google!';
                
                \Log::info('Google OAuth login successful', [
                    'user_id' => $user->id,
                    'is_new_user' => $isNewUser
                ]);
                
                return redirect()->intended('/dashboard')->with('status', $message);
            }
            
            \Log::warning('Google OAuth callback returned no user');
            return redirect()->route('auth.login')->withErrors(['error' => 'Google authentication failed. Please try again.']);
            
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback failed in controller', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => [
                    'query' => request()->query(),
                    'all' => request()->all(),
                    'url' => request()->fullUrl()
                ]
            ]);
            
            // Handle specific error cases
            $errorMessage = $e->getMessage();
            
            if (str_contains($errorMessage, 'deactivated')) {
                return redirect()->route('auth.login')->withErrors(['error' => 'Your account has been deactivated. Please contact support.']);
            }
            
            if (str_contains($errorMessage, 'verification required')) {
                return redirect()->route('auth.login')->withErrors(['error' => 'Unable to link Google account. Please contact support.']);
            }

            if (str_contains($errorMessage, 'OAuth error')) {
                return redirect()->route('auth.login')->withErrors(['error' => 'Google authorization was denied or failed. Please try again.']);
            }
            
            return redirect()->route('auth.login')->withErrors(['error' => 'Google authentication failed. Please try again.']);
        }
    }

    /**
     * Verify an authentication method
     */
    public function verifyAuthMethod(Request $request, $methodId)
    {
        try {
            $user = Auth::user();
            $authMethod = $user->authMethods()->findOrFail($methodId);
            
            // Check if method is already verified
            if ($authMethod->isVerified()) {
                return response()->json(['message' => 'Authentication method is already verified'], 200);
            }

            // Send verification OTP/Email based on method type
            if (str_contains($authMethod->auth_method_type, 'email')) {
                $result = $this->authService->sendOTP($authMethod->identifier, 'email');
            } elseif (str_contains($authMethod->auth_method_type, 'phone')) {
                $result = $this->authService->sendOTP($authMethod->identifier, 'phone');
            } else {
                // For SSO methods, mark as verified immediately
                $authMethod->markAsVerified();
                return response()->json(['message' => 'Authentication method verified successfully']);
            }

            if ($result['success']) {
                return response()->json(['message' => $result['message']]);
            }

            return response()->json(['error' => $result['message']], 400);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send verification: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove an authentication method
     */
    public function removeAuthMethod($methodId)
    {
        try {
            $user = Auth::user();
            $authMethod = $user->authMethods()->findOrFail($methodId);
            
            // Prevent removal if it's the only auth method
            if ($user->authMethods()->count() <= 1) {
                return response()->json(['error' => 'Cannot remove the only authentication method'], 400);
            }

            // If removing the primary method, set another as primary
            if ($user->primary_auth_method === $authMethod->auth_method_type) {
                $nextMethod = $user->authMethods()->where('id', '!=', $methodId)->first();
                if ($nextMethod) {
                    $user->update(['primary_auth_method' => $nextMethod->auth_method_type]);
                }
            }

            $authMethod->delete();

            return response()->json(['message' => 'Authentication method removed successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove authentication method: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Set primary authentication method
     */
    public function setPrimaryAuthMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auth_method_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            
            // Check if user has this auth method
            $authMethod = $user->authMethods()->where('auth_method_type', $request->auth_method_type)->first();
            
            if (!$authMethod) {
                return response()->json(['error' => 'Authentication method not found'], 404);
            }

            // Check if method is verified
            if (!$authMethod->isVerified()) {
                return response()->json(['error' => 'Authentication method must be verified before setting as primary'], 400);
            }

            $user->update(['primary_auth_method' => $request->auth_method_type]);

            return response()->json(['message' => 'Primary authentication method updated successfully']);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update primary authentication method: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|unique:users,phone,' . Auth::id(),
            'primary_auth_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = Auth::user();
            
            // Check if user has the selected primary auth method
            $authMethod = $user->authMethods()->where('auth_method_type', $request->primary_auth_method)->first();
            
            if (!$authMethod) {
                return back()->withErrors(['primary_auth_method' => 'Selected authentication method not found'])->withInput();
            }

            // Check if method is verified
            if (!$authMethod->isVerified()) {
                return back()->withErrors(['primary_auth_method' => 'Authentication method must be verified before setting as primary'])->withInput();
            }

            // Prepare update data
            $updateData = [
                'name' => $request->name,
                'primary_auth_method' => $request->primary_auth_method,
            ];

            // Add email if provided
            if ($request->filled('email') && $request->email !== $user->email) {
                $updateData['email'] = $request->email;
                $updateData['email_verified_at'] = null; // Reset verification status
            }

            // Add phone if provided
            if ($request->filled('phone') && $request->phone !== $user->phone) {
                $updateData['phone'] = $request->phone;
                $updateData['phone_verified_at'] = null; // Reset verification status
            }

            $user->update($updateData);

            $message = 'Profile updated successfully';
            if (isset($updateData['email']) || isset($updateData['phone'])) {
                $message .= '. Please verify your new contact information.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Verify OTP for authentication method addition
     */
    private function verifyOTPForAuthMethod($identifier, $otpCode, $type)
    {
        try {
            // Find user (should be current user)
            $user = Auth::user();
            
            // Check if OTP exists and is valid
            $otp = $user->otps()
                ->where('identifier', $identifier)
                ->where('type', $type . '_otp')
                ->where('otp_code', $otpCode)
                ->valid()
                ->first();

            if (!$otp) {
                return false;
            }

            // Mark OTP as verified
            $otp->markAsVerified();
            return true;
            
        } catch (\Exception $e) {
            \Log::error('OTP verification failed', [
                'identifier' => $identifier,
                'type' => $type,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Send OTP for adding authentication method (for authenticated users)
     */
    public function sendOtpForMethod(Request $request)
    {
        try {
            \Log::info('Send OTP for method request received', [
                'data' => $request->all(),
                'user' => Auth::id()
            ]);

            $validator = Validator::make($request->all(), [
                'identifier' => 'required',
                'type' => 'required|in:email,phone',
            ]);

            if ($validator->fails()) {
                \Log::warning('Send OTP for method validation failed', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = Auth::user();
            $identifier = $request->identifier;
            $type = $request->type;

            // For adding auth methods, we allow sending OTP even if the method doesn't exist yet
            // But we check if the identifier belongs to the current user
            if ($type === 'email' && $user->email && $user->email !== $identifier) {
                return response()->json(['error' => 'You can only add authentication methods for your own email address.'], 400);
            }
            
            if ($type === 'phone' && $user->phone && $user->phone !== $identifier) {
                return response()->json(['error' => 'You can only add authentication methods for your own phone number.'], 400);
            }

            // Use the specialized method for adding auth methods
            $result = $this->authService->sendOTPForAuthMethodAddition($identifier, $type, $user);

            \Log::info('AuthService sendOTPForAuthMethodAddition result', $result);

            if ($result['success']) {
                return response()->json(['message' => $result['message']]);
            }

            return response()->json(['error' => $result['message']], 400);

        } catch (\Exception $e) {
            \Log::error('Send OTP for method exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Internal server error occurred while sending OTP',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
