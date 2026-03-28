@props(['gradient' => false, 'class' => ''])

@php
$baseClasses = 'card';
$gradientClasses = $gradient ? 'gradient-border' : '';
@endphp

<div {{ $attributes->merge(['class' => $baseClasses . ' ' . $gradientClasses . ' ' . $class]) }}>
    {{ $slot }}
</div>
