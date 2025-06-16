<x-layouts.auth :title="__('Confirm Password')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Confirm your password') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="mt-8 space-y-6">
            @csrf

            <!-- Password -->
            <div>
                <x-forms.input 
                    id="password" 
                    name="password"
                    type="password"
                    label="Password"
                    required
                    autocomplete="current-password"
                />
            </div>

            <div class="flex items-center justify-center">
                <x-button type="submit" class="w-full">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.auth>
