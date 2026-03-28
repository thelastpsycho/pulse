@props(['disabled' => false, 'class' => ''])

<button {{ $attributes->merge(['type' => 'submit', 'disabled' => $disabled, 'class' => 'btn btn-warning ' . $class]) }}>
    {{ $slot }}
</button>
