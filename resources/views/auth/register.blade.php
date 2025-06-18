<x-layouts.auth :title="__('Register')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                {{ __('Create your account') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Choose your preferred registration method
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

        <!-- Quick Registration Options -->
        <div class="space-y-4">
            <!-- Google Sign-Up Button -->
            <a href="{{ route('auth.google') }}" 
                class="w-full inline-flex items-center justify-center px-4 py-3 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            </a>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">OR register manually</span>
            </div>
        </div>

        <form method="POST" action="{{ route('auth.register.post') }}" id="registerForm" class="mt-8 space-y-6">
            @csrf

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

            <!-- Email -->
            <div>
                <x-forms.input 
                    id="email" 
                    name="email"
                    type="email"
                    label="Email Address"
                    placeholder="Enter your email address"
                    :value="old('email')"
                    required
                />
            </div>

            <!-- Phone (Optional) -->
            <div>
                <x-forms.input 
                    id="phone" 
                    name="phone"
                    type="tel"
                    label="Phone Number (Optional)"
                    placeholder="Enter your phone number (e.g., +1234567890)"
                    :value="old('phone')"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">You can add this later if you prefer to skip it now</p>
            </div>

            <!-- Password Section -->
            <div>
                <div>
                    <x-forms.input 
                        id="password" 
                        name="password"
                        type="password"
                        label="Password"
                        placeholder="Enter a strong password"
                        required
                    />
                </div>

                <div class="mt-4">
                    <x-forms.input 
                        id="password_confirmation" 
                        name="password_confirmation"
                        type="password"
                        label="Confirm Password"
                        placeholder="Re-enter your password"
                        required
                    />
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('auth.login') }}">
                    {{ __('Already have an account?') }}
                </a>

                <x-button type="submit" class="ml-3">
                    {{ __('Register') }}
                </x-button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Already have an account? 
                    <a href="{{ route('auth.login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Sign in here</a>
                </p>
            </div>
        </form>    </div>
</x-layouts.auth>
