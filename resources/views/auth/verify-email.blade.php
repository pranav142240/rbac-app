<x-layouts.auth :title="__('Verify Email')">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                {{ __('Verify your email address') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="rounded-md bg-green-50 p-4">
                <div class="text-sm text-green-700">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            </div>
        @endif

        <div class="flex items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-button type="submit">
                    {{ __('Resend Verification Email') }}
                </x-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-indigo-600 hover:text-indigo-500 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
