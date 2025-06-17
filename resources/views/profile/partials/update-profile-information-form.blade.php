<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-forms.input 
                id="name" 
                name="name"
                type="text"
                label="Name"
                placeholder="Enter your full name"
                :value="old('name', $user->name)"
                required
                autofocus
                autocomplete="name"
            />
        </div>

        <div>
            <x-forms.input 
                id="email" 
                name="email"
                type="email"
                label="Email"
                placeholder="Enter your email address"
                :value="old('email', $user->email)"
                required
                autocomplete="username"
            />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-gray-800 dark:text-gray-200">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-success-600 dark:text-success-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-forms.input 
                id="phone" 
                name="phone"
                type="tel"
                label="Phone Number"
                placeholder="Enter your phone number (e.g., +1234567890)"
                :value="old('phone', $user->phone)"
                autocomplete="tel"
            />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                {{ __('Include country code for international numbers (e.g., +1 for US, +44 for UK)') }}
            </p>
        </div>

        <div class="flex items-center gap-4">
            <x-button type="submit">{{ __('Save') }}</x-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ session('message', __('Saved.')) }}</p>
            @endif
        </div>
    </form>
</section>
