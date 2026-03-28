@props(['disabled' => false, 'class' => ''])

<button {{ $attributes->merge(['type' => 'button', 'disabled' => $disabled, 'class' => 'btn btn-secondary ' . $class]) }}>
    {{ $slot }}
</button>
