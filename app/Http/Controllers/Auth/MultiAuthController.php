<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
     */    public function register(Request $request)
    {
        // Build validation rules dynamically based on auth method
        $rules = [
            'name' => 'required|string|max:255',
            'auth_method_type' => 'required|in:email_password,email_otp,phone_password,phone_otp,google_sso',
        ];

        $authMethod = $request->auth_method_type;

        // Add email validation only for email-based methods
        if (in_array($authMethod, ['email_password', 'email_otp', 'google_sso'])) {
            $rules['email'] = 'required|email|unique:users';
        }

        // Add phone validation only for phone-based methods
        if (in_array($authMethod, ['phone_password', 'phone_otp'])) {
            $rules['phone'] = 'required|unique:users';
        }

        // Add password validation only for password-based methods
        if (in_array($authMethod, ['email_password', 'phone_password'])) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->all();
            $data['identifier'] = $data['email'] ?? $data['phone'];
            
            $result = $this->authService->register($data);
              // Send verification if needed
            if (in_array($data['auth_method_type'], ['email_otp', 'phone_otp'])) {
                $type = str_contains($data['auth_method_type'], 'email') ? 'email' : 'phone';
                $otpResult = $this->authService->sendOTP($data['identifier'], $type);
                
                if ($otpResult['success']) {
                    return redirect()->route('auth.verify-otp')
                        ->with('message', $otpResult['message'])
                        ->with('identifier', $data['identifier'])
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
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required',
            'otp' => 'required',
            'type' => 'required|in:email,phone',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            \Log::info('OTP verification attempt', [
                'identifier' => $request->identifier,
                'type' => $request->type,
                'otp' => $request->otp
            ]);
            
            $user = $this->authService->authenticate([
                'auth_type' => $request->type . '_otp',
                'identifier' => $request->identifier,
                'otp' => $request->otp,
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
    public function profile()    {
        $user = Auth::user();
        $authMethods = $user->authMethods;

        return view('auth.profile', compact('user', 'authMethods'));
    }    /**
     * Add new auth method
     */
    public function addAuthMethod(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'auth_method_type' => 'required|in:email_password,email_otp,phone_password,phone_otp,google_sso',
            'identifier' => 'required',
            'password' => 'required_if:auth_method_type,email_password,phone_password|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = Auth::user();
            $authMethod = $this->authService->addAuthMethod($user, $request->all());

            return response()->json([
                'message' => 'Auth method added successfully', 
                'auth_method' => $authMethod,
                'user_updated' => $user->fresh() // Return updated user data
            ]);        } catch (\Exception $e) {
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
}
