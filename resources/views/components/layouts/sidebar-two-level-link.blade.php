@props(['active' => false, 'href' => '#', 'icon' => 'circle'])

<a href="{{ $href }}" @class([
    'flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-200',
    'bg-sidebar-accent text-sidebar-accent-foreground font-medium' => $active,
    'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground text-sidebar-foreground' => !$active,
])>
    <div class="flex items-center">
        <x-icon name="{{ $icon }}" class="{{ $active ? 'w-5 h-5 mr-3 text-primary-600 dark:text-primary-400' : 'w-5 h-5 mr-3 text-gray-500 dark:text-gray-400' }}" />
        <span x-data="{}" :class="{ 'opacity-0 hidden': !sidebarOpen }">{{ $slot }}</span>
    </div>
</a>
