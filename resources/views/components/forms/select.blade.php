@props([
    'label',
    'name',
    'placeholder' => '',
    'error' => false,
    'class' => '',
    'labelClass' => '',
    'id' => null,
    'required' => false,
])

@php
    $selectId = $id ?? $name;
@endphp

@if ($label)
    <label for="{{ $selectId }}"
        {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ' . $labelClass]) }}>
        {{ $label }}
        @if($required)
            <span class="text-error-500">*</span>
        @endif
    </label>
@endif

<select id="{{ $selectId }}" 
        name="{{ $name }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'form-input ' . $class])->except(['id', 'required']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif
    {{ $slot }}
</select>

@error($name)
    <span class="text-error-500 text-sm mt-1">{{ $message }}</span>
@enderror
