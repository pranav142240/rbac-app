<x-layouts.auth :title="__('Forgot Password')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Forgot your password?') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-700">
                    {{ session('status') }}
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
                    :value="old('email')"
                    required
                    autofocus
                />
            </div>

            <div class="flex items-center justify-between">
                <a class="text-sm text-indigo-600 hover:text-indigo-500" href="{{ route('auth.login') }}">
                    {{ __('Back to login') }}
                </a>

                <x-button type="submit">
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.auth>
