@props(['label', 'name', 'value' => null])

<label for="{{ $name }}"
    {{ $attributes->merge(['class' => 'flex items-center text-sm text-gray-700 dark:text-gray-300']) }}>
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" id="{{ $name }}" name="{{ $name }}" value="{{ $value ?? 1 }}"
        class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded mr-2">
    {{ $label }}
</label>

@error($name)
    <span class="text-error-500 text-sm mt-1">{{ $message }}</span>
@enderror
