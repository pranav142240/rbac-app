<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $organization->name }} - Groups
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Manage organization groups
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $organization)
                    <a href="{{ route('organizations.groups.create', $organization) }}" 
                       class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Create Group
                    </a>
                @endcan
                <a href="{{ route('organizations.show', $organization) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Organization
                </a>
            </div>
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
                    @if($groups->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($groups as $group)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-4">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                            {{ $group->name }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>

                                    @if($group->description)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                                            {{ Str::limit($group->description, 100) }}
                                        </p>
                                    @endif

                                    <div class="flex justify-between items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                                        <span>{{ $group->users_count }} members</span>
                                        <span>Created {{ $group->created_at->diffForHumans() }}</span>
                                    </div>

                                    <div class="flex space-x-2">
                                        @can('view', $organization)
                                            <a href="{{ route('organizations.groups.show', [$organization, $group]) }}" 
                                               class="flex-1 bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm">
                                                View
                                            </a>
                                        @endcan
                                        @can('update', $organization)
                                            <a href="{{ route('organizations.groups.edit', [$organization, $group]) }}" 
                                               class="flex-1 bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded text-sm">
                                                Edit
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $groups->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No groups</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new group for this organization.</p>
                            @can('update', $organization)
                                <div class="mt-6">
                                    <a href="{{ route('organizations.groups.create', $organization) }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        Create Group
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
