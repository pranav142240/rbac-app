<x-layouts.auth :title="__('Register')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                {{ __('Create your account') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Register with your preferred authentication method
            </p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="rounded-md bg-error-50 p-4">
                <div class="text-sm text-error-700">
                    <div class="font-medium">{{ __('Please fix the following errors:') }}</div>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Success Messages -->
        @if (session('success'))
            <div class="rounded-md bg-success-50 p-4">
                <div class="text-sm text-success-700">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        <!-- Info Messages -->
        @if (session('message'))
            <div class="rounded-md bg-info-50 p-4">
                <div class="text-sm text-info-700">
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <!-- Google Sign-Up Option -->
        <div class="p-4 bg-gradient-to-r from-red-50 to-blue-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-3">
                <svg class="w-5 h-5 text-red-600 mr-2" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <h3 class="text-lg font-medium text-red-900">Quick Sign-Up with Google</h3>
            </div>
            <p class="text-red-700 text-sm mb-4">
                Sign up instantly with your Google account. Fast, secure, and no forms to fill!
            </p>
            
            <!-- Google Sign-Up Button -->
            <a href="{{ route('auth.google') }}" 
                class="w-full inline-flex items-center justify-center px-4 py-3 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign Up with Google
            </a>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">Or register manually</span>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.register.post') }}" id="registerForm" class="mt-8 space-y-6">
            @csrf

            <!-- Authentication Method Selector -->
            <div>
                <label for="auth_method_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ __('Primary Authentication Method') }}
                </label>
                <select id="auth_method_type" name="auth_method_type" class="form-input" required>
                    <option value="">Select authentication method</option>
                    <option value="email_password" {{ old('auth_method_type') == 'email_password' ? 'selected' : '' }}>Email + Password</option>
                    <option value="phone_password" {{ old('auth_method_type') == 'phone_password' ? 'selected' : '' }}>Phone + Password</option>
                    <option value="email_otp" {{ old('auth_method_type') == 'email_otp' ? 'selected' : '' }}>Email + OTP (Passwordless)</option>
                    <option value="phone_otp" {{ old('auth_method_type') == 'phone_otp' ? 'selected' : '' }}>Phone + OTP (Passwordless)</option>
                </select>
                @error('auth_method_type')
                    <p class="mt-2 text-sm text-error-600">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can add additional authentication methods later from your profile</p>
            </div>

            <!-- Name -->
            <div>
                <x-forms.input 
                    id="name" 
                    name="name"
                    type="text"
                    label="Full Name"
                    placeholder="Enter your full name"
                    :value="old('name')"
                    required
                    autofocus
                />
            </div>

            <!-- Email Field -->
            <div id="email_section">
                <x-forms.input 
                    id="email" 
                    name="email"
                    type="email"
                    label="Email Address"
                    placeholder="Enter your email address"
                    :value="old('email')"
                />
            </div>

            <!-- Phone Field -->
            <div class="hidden" id="phone_section">
                <x-forms.input 
                    id="phone" 
                    name="phone"
                    type="tel"
                    label="Phone Number"
                    placeholder="Enter your phone number (e.g., +1234567890)"
                    :value="old('phone')"
                />
            </div>

            <!-- Password Fields -->
            <div id="password_fields">
                <div>
                    <x-forms.input 
                        id="password" 
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Enter a strong password"
                    />
                </div>

                <div>
                    <x-forms.input 
                        id="password_confirmation" 
                        name="password_confirmation"
                        type="password"
                        label="Confirm Password"
                        placeholder="Re-enter your password"
                    />
                </div>
            </div>

            <!-- Info for OTP methods -->
            <div class="hidden bg-info-50 p-3 rounded-md" id="otp_info">
                <p class="text-sm text-info-800">
                    <strong>Passwordless Authentication:</strong> You'll receive an OTP code each time you log in. No password required!
                </p>
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('auth.login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button type="submit" class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const authMethodSelect = document.getElementById('auth_method_type');
                const emailSection = document.getElementById('email_section');
                const phoneSection = document.getElementById('phone_section');
                const passwordFields = document.getElementById('password_fields');
                const otpInfo = document.getElementById('otp_info');
                const emailInput = document.getElementById('email');
                const phoneInput = document.getElementById('phone');
                const passwordInput = document.getElementById('password');
                const passwordConfirmInput = document.getElementById('password_confirmation');

                authMethodSelect.addEventListener('change', function() {
                    const authMethod = this.value;

                    // Reset all fields
                    emailSection.classList.add('hidden');
                    phoneSection.classList.add('hidden');
                    passwordFields.classList.add('hidden');
                    otpInfo.classList.add('hidden');

                    // Clear validation requirements and disable inputs
                    emailInput.removeAttribute('required');
                    phoneInput.removeAttribute('required');
                    passwordInput.removeAttribute('required');
                    passwordConfirmInput.removeAttribute('required');
                    
                    // Disable all inputs initially
                    emailInput.disabled = true;
                    phoneInput.disabled = true;
                    passwordInput.disabled = true;
                    passwordConfirmInput.disabled = true;

                    if (authMethod) {
                        if (authMethod.includes('email')) {
                            emailSection.classList.remove('hidden');
                            emailInput.setAttribute('required', '');
                            emailInput.disabled = false;
                        } else if (authMethod.includes('phone')) {
                            phoneSection.classList.remove('hidden');
                            phoneInput.setAttribute('required', '');
                            phoneInput.disabled = false;
                        }

                        if (authMethod.includes('password')) {
                            passwordFields.classList.remove('hidden');
                            passwordInput.setAttribute('required', '');
                            passwordConfirmInput.setAttribute('required', '');
                            passwordInput.disabled = false;
                            passwordConfirmInput.disabled = false;
                        } else if (authMethod.includes('otp')) {
                            otpInfo.classList.remove('hidden');
                        }
                    }
                });

                // Trigger change event on page load if value is selected
                if (authMethodSelect.value) {
                    authMethodSelect.dispatchEvent(new Event('change'));
                }
            });
        </script>
    </div>
</x-layouts.auth>
