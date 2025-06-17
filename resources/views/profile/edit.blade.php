<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Profile Settings') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Manage your account settings and preferences.') }}
                </p>
            </div>            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
