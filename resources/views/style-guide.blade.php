<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    UI Style Guide
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Professional design system showcase
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Color Palette -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Color System</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consistent color palette across the application</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                        <!-- Primary -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary-600 rounded-lg mx-auto mb-2"></div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Primary</h4>
                            <p class="text-xs text-gray-500">Indigo</p>
                        </div>
                        
                        <!-- Success -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-success-600 rounded-lg mx-auto mb-2"></div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Success</h4>
                            <p class="text-xs text-gray-500">Emerald</p>
                        </div>
                        
                        <!-- Warning -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-warning-600 rounded-lg mx-auto mb-2"></div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Warning</h4>
                            <p class="text-xs text-gray-500">Amber</p>
                        </div>
                        
                        <!-- Error -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-error-600 rounded-lg mx-auto mb-2"></div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Error</h4>
                            <p class="text-xs text-gray-500">Rose</p>
                        </div>
                        
                        <!-- Info -->
                        <div class="text-center">
                            <div class="w-16 h-16 bg-info-600 rounded-lg mx-auto mb-2"></div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Info</h4>
                            <p class="text-xs text-gray-500">Sky</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Button System</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consistent button styling with proper contrast</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Primary Actions</h4>
                            <button class="btn btn-primary w-full">Primary Button</button>
                            <button class="btn btn-success w-full">Success Button</button>
                        </div>
                        
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Secondary Actions</h4>
                            <button class="btn btn-secondary w-full">Secondary Button</button>
                            <button class="btn btn-warning w-full">Warning Button</button>
                        </div>
                        
                        <div class="space-y-3">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Destructive Actions</h4>
                            <button class="btn btn-danger w-full">Danger Button</button>
                            <button class="btn btn-info w-full">Info Button</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Badges -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Status Badges</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Semantic status indicators</p>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-3">
                        <span class="status-badge status-badge-success">Active</span>
                        <span class="status-badge status-badge-warning">Pending</span>
                        <span class="status-badge status-badge-error">Inactive</span>
                        <span class="status-badge status-badge-info">Processing</span>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Alert System</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consistent feedback messages</p>
                </div>
                <div class="p-6 space-y-4">
                    <div class="alert alert-success">
                        <strong>Success!</strong> Your changes have been saved successfully.
                    </div>
                    
                    <div class="alert alert-warning">
                        <strong>Warning!</strong> Please review your input before proceeding.
                    </div>
                    
                    <div class="alert alert-error">
                        <strong>Error!</strong> There was a problem processing your request.
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>Info!</strong> This feature is currently in beta testing.
                    </div>
                </div>
            </div>

            <!-- Forms -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Form Elements</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Consistent form styling with proper focus states</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Text Input
                            </label>
                            <input type="text" class="form-input" placeholder="Enter text here...">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Dropdown
                            </label>
                            <select class="form-input">
                                <option>Choose an option...</option>
                                <option>Option 1</option>
                                <option>Option 2</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Checkbox Options
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Option 1</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500" checked>
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Option 2</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Improvements Summary -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">UI Improvements Summary</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Professional design system implementation</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3">✅ Completed</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li>• Unified color system (Indigo, Emerald, Amber, Rose, Sky)</li>
                                <li>• Consistent button styling with proper hover states</li>
                                <li>• Searchable dropdown component for user selection</li>
                                <li>• Professional table designs with stats cards</li>
                                <li>• Semantic status badges and alerts</li>
                                <li>• Form consistency across all pages</li>
                                <li>• Improved error message styling</li>
                                <li>• Modern card-based layouts</li>
                                <li>• Better pagination component</li>
                                <li>• Dark mode compatibility</li>
                            </ul>
                        </div>
                        
                        <div>
                            <h4 class="text-base font-medium text-gray-900 dark:text-gray-100 mb-3">🎯 Key Features</h4>
                            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                <li>• <strong>Searchable User Selection:</strong> No more long dropdown lists</li>
                                <li>• <strong>Professional Tables:</strong> Stats, avatars, and modern layouts</li>
                                <li>• <strong>Consistent Colors:</strong> No more scattered blue/green/yellow</li>
                                <li>• <strong>Better UX:</strong> Clear hover states and focus indicators</li>
                                <li>• <strong>Accessibility:</strong> Proper contrast ratios and ARIA labels</li>
                                <li>• <strong>Responsive Design:</strong> Works on all screen sizes</li>
                                <li>• <strong>Performance:</strong> Optimized CSS with semantic classes</li>
                                <li>• <strong>Maintainability:</strong> Component-based architecture</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
