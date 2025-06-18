<x-layouts.auth :title="__('Forgot Password')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-gray-100">
                {{ __('Forgot your password?') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
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

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-forms.input 
                    id="email" 
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="Enter your email address"
                    :value="old('email')"
                    required
                    autofocus
                />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300" href="{{ route('auth.login') }}">
                    {{ __('Back to login') }}
                </a>

                <x-button type="submit" class="ml-3">
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Remember your password? 
                    <a href="{{ route('auth.login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">Sign in here</a>
                </p>
            </div>
        </form>
    </div>
</x-layouts.auth>
