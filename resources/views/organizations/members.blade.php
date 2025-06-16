<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $organization->name }} - Members
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Manage organization members
                </p>
            </div>
            <a href="{{ route('organizations.show', $organization) }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Back to Organization
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Add Member Form -->
            @can('update', $organization)
                @if($availableUsers->count() > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Member</h3>
                            <form method="POST" action="{{ route('organizations.members.add', $organization) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Select User
                                    </label>
                                    <x-searchable-select 
                                        name="user_id" 
                                        id="user_id" 
                                        :options="$availableUsers->map(fn($user) => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email])->toArray()" 
                                        placeholder="Search users..."
                                        required="true" />
                                </div>
                                <button type="submit" 
                                        class="btn btn-success w-full">
                                    Add Member
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endcan

            <!-- Members List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Members ({{ $members->total() }})
                        </h3>
                    </div>

                    @if($members->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Member
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Joined
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Groups
                                        </th>
                                        @can('update', $organization)
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($members as $member)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-medium">
                                                            {{ $member->initials() }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $member->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $member->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('M j, Y') : 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                @php
                                                    $userGroups = $member->getGroupsForOrganization($organization)->get();
                                                @endphp
                                                @if($userGroups->count() > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($userGroups as $group)
                                                            <span class="status-badge status-badge-primary">
                                                                {{ $group->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">No groups</span>
                                                @endif
                                            </td>
                                            @can('update', $organization)
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form method="POST" action="{{ route('organizations.members.remove', [$organization, $member]) }}" 
                                                          onsubmit="return confirm('Are you sure you want to remove this member?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"                                                                class="text-error-600 hover:text-error-700 dark:text-error-400 dark:hover:text-error-300">
                                                                Remove
                                                            </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6">
                            {{ $members->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No members</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding members to this organization.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
