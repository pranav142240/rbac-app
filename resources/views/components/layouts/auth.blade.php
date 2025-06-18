<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Authentication' }} - {{ config('app.name') }}</title>
    @vite('resources/css/app.css')    <script>
        // Theme initialization - must be inline and immediate
        (function() {
            function applyTheme() {
                const theme = localStorage.getItem('theme');
                const html = document.documentElement;
                
                // If no preference stored, use system preference
                const isDark = theme === 'dark' || 
                             (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches);
                
                if (isDark) {
                    html.classList.add('dark');
                } else {
                    html.classList.remove('dark');
                }
            }
            
            // Apply theme immediately
            applyTheme();
            
            // Listen for system theme changes (only if no manual preference is set)
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (!localStorage.getItem('theme')) {
                    applyTheme();
                }
            });
        })();
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">

    <div class="min-h-screen flex flex-col">
        <!-- Theme Toggle (Top Right) -->
        <div class="absolute top-4 right-4 z-10">
            <x-theme-toggle size="md" />
        </div>
        
        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
