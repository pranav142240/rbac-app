@props([
    'name',
    'id' => null,
    'options' => [],
    'selected' => null,
    'placeholder' => 'Search...',
    'required' => false,
    'displayKey' => 'name',
    'valueKey' => 'id',
    'searchable' => true,
    'maxHeight' => 'max-h-48'
])

@php
    $id = $id ?? $name;
@endphp

<div class="relative" x-data="searchableSelect({{ json_encode([
    'options' => $options,
    'selected' => $selected,
    'displayKey' => $displayKey,
    'valueKey' => $valueKey,
    'searchable' => $searchable
]) }})" @click.away="open = false">
    <!-- Hidden input for form submission -->
    <input type="hidden" name="{{ $name }}" x-model="selectedValue" {{ $required ? 'required' : '' }}>
    
    <!-- Display/Trigger Button -->
    <button type="button" 
            class="form-input cursor-pointer text-left flex items-center justify-between w-full"
            @click="open = !open"
            :class="{ 'ring-2 ring-primary-500': open }">
        <span x-text="selectedText || '{{ $placeholder }}'" 
              :class="{ 'text-gray-500': !selectedText }"></span>
        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" 
             :class="{ 'rotate-180': open }" 
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    
    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md shadow-lg"
         style="display: none;">
        
        @if($searchable)
            <!-- Search Input -->
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input type="text" 
                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="Search..."
                       x-model="searchQuery"
                       @input="filterOptions">
            </div>
        @endif
        
        <!-- Options Container -->
        <div class="{{ $maxHeight }} overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option.{{ $valueKey }}">
                <button type="button"
                        class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                        :class="{ 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400': selectedValue == option.{{ $valueKey }} }"
                        @click="selectOption(option)">
                    
                    <!-- Avatar if email exists -->
                    <template x-if="option.email">
                        <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3 text-xs font-medium text-primary-600 dark:text-primary-400">
                            <span x-text="getInitials(option.{{ $displayKey }})"></span>
                        </div>
                    </template>
                    
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-gray-100" x-text="option.{{ $displayKey }}"></div>
                        <template x-if="option.email">
                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="option.email"></div>
                        </template>
                    </div>
                    
                    <!-- Checkmark for selected -->
                    <template x-if="selectedValue == option.{{ $valueKey }}">
                        <svg class="h-4 w-4 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </template>
                </button>
            </template>
            
            <!-- No results message -->
            <div x-show="filteredOptions.length === 0 && searchQuery" 
                 class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                No results found
            </div>
        </div>
    </div>
</div>

<script>
function searchableSelect(config) {
    return {
        open: false,
        searchQuery: '',
        options: config.options || [],
        filteredOptions: config.options || [],
        selectedValue: config.selected || '',
        selectedText: '',
        displayKey: config.displayKey || 'name',
        valueKey: config.valueKey || 'id',
        searchable: config.searchable !== false,
        
        init() {
            this.updateSelectedText();
            this.filteredOptions = [...this.options];
        },
        
        selectOption(option) {
            this.selectedValue = option[this.valueKey];
            this.updateSelectedText();
            this.open = false;
            this.searchQuery = '';
            this.filteredOptions = [...this.options];
        },
        
        updateSelectedText() {
            const selected = this.options.find(option => option[this.valueKey] == this.selectedValue);
            this.selectedText = selected ? selected[this.displayKey] : '';
        },
        
        filterOptions() {
            if (!this.searchQuery) {
                this.filteredOptions = [...this.options];
                return;
            }
            
            const query = this.searchQuery.toLowerCase();
            this.filteredOptions = this.options.filter(option => {
                const name = option[this.displayKey].toLowerCase();
                const email = option.email ? option.email.toLowerCase() : '';
                return name.includes(query) || email.includes(query);
            });
        },
        
        getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        }
    }
}
</script>
