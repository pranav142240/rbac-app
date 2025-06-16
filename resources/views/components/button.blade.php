@props([
    'type' => 'primary',
    'buttonType' => 'submit',
    'tag' => 'button',
])

@php
    $styleClasses = \Illuminate\Support\Arr::toCssClasses([
        'btn',
        match ($type) {
            'primary' => 'btn-primary',
            'danger' => 'btn-danger',
            'secondary' => 'btn-secondary',
            'warning' => 'btn-warning',
            'success' => 'btn-success',
            default => 'btn-primary', // Default to primary
        },
    ]);
@endphp

<{{ $tag }} type="{{ $buttonType }}" {{ $attributes->merge(['class' => $styleClasses]) }}>
    {{ $slot }}
</{{ $tag }}>
