@props(['value', 'class' => ''])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-text mb-1.5 ' . $class]) }}>
    {{ $value ?? $slot }}
</label>
