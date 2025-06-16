<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Organizations') }}
            </h2>
            @can('create', App\Models\Organization::class)
                <a href="{{ route('organizations.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Create Organization
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($organizations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($organizations as $organization)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $organization->name }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $organization->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>

                                    @if($organization->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                            {{ Str::limit($organization->description, 100) }}
                                        </p>
                                    @endif

                                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        <span>{{ $organization->users_count }} members</span>
                                        <span>{{ $organization->organization_groups_count }} groups</span>
                                    </div>

                                    @if($organization->email || $organization->website)
                                        <div class="space-y-1 mb-4">
                                            @if($organization->email)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                    </svg>
                                                    {{ $organization->email }}
                                                </div>
                                            @endif
                                            @if($organization->website)
                                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.56-.5-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.56.5.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.498-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.147.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.03 11H4.083a6.004 6.004 0 002.783 4.118z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <a href="{{ $organization->website }}" target="_blank" class="hover:underline">
                                                        {{ parse_url($organization->website, PHP_URL_HOST) }}
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex space-x-2">
                                        @can('view', $organization)
                                            <a href="{{ route('organizations.show', $organization) }}" 
                                               class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm">
                                                View
                                            </a>
                                        @endcan
                                        @can('update', $organization)
                                            <a href="{{ route('organizations.edit', $organization) }}" 
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded text-sm">
                                                Edit
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $organizations->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-5a2 2 0 00-2-2H8a2 2 0 00-2 2v5m5 0v-3a1 1 0 00-1-1h-1a1 1 0 00-1 1v3"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No organizations</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new organization.</p>
                            @can('create', App\Models\Organization::class)
                                <div class="mt-6">
                                    <a href="{{ route('organizations.create') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        Create Organization
                                    </a>
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
