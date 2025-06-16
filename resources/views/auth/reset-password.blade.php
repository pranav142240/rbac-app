<x-layouts.auth :title="__('Reset Password')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Reset your password') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Enter your new password below') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <x-forms.input 
                    id="email" 
                    name="email"
                    type="email"
                    label="Email"
                    placeholder="Your email address"
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <!-- Password -->
            <div>
                <x-forms.input 
                    id="password" 
                    name="password"
                    type="password"
                    label="Password"
                    placeholder="Enter your new password"
                    required
                    autocomplete="new-password"
                />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-forms.input 
                    id="password_confirmation" 
                    name="password_confirmation"
                    type="password"
                    label="Confirm Password"
                    placeholder="Re-enter your new password"
                    required
                    autocomplete="new-password"
                />
            </div>

            <div class="flex items-center justify-center">
                <x-button type="submit" class="w-full">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.auth>
