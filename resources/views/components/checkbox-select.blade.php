@props([
    'value',           // Issue ID
    'checked' => false, // Checked state
    'label' => '',     // Optional label (for screen readers)
])

<label class="relative inline-flex items-center justify-center cursor-pointer group">
    <input
        type="checkbox"
        value="{{ $value }}"
        {{ $checked ? 'checked' : '' }}
        class="peer h-5 w-5 rounded-md border-2 border-border/50 bg-surface-2/50 text-primary transition-all duration-200 focus:ring-2 focus:ring-primary/20 focus:ring-offset-2 focus:ring-offset-background cursor-pointer"
        @if($label)
            aria-label="{{ $label }}"
        @endif
    />
    <!-- Custom checkmark icon -->
    <svg class="pointer-events-none absolute h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100 transition-opacity duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
    </svg>

    <!-- Selected state indicator (ring) -->
    <div class="pointer-events-none absolute inset-0 rounded-md border-2 border-transparent peer-checked:border-primary peer-checked:ring-2 peer-checked:ring-primary/20 transition-all duration-200"></div>

    {{ $slot }}
</label>
