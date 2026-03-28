@props(['variant' => 'muted', 'class' => ''])

@php
$variantClasses = match($variant) {
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
    'muted' => 'badge-muted',
    default => 'badge-muted'
};
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $variantClasses . ' ' . $class]) }}>
    {{ $slot }}
</span>
