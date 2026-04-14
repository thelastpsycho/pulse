@props([
    'issue' => null,
    'show' => false,
])

@if($show && $issue)
<div
    x-data="{
        show: {{ $show ? 'true' : 'false' }},
        close() {
            this.show = false;
            @this.closeQuickView();
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="transform opacity-100 scale-100"
    x-transition:leave-end="transform opacity-0 scale-95"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
    @click.self="close()"
    @keydown.escape="close()"
    role="dialog"
    aria-modal="true"
    aria-labelledby="quick-view-title"
>
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50"></div>

    <!-- Modal content -->
    <div class="relative w-full max-w-2xl rounded-2xl border border-border bg-surface shadow-2xl max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border/50 bg-surface/95 backdrop-blur-sm px-6 py-4">
            <div class="flex items-center gap-3">
                <h2 id="quick-view-title" class="text-xl font-semibold text-text">
                    #{{ $issue->id }}: {{ $issue->title }}
                </h2>
                @if($issue->isClosed())
                <x-badge variant="success">Closed</x-badge>
                @else
                <x-badge variant="muted">Open</x-badge>
                @endif
            </div>
            <button @click="close()" class="p-2 text-muted hover:text-text rounded-lg hover:bg-surface-2 transition-colors">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 space-y-4">
            <!-- Priority and badges -->
            <div class="flex flex-wrap items-center gap-2">
                @php
                    $priorityVariant = match($issue->priority) {
                        'urgent' => 'danger',
                        'high' => 'warning',
                        default => 'muted'
                    };
                @endphp
                <x-badge variant="{{ $priorityVariant }}">
                    {{ ucfirst($issue->priority) }}
                </x-badge>
                @foreach($issue->departments->take(3) as $department)
                <x-badge variant="muted">{{ $department->name }}</x-badge>
                @endforeach
                @if($issue->departments->count() > 3)
                <x-badge variant="muted">+{{ $issue->departments->count() - 3 }}</x-badge>
                @endif
            </div>

            <!-- Description -->
            @if($issue->description)
            <div>
                <h3 class="text-sm font-semibold text-text mb-2">Description</h3>
                <div class="text-sm text-muted whitespace-pre-wrap">{{ $issue->description }}</div>
            </div>
            @endif

            <!-- Details grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-muted">Created by:</span>
                    <span class="ml-2 text-text">{{ $issue->createdBy->name ?? 'Unknown' }}</span>
                </div>
                <div>
                    <span class="text-muted">Created at:</span>
                    <span class="ml-2 text-text">{{ $issue->created_at->format('M d, Y') }}</span>
                </div>
                <div>
                    <span class="text-muted">Assigned to:</span>
                    <span class="ml-2 text-text">{{ $issue->assignedTo?->name ?? 'Unassigned' }}</span>
                </div>
                <div>
                    <span class="text-muted">Due date:</span>
                    <span class="ml-2 text-text">{{ $issue->due_date?->format('M d, Y') ?? 'No due date' }}</span>
                </div>
                @if($issue->location)
                <div class="sm:col-span-2">
                    <span class="text-muted">Location:</span>
                    <span class="ml-2 text-text">{{ $issue->location }}</span>
                </div>
                @endif
            </div>

            <!-- Recent activity (last 3 comments) -->
            @if($issue->comments && $issue->comments->count() > 0)
            <div>
                <h3 class="text-sm font-semibold text-text mb-2">Recent Activity</h3>
                <div class="space-y-2">
                    @foreach($issue->comments->take(-3) as $comment)
                    <div class="rounded-lg bg-surface-2/50 p-3">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold text-text">{{ $comment->user->name }}</span>
                            <span class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-muted">{{ \Illuminate\Support\Str::limit($comment->content, 150) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="sticky bottom-0 flex items-center justify-end gap-2 border-t border-border/50 bg-surface/95 backdrop-blur-sm px-6 py-4">
            <button @click="close()" class="btn btn-secondary">
                Close
            </button>
            <a href="{{ route('issues.show', $issue) }}" class="btn btn-primary">
                View Full Details
            </a>
        </div>
    </div>
</div>
@endif
