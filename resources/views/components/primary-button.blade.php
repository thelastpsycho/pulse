@props(['disabled' => false, 'class' => ''])

<button {{ $attributes->merge(['type' => 'submit', 'disabled' => $disabled, 'class' => 'btn btn-primary ' . $class]) }}>
    {{ $slot }}
</button>
