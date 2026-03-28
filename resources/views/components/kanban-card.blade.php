@props(['issue', 'compact' => false])

<div
    draggable="true"
    @click="openQuickView({{ $issue->id }})"
    class="kanban-card group relative bg-surface border border-border rounded-xl p-4 shadow-sm hover:shadow-md transition-all duration-200 cursor-move"
    data-issue-id="{{ $issue->id }}"
    x-data="{
        dragged: false,
        handleKeyDown(event) {
            const card = event.currentTarget;
            const column = card.closest('.kanban-column')?.getAttribute('data-column');

            switch(event.key) {
                case ' ':
                case 'Enter':
                    event.preventDefault();
                    this.dragged = true;
                    card.setAttribute('aria-grabbed', 'true');
                    card.classList.add('ring-2', 'ring-primary', 'ring-offset-2');
                    this.$dispatch('announce', 'Dragging issue {{ $issue->id }}');
                    break;
                case 'Escape':
                    event.preventDefault();
                    this.dragged = false;
                    card.setAttribute('aria-grabbed', 'false');
                    card.classList.remove('ring-2', 'ring-primary', 'ring-offset-2');
                    this.$dispatch('announce', 'Drag cancelled');
                    break;
                case 'ArrowRight':
                    if (this.dragged && column !== 'closed') {
                        event.preventDefault();
                        @this.call('updateIssueStatus', {
                            issueId: {{ $issue->id }},
                            newStatus: column === 'open' ? 'in_progress' : 'closed'
                        });
                    }
                    break;
                case 'ArrowLeft':
                    if (this.dragged && column !== 'open') {
                        event.preventDefault();
                        @this.call('updateIssueStatus', {
                            issueId: {{ $issue->id }},
                            newStatus: column === 'closed' ? 'in_progress' : 'open'
                        });
                    }
                    break;
            }
        }
    }"
    @dragstart="$wire.emit('dragStart', {{ $issue->id }}); dragged = true"
    @dragend="$wire.emit('dragEnd', {{ $issue->id }}); dragged = false"
    @keydown.window="handleKeyDown($event)"
    tabindex="0"
    role="button"
    aria-label="Issue: {{ $issue->title }}, Status: {{ $issue->status }}, Press Space or Enter to drag, Arrow keys to move, Escape to cancel"
    :aria-grabbed="dragged ? 'true' : 'false'"
>
    <!-- Priority badge (top right) -->
    <div class="absolute top-3 right-3">
        <x-badge variant="{{ match($issue->priority) {
            'urgent' => 'danger',
            'high' => 'warning',
            default => 'muted'
        } }}">
            {{ ucfirst($issue->priority) }}
        </x-badge>
    </div>

    <!-- Issue ID (top left) -->
    <div class="text-xs text-muted font-mono mb-2">
        #{{ $issue->id }}
    </div>

    <!-- Title -->
    <h4 class="font-semibold text-text text-sm mb-2 pr-12 line-clamp-2">
        {{ $issue->title }}
    </h4>

    <!-- Description (truncated) -->
    @if($issue->description && !$compact)
    <p class="text-xs text-muted line-clamp-2 mb-3">
        {{ \Illuminate\Support\Str::limit($issue->description, 100) }}
    </p>
    @endif

    <!-- Footer: department and assignee -->
    <div class="flex items-center justify-between text-xs text-muted">
        <!-- Department -->
        @if($issue->departments->count() > 0)
        <div class="flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span class="truncate max-w-[100px]">{{ $issue->departments->first()->name }}</span>
        </div>
        @endif

        <!-- Assigned user -->
        @if($issue->assignedTo)
        <div class="flex items-center gap-1">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="truncate max-w-[80px]">{{ $issue->assignedTo->name }}</span>
        </div>
        @else
        <span class="text-muted/60">Unassigned</span>
        @endif
    </div>

    <!-- Drag handle indicator (visible on hover) -->
    <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
        <svg class="h-4 w-4 text-muted" fill="currentColor" viewBox="0 0 24 24">
            <path d="M8 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm0 6a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-2 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm8-14a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm-2 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm2 4a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
        </svg>
    </div>
</div>
