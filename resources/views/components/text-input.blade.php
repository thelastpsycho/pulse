@props(['disabled' => false, 'class' => ''])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'w-full bg-surface border border-border text-text rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-muted disabled:opacity-50 disabled:cursor-not-allowed transition-colors ' . $class]) }}
>
