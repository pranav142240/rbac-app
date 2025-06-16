@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
    'error' => false,
    'class' => '',
    'labelClass' => '',
    'id' => null,
    'value' => '',
])

@php
    $inputId = $id ?? $name;
@endphp

@if ($label)
    <label for="{{ $inputId }}"
        {{ $attributes->merge(['class' => 'block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 ' . $labelClass]) }}>
        {{ $label }}
    </label>
@endif

<input type="{{ $type }}" 
       id="{{ $inputId }}" 
       name="{{ $name }}"
       placeholder="{{ $placeholder }}" 
       value="{{ $value }}"
       {{ $attributes->merge(['class' => 'form-input ' . $class])->except(['id', 'value']) }}>

@error($name)
    <span class="text-error-500 text-sm mt-1">{{ $message }}</span>
@enderror
