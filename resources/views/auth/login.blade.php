<x-layouts.auth :title="__('Login')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                {{ __('Sign in to your account') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Choose your preferred login method
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="rounded-md bg-success-50 p-4">
                <div class="text-sm text-success-700">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="rounded-md bg-error-50 p-4">
                <div class="text-sm text-error-700">
                    <div class="font-medium">{{ __('Whoops! Something went wrong.') }}</div>
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

        <!-- Authentication Method Selector -->
        <div>
            <label for="auth_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Authentication Method') }}
            </label>
            <select id="auth_type" name="auth_type" class="form-input">
                <option value="email_password">Email + Password</option>
                <option value="phone_password">Phone + Password</option>
                <option value="email_otp">Email + OTP</option>
                <option value="phone_otp">Phone + OTP</option>
                <option value="magic_link">Magic Link (Email)</option>
                <option value="google_sso">Google Sign-In</option>
            </select>
        </div>

        <!-- Magic Link Section -->
        <div class="hidden" id="magic_link_section">
            <div class="p-4 bg-gradient-to-r from-primary-50 to-info-50 border border-primary-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <x-icon name="mail" class="w-5 h-5 text-primary-600 mr-2" />
                    <h3 class="text-lg font-medium text-primary-900">✨ Magic Link Login</h3>
                </div>
                <p class="text-primary-700 text-sm mb-4">
                    Enter your email and we'll send you a secure magic link to sign in instantly without a password.
                </p>
                
                <!-- Magic Link Email Input -->
                <div class="mb-4">
                    <x-forms.input 
                        id="magic_email" 
                        name="magic_email"
                        type="email"
                        label="Email Address"
                        placeholder="Enter your email address"
                        :value="old('magic_email')"
                        class="border-primary-300 focus:border-primary-500 focus:ring-primary-500"
                    />
                </div>
                
                <!-- Send Magic Link Button -->
                <x-button id="send_magic_link_btn" class="w-full bg-gradient-to-r from-primary-600 to-info-600 hover:from-primary-700 hover:to-info-700">
                    <x-icon name="lock-closed" class="w-4 h-4 mr-2" />
                    Send Magic Link
                </x-button>
                
                <!-- Magic Link Info -->
                <div class="mt-3 text-xs text-primary-600">
                    <p>• The magic link will expire in 15 minutes</p>
                    <p>• Check your email inbox and spam folder</p>
                    <p>• Click the link to sign in automatically</p>
                </div>
            </div>
        </div>

        <!-- Google Sign-In Section -->
        <div class="hidden" id="google_sso_section">
            <div class="p-4 bg-gradient-to-r from-red-50 to-blue-50 border border-red-200 rounded-lg">
                <div class="flex items-center mb-3">
                    <svg class="w-5 h-5 text-red-600 mr-2" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-red-900">Google Sign-In</h3>
                </div>
                <p class="text-red-700 text-sm mb-4">
                    Sign in quickly and securely with your Google account. No need to remember another password!
                </p>
                
                <!-- Google Sign-In Button -->
                <a href="{{ route('auth.google') }}" 
                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </a>
                
                <!-- Google Sign-In Info -->
                <div class="mt-3 text-xs text-red-600">
                    <p>• Secure OAuth 2.0 authentication</p>
                    <p>• Your Google password is never shared</p>
                    <p>• Automatic account linking for existing users</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.login') }}" id="loginForm" class="mt-8 space-y-6">
            @csrf
            <input type="hidden" name="auth_type" id="auth_type_input" value="email_password">

            <!-- Identifier (Email/Phone) -->
            <div id="identifier_section">
                <x-forms.input 
                    id="identifier" 
                    name="identifier"
                    type="email"
                    label="Email"
                    placeholder="Enter your email address"
                    :value="old('identifier')"
                    required
                    autofocus
                />
            </div>

            <!-- Password Section -->
            <div id="password_section">
                <x-forms.input 
                    id="password" 
                    name="password"
                    type="password"
                    label="Password"
                    placeholder="Enter your password"
                />
            </div>

            <!-- OTP Section -->
            <div class="hidden" id="otp_section">
                <div class="flex gap-2">
                    <div class="flex-1">
                        <x-forms.input 
                            id="otp" 
                            name="otp"
                            type="text"
                            label="OTP Code"
                            placeholder="Enter 6-digit OTP code"
                            maxlength="6"
                        />
                    </div>
                    <div class="flex items-end">
                        <x-button type="button" id="send_otp_btn" variant="secondary" class="px-4 py-2">
                            Send OTP
                        </x-button>
                    </div>
                </div>
            </div>

            <!-- Remember Me -->
            <div id="remember_section">
                <x-forms.checkbox 
                    id="remember_me" 
                    name="remember"
                    label="Remember me"
                />
            </div>

            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button type="submit" class="ml-3">
                    {{ __('Log in') }}
                </x-button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Don't have an account? 
                    <a href="{{ route('auth.register') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Register here</a>
                </p>
            </div>
        </form>        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const authTypeSelect = document.getElementById('auth_type');
                const authTypeInput = document.getElementById('auth_type_input');
                const identifierLabel = document.querySelector('label[for="identifier"]');
                const identifierInput = document.getElementById('identifier');
                const passwordSection = document.getElementById('password_section');
                const otpSection = document.getElementById('otp_section');
                const magicLinkSection = document.getElementById('magic_link_section');
                const googleSsoSection = document.getElementById('google_sso_section');
                const rememberSection = document.getElementById('remember_section');
                const sendOtpBtn = document.getElementById('send_otp_btn');
                const loginForm = document.getElementById('loginForm');

                function updateFormSections() {
                    const authType = authTypeSelect.value;
                    authTypeInput.value = authType;

                    // Hide all sections first
                    if (passwordSection) passwordSection.classList.add('hidden');
                    if (otpSection) otpSection.classList.add('hidden');
                    if (magicLinkSection) magicLinkSection.classList.add('hidden');
                    if (googleSsoSection) googleSsoSection.classList.add('hidden');
                    if (rememberSection) rememberSection.classList.add('hidden');
                    
                    const identifierSection = document.getElementById('identifier_section');
                    if (identifierSection) identifierSection.classList.remove('hidden');
                    if (loginForm) loginForm.classList.remove('hidden');

                    if (authType === 'magic_link') {
                        // Show magic link section and hide login form
                        if (magicLinkSection) magicLinkSection.classList.remove('hidden');
                        if (identifierSection) identifierSection.classList.add('hidden');
                        if (loginForm) loginForm.classList.add('hidden');
                        return;
                    }

                    if (authType === 'google_sso') {
                        // Show Google SSO section and hide login form
                        if (googleSsoSection) googleSsoSection.classList.remove('hidden');
                        if (identifierSection) identifierSection.classList.add('hidden');
                        if (loginForm) loginForm.classList.add('hidden');
                        return;
                    }

                    // Update identifier label and input type
                    if (authType.includes('email')) {
                        if (identifierLabel) identifierLabel.textContent = 'Email';
                        if (identifierInput) {
                            identifierInput.type = 'email';
                            identifierInput.placeholder = 'Enter your email';
                        }
                    } else if (authType.includes('phone')) {
                        if (identifierLabel) identifierLabel.textContent = 'Phone Number';
                        if (identifierInput) {
                            identifierInput.type = 'tel';
                            identifierInput.placeholder = 'Enter your phone number';
                        }
                    }

                    // Show/hide password or OTP sections
                    if (authType.includes('password')) {
                        if (passwordSection) passwordSection.classList.remove('hidden');
                        if (rememberSection) rememberSection.classList.remove('hidden');
                    } else if (authType.includes('otp')) {
                        if (otpSection) otpSection.classList.remove('hidden');
                    }
                }

                // Initialize form on page load
                updateFormSections();

                // Add event listener for dropdown changes
                authTypeSelect.addEventListener('change', updateFormSections);

                // Send OTP button functionality
                if (sendOtpBtn) {
                    sendOtpBtn.addEventListener('click', function() {
                        const identifier = identifierInput ? identifierInput.value : '';
                        const type = authTypeInput.value.includes('email') ? 'email' : 'phone';

                        if (!identifier) {
                            alert('Please enter your ' + type + ' first');
                            return;
                        }

                        this.disabled = true;
                        this.textContent = 'Sending...';

                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            this.disabled = false;
                            this.textContent = 'Send OTP';
                            return;
                        }

                        fetch('{{ route("auth.send-otp") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                identifier: identifier,
                                type: type
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            ok: response.ok,
                            data: data
                        })))
                        .then(result => {
                            // Remove any existing messages
                            const existingMessages = document.querySelectorAll('.otp-message');
                            existingMessages.forEach(msg => msg.remove());

                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'otp-message mt-2 p-3 rounded text-sm';
                            
                            if (result.ok && result.data.message) {
                                messageDiv.className += ' alert alert-success';
                                messageDiv.textContent = result.data.message;
                            } else if (!result.ok && result.data.error) {
                                messageDiv.className += ' alert alert-error';
                                messageDiv.textContent = result.data.error;
                            } else {
                                messageDiv.className += ' alert alert-error';
                                messageDiv.textContent = 'Failed to send OTP';
                            }
                            
                            const otpSectionElement = document.getElementById('otp_section');
                            if (otpSectionElement) {
                                otpSectionElement.appendChild(messageDiv);
                            }
                        })
                        .catch(error => {
                            console.error('Network Error:', error);
                            
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'otp-message mt-2 p-3 rounded text-sm alert alert-error';
                            messageDiv.textContent = 'Network error while sending OTP. Please check your connection and try again.';
                            
                            const otpSectionElement = document.getElementById('otp_section');
                            if (otpSectionElement) {
                                otpSectionElement.appendChild(messageDiv);
                            }
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.textContent = 'Send OTP';
                        });
                    });
                }

                // Send Magic Link button functionality
                const sendMagicLinkBtn = document.getElementById('send_magic_link_btn');
                if (sendMagicLinkBtn) {
                    sendMagicLinkBtn.addEventListener('click', function() {
                        const magicEmail = document.getElementById('magic_email');
                        const email = magicEmail ? magicEmail.value : '';

                        if (!email) {
                            alert('Please enter your email address first');
                            return;
                        }

                        if (!email.includes('@')) {
                            alert('Please enter a valid email address');
                            return;
                        }

                        this.disabled = true;
                        this.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending Magic Link...
                        `;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            this.disabled = false;
                            this.innerHTML = 'Send Magic Link';
                            return;
                        }

                        fetch('{{ route("auth.send-magic-link") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                email: email
                            })
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            ok: response.ok,
                            data: data
                        })))
                        .then(result => {
                            // Remove any existing messages
                            const existingMessages = document.querySelectorAll('.magic-link-message');
                            existingMessages.forEach(msg => msg.remove());

                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'magic-link-message mt-3 p-3 rounded text-sm';
                            
                            if (result.ok && result.data.message) {
                                messageDiv.className += ' alert alert-success';
                                messageDiv.innerHTML = `
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        ${result.data.message}
                                    </div>
                                `;
                            } else if (!result.ok && result.data.error) {
                                messageDiv.className += ' alert alert-error';
                                messageDiv.innerHTML = `
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        ${result.data.error}
                                    </div>
                                `;
                            } else {
                                messageDiv.className += ' alert alert-error';
                                messageDiv.innerHTML = 'Failed to send magic link';
                            }
                            
                            const magicLinkSectionElement = document.getElementById('magic_link_section');
                            if (magicLinkSectionElement) {
                                magicLinkSectionElement.appendChild(messageDiv);
                            }
                        })
                        .catch(error => {
                            console.error('Network Error:', error);
                            
                            const messageDiv = document.createElement('div');
                            messageDiv.className = 'magic-link-message mt-3 p-3 rounded text-sm alert alert-error';
                            messageDiv.innerHTML = 'Network error while sending magic link. Please check your connection and try again.';
                            
                            const magicLinkSectionElement = document.getElementById('magic_link_section');
                            if (magicLinkSectionElement) {
                                magicLinkSectionElement.appendChild(messageDiv);
                            }
                        })
                        .finally(() => {
                            this.disabled = false;
                            this.innerHTML = 'Send Magic Link';
                        });
                    });
                }
            });
        </script>
    </div>
</x-layouts.auth>
