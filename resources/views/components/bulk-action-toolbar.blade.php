@props([
    'selectedCount' => 0,
    'canClose' => false,
    'canDelete' => false,
])

@if($selectedCount > 0)
<div class="gradient-border rounded-2xl p-4 animate-fade-in fixed top-20 left-4 right-4 z-40 md:relative md:top-0 md:left-0 md:right-0 md:z-0">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <!-- Selection count -->
        <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary/20">
                <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <div>
                <p class="font-semibold text-text">{{ $selectedCount }} issue{{ $selectedCount > 1 ? 's' : '' }} selected</p>
                <p class="text-xs text-muted">Apply bulk actions below</p>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="flex flex-col gap-2 sm:flex-row md:gap-3">
            @if($canClose)
            <button wire:click="closeSelected" class="btn btn-success inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Close Selected</span>
            </button>
            @endif

            <button wire:click="reopenSelected" class="btn btn-primary inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Reopen</span>
            </button>

            @if($canDelete)
            <button wire:click="deleteSelected" class="btn btn-danger inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Delete</span>
            </button>
            @endif

            <button wire:click="clearSelection" class="btn btn-secondary inline-flex items-center justify-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Clear</span>
            </button>
        </div>
    </div>
</div>
@endif
