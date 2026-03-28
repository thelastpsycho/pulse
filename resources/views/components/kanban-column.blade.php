@props([
    'title',           // Column title (Open, In Progress, Closed)
    'status',          // Issue status filter (open, in_progress, closed)
    'issues',          // Collection of issues to display
    'columnId',        // Unique ID for SortableJS
    'bgOpacity' => 100 // Background opacity (100%, 95%, 90%)
])

<div
    class="kanban-column flex-shrink-0 w-full lg:w-1/3 bg-surface/{{ $bgOpacity }} border border-border/50 rounded-2xl p-4"
    data-column-id="{{ $columnId }}"
    data-status="{{ $status }}"
    x-data="{
        isDragOver: false,
        onDragOver() {
            this.isDragOver = true;
        },
        onDragLeave() {
            this.isDragOver = false;
        }
    }"
    @dragover.prevent="onDragOver()"
    @dragleave="onDragLeave()"
    @drop.prevent="$wire.emit('dropIssue', '{{ $columnId }}'); isDragOver = false"
    :class="{ 'border-primary border-2 border-dashed': isDragOver }"
    role="region"
    :aria-label="'{{ $title }} column'"
>
    <!-- Column header -->
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <h3 class="text-lg font-semibold text-text">{{ $title }}</h3>
            <span class="px-2 py-0.5 rounded-full bg-surface-2/50 text-xs font-semibold text-muted">
                {{ $issues->count() }}
            </span>
        </div>
    </div>

    <!-- Drop zone hint (visible when dragging over) -->
    <div x-show="isDragOver" class="hidden">
        <div class="text-center py-8 text-muted text-sm">
            <svg class="h-8 w-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
            </svg>
            Drop to change status
        </div>
    </div>

    <!-- Cards container (Sortable zone) -->
    <div class="kanban-cards space-y-3 min-h-[200px]" data-column="{{ $columnId }}">
        @forelse($issues as $issue)
            <x-kanban-card :issue="$issue" />
        @empty
            <!-- Empty state -->
            <div class="text-center py-8 text-muted/60 text-sm">
                No issues in this column
            </div>
        @endforelse
    </div>
</div>
