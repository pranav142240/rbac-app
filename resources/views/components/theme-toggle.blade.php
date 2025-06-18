@props(['size' => 'md'])

@php
$sizeClasses = [
    'sm' => 'h-8 w-8',
    'md' => 'h-10 w-10',
    'lg' => 'h-12 w-12'
];
$iconSize = [
    'sm' => 'h-4 w-4',
    'md' => 'h-5 w-5',
    'lg' => 'h-6 w-6'
];
@endphp

<div x-data="themeToggle()" class="relative">
    <button 
        @click="toggleTheme()" 
        class="{{ $sizeClasses[$size] }} rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
        :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        aria-label="Toggle theme"
    >
        <!-- Sun Icon (Light Mode) -->
        <svg x-show="!isDark" class="{{ $iconSize[$size] }} text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        
        <!-- Moon Icon (Dark Mode) -->
        <svg x-show="isDark" class="{{ $iconSize[$size] }} text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
        </svg>
    </button>
</div>

<script>
function themeToggle() {
    return {
        isDark: localStorage.getItem('theme') === 'dark' || 
               (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        init() {
            this.applyTheme();
        },
        
        toggleTheme() {
            this.isDark = !this.isDark;
            localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
            this.applyTheme();
        },
        
        applyTheme() {
            const html = document.documentElement;
            
            if (this.isDark) {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
        }
    }
}
</script>
