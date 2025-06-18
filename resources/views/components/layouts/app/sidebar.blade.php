<!-- Sidebar -->
<aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
    class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
    <!-- Sidebar Content -->
    <div class="h-full flex flex-col">
        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">            <ul class="space-y-1 px-2">                <!-- Dashboard -->
                <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon="chart-bar"
                    :active="request()->routeIs('dashboard')">
                    @can('view_admin_dashboard')
                        Admin Dashboard
                    @else
                        User Dashboard
                    @endcan
                </x-layouts.sidebar-link><!-- Auth Profile -->
                <x-layouts.sidebar-link href="{{ route('auth.profile') }}" icon="user-circle"
                    :active="request()->routeIs('auth.profile*')">Auth Profile</x-layouts.sidebar-link><!-- Roles Management -->
                @can('view_roles')
                <x-layouts.sidebar-two-level-link-parent title="Roles" icon="shield"
                    :active="request()->routeIs('roles*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('roles.index') }}" icon="list"
                        :active="request()->routeIs('roles.index')">View Roles</x-layouts.sidebar-two-level-link>
                    @can('create_roles')
                    <x-layouts.sidebar-two-level-link href="{{ route('roles.create') }}" icon="plus"
                        :active="request()->routeIs('roles.create')">Create Role</x-layouts.sidebar-two-level-link>
                    @endcan
                </x-layouts.sidebar-two-level-link-parent>
                @endcan

                <!-- Permissions Management -->
                @can('view_permissions')
                <x-layouts.sidebar-two-level-link-parent title="Permissions" icon="key"
                    :active="request()->routeIs('permissions*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('permissions.index') }}" icon="list"
                        :active="request()->routeIs('permissions.index')">View Permissions</x-layouts.sidebar-two-level-link>
                    
                </x-layouts.sidebar-two-level-link-parent>
                @endcan                  <!-- Organizations Management -->
                @can('viewAny', App\Models\Organization::class)
                <x-layouts.sidebar-two-level-link-parent title="Organizations" icon="office-building"
                    :active="request()->routeIs('organizations*')">                    @can('view_organizations')
                    <x-layouts.sidebar-two-level-link href="{{ route('organizations.index') }}" icon="list"
                        :active="request()->routeIs('organizations.index')">
                        @can('manage_all_organizations')
                            All Organizations
                        @else
                            My Organizations
                        @endcan
                    </x-layouts.sidebar-two-level-link>
                    @endcan
                    
                    @can('create', App\Models\Organization::class)
                    <x-layouts.sidebar-two-level-link href="{{ route('organizations.create') }}" icon="plus"
                        :active="request()->routeIs('organizations.create')">Create Organization</x-layouts.sidebar-two-level-link>
                    @endcan
                    
                    @if(auth()->user()->organizations->count() > 0)
                        @can('view', auth()->user()->organizations->first())
                        <x-layouts.sidebar-two-level-link href="{{ route('organizations.show', auth()->user()->organizations->first()) }}" icon="eye"
                            :active="request()->routeIs('organizations.show', auth()->user()->organizations->first())">View Organization</x-layouts.sidebar-two-level-link>
                        @endcan
                    @endif
                </x-layouts.sidebar-two-level-link-parent>
                @endcan                <!-- Groups Management -->
                @can('view_organization_groups')
                <x-layouts.sidebar-two-level-link-parent title="Groups" icon="user-group"
                    :active="request()->routeIs('organization-groups*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('organization-groups.index') }}" icon="list"
                        :active="request()->routeIs('organization-groups.index')">
                        @if(auth()->user()->hasRole(['Super Admin', 'Admin']))
                            All Groups
                        @else
                            My Groups
                        @endif
                    </x-layouts.sidebar-two-level-link>
                    
                    @if(auth()->user()->organizationGroups->count() > 0)
                        <x-layouts.sidebar-two-level-link href="{{ route('organization-groups.show', auth()->user()->organizationGroups->first()) }}" icon="eye"
                            :active="request()->routeIs('organization-groups.show', auth()->user()->organizationGroups->first())">View Group</x-layouts.sidebar-two-level-link>
                    @endif
                </x-layouts.sidebar-two-level-link-parent>
                @endcan<!-- User Management (Admin) -->
                @can('view_users')
                <x-layouts.sidebar-two-level-link-parent title="User Management" icon="users"
                    :active="request()->routeIs('admin.users*')">
                    <x-layouts.sidebar-two-level-link href="{{ route('admin.users.index') }}" icon="list"
                        :active="request()->routeIs('admin.users.index')">All Users</x-layouts.sidebar-two-level-link>
                    @can('create_users')
                    <x-layouts.sidebar-two-level-link href="{{ route('admin.users.create') }}" icon="plus"
                        :active="request()->routeIs('admin.users.create')">Create User</x-layouts.sidebar-two-level-link>
                    @endcan
                </x-layouts.sidebar-two-level-link-parent>
                @endcan                <!-- Profile Settings -->
                <x-layouts.sidebar-link href="{{ route('profile.edit') }}" icon="cog"
                    :active="request()->routeIs('profile.*')">Profile Settings</x-layouts.sidebar-link>

                

                
            </ul>
        </nav>
    </div>
</aside>
