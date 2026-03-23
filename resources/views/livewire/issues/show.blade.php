<div class="space-y-6" x-data="{ showCloseModal: false, showReopenModal: false }">
    @if (session('success'))
        <div class="rounded-lg bg-success/10 px-4 py-3 text-sm text-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between border-b border-border pb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('issues.index') }}" class="text-muted hover:text-text">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <p class="text-sm text-muted">Issue #{{ $issue->id }}</p>
                <h1 class="text-xl font-semibold text-text">{{ $issue->title }}</h1>
            </div>
        </div>
        <div class="flex items-center gap-1">
            @can('update', $issue)
                <a href="{{ route('issues.edit', $issue) }}" class="p-2 text-muted hover:text-text rounded-lg hover:bg-surface-2" title="Edit">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
            @endcan
            @if($issue->status === 'open')
                @can('close', $issue)
                    <button @click="showCloseModal = true" class="p-2 text-muted hover:text-success rounded-lg hover:bg-surface-2" title="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                @endcan
            @else
                @can('reopen', $issue)
                    <button @click="showReopenModal = true" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-surface-2" title="Reopen">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </button>
                @endcan
            @endif
            @can('issues.export')
                <button wire:click="exportPDF" class="p-2 text-muted hover:text-text rounded-lg hover:bg-surface-2" title="Export PDF">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </button>
            @endcan
            @can('delete', $issue)
                <button wire:click="deleteIssue" class="p-2 text-muted hover:text-danger rounded-lg hover:bg-surface-2" title="Delete">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            @endcan
        </div>
    </div>

    <!-- Description & Recovery Action -->
    @if($issue->description || $issue->recovery)
        <div class="rounded-lg border border-border divide-y divide-border">
            @if($issue->description)
                <div class="p-4">
                    <p class="text-xs text-muted uppercase tracking-wide mb-2">Description</p>
                    <div class="prose prose-sm max-w-none text-text">{!! app(\League\CommonMark\MarkdownConverter::class)->convert($issue->description) !!}</div>
                </div>
            @endif
            @if($issue->recovery)
                <div class="p-4">
                    <p class="text-xs text-muted uppercase tracking-wide mb-2">Recovery Action</p>
                    <div class="prose prose-sm max-w-none text-text">{!! app(\League\CommonMark\MarkdownConverter::class)->convert($issue->recovery) !!}</div>
                </div>
            @endif
        </div>
    @endif

    <!-- Meta Info -->
    <div class="flex flex-wrap items-center gap-4 text-sm">
        <span class="inline-flex items-center gap-1.5 rounded-full {{ $issue->status === 'open' ? 'bg-success/20 text-success' : 'bg-muted/20 text-muted' }} px-3 py-1">
            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
            {{ ucfirst($issue->status) }}
        </span>
        <span class="rounded-full {{ match($issue->priority) {
            'urgent' => 'bg-danger/20 text-danger',
            'high' => 'bg-warning/20 text-warning',
            default => 'bg-muted/20 text-muted'
        } }} px-3 py-1">
            {{ ucfirst($issue->priority) }}
        </span>
        <span class="text-muted">{{ $issue->created_at->format('M d, Y') }}</span>
        <span class="text-muted">by {{ $issue->createdBy->name ?? 'Unknown' }}</span>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Guest Details -->
            @if($issue->name || $issue->room_number || $issue->nationality || $issue->contact || $issue->checkin_date || $issue->checkout_date || $issue->issue_date || $issue->recovery_cost !== null)
                <div class="rounded-lg border border-border">
                    <div class="border-b border-border px-4 py-3">
                        <h3 class="text-sm font-medium text-text">Guest Details</h3>
                    </div>
                    <div class="divide-y divide-border">
                        @if($issue->name)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Name</p>
                                <p class="col-span-2 text-text">{{ $issue->name }}</p>
                            </div>
                        @endif
                        @if($issue->room_number)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Room</p>
                                <p class="col-span-2 text-text">{{ $issue->room_number }}</p>
                            </div>
                        @endif
                        @if($issue->nationality)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Nationality</p>
                                <p class="col-span-2 text-text">{{ $issue->nationality }}</p>
                            </div>
                        @endif
                        @if($issue->contact)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Contact</p>
                                <p class="col-span-2 text-text">{{ $issue->contact }}</p>
                            </div>
                        @endif
                        @if($issue->checkin_date)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Check-in</p>
                                <p class="col-span-2 text-text">{{ $issue->checkin_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($issue->checkout_date)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Check-out</p>
                                <p class="col-span-2 text-text">{{ $issue->checkout_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($issue->issue_date)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Issue Date</p>
                                <p class="col-span-2 text-text">{{ $issue->issue_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($issue->recovery_cost !== null)
                            <div class="grid grid-cols-3 gap-4 p-4">
                                <p class="text-xs text-muted uppercase">Recovery Cost</p>
                                <p class="col-span-2 text-text">{{ number_format($issue->recovery_cost) }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Details Card -->
            <div class="rounded-lg border border-border divide-y divide-border">
                @if($issue->assignedTo || $issue->location)
                    @if($issue->assignedTo)
                        <div class="p-4">
                            <p class="text-xs text-muted uppercase tracking-wide mb-1">Assigned To</p>
                            <p class="text-text">{{ $issue->assignedTo->name }}</p>
                        </div>
                    @endif
                    @if($issue->location)
                        <div class="p-4">
                            <p class="text-xs text-muted uppercase tracking-wide mb-1">Location</p>
                            <p class="text-text">{{ $issue->location }}</p>
                        </div>
                    @endif
                @endif
                @if($issue->departments->count() > 0 || $issue->issueTypes->count() > 0)
                    <div class="p-4">
                        <p class="text-xs text-muted uppercase tracking-wide mb-2">Categories</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($issue->departments as $department)
                                <span class="rounded bg-primary/10 px-2 py-1 text-sm text-primary">{{ $department->name }}</span>
                            @endforeach
                            @foreach($issue->issueTypes as $type)
                                <span class="rounded bg-accent/10 px-2 py-1 text-sm text-accent">{{ $type->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6" x-data="{ showCloseModal: false, showReopenModal: false }">
            <!-- Comments -->
            <div class="rounded-lg border border-border">
                <div class="border-b border-border px-4 py-3">
                    <h3 class="text-sm font-medium text-text">Comments ({{ $issue->comments->count() }})</h3>
                </div>
                <div class="max-h-96 overflow-y-auto">
                    <div class="divide-y divide-border">
                        @if($issue->comments->count() > 0)
                            @foreach($issue->comments as $comment)
                                <div class="p-4">
                                    <div class="flex gap-2">
                                        <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded-full bg-surface-2 text-xs font-medium text-text">
                                            {{ $comment->user->name[0] }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-medium text-text">{{ $comment->user->name }}</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                                                    @can('delete', $comment)
                                                        <button wire:click="deleteComment({{ $comment->id }})" class="text-xs text-danger hover:underline">Delete</button>
                                                    @endcan
                                                </div>
                                            </div>
                                            <p class="mt-1 text-xs text-muted whitespace-pre-wrap">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-6 text-center text-xs text-muted">No comments</div>
                        @endif
                    </div>
                </div>
                <div class="border-t border-border p-3">
                    <form wire:submit="addComment">
                        <textarea
                            wire:model="comment"
                            rows="2"
                            class="w-full resize-none rounded border border-border bg-surface-2 px-3 py-2 text-sm text-text placeholder-muted focus:border-primary focus:outline-none"
                            placeholder="Add a comment..."
                        ></textarea>
                        <x-input-error :messages="$errors->get('comment')" class="mt-1" />
                        <div class="mt-2 flex justify-end">
                            <button type="submit" class="bg-primary px-3 py-1.5 text-xs text-white rounded hover:bg-primary/90">Send</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Activity -->
            <div class="rounded-lg border border-border">
                <div class="border-b border-border px-4 py-3">
                    <h3 class="text-sm font-medium text-text">Activity</h3>
                </div>
                <div class="max-h-80 overflow-y-auto p-4">
                    @if($activityLog->count() > 0)
                        <div class="space-y-4">
                            @foreach($activityLog as $activity)
                                <div class="flex gap-2">
                                    <div class="flex h-6 w-6 flex-shrink-0 items-center justify-center rounded bg-surface-2 text-xs text-text">
                                        {{ $activity->actor?->name[0] ?? '?' }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-text">
                                            <span class="font-medium">{{ $activity->actor?->name ?? 'Unknown' }}</span>
                                            <span class="text-muted"> {{ $activity->meta['description'] ?? $activity->action }}</span>
                                        </p>
                                        <p class="text-xs text-muted">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-sm text-muted">No activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Close Modal -->
<div x-show="showCloseModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="showCloseModal = false; showReopenModal = false"></div>
        <div class="relative w-full max-w-md rounded-lg border border-border bg-surface p-6">
            <h3 class="text-lg font-semibold text-text mb-4">Close Issue</h3>
            <p class="text-sm text-muted mb-4">Add a note (optional)</p>
            <textarea
                wire:model="closeNote"
                rows="3"
                class="w-full resize-none rounded border border-border bg-surface-2 px-3 py-2 text-sm text-text placeholder-muted focus:border-primary focus:outline-none"
                placeholder="Resolution note..."
            ></textarea>
            <div class="mt-4 flex justify-end gap-2">
                <button @click="showCloseModal = false; showReopenModal = false" class="px-4 py-2 text-sm text-text hover:bg-surface-2 rounded">Cancel</button>
                <button wire:click="closeIssue" class="bg-success px-4 py-2 text-sm text-white rounded hover:bg-success/90">Close</button>
            </div>
        </div>
    </div>

<!-- Reopen Modal -->
<div x-show="showReopenModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="showCloseModal = false; showReopenModal = false"></div>
        <div class="relative w-full max-w-md rounded-lg border border-border bg-surface p-6">
            <h3 class="text-lg font-semibold text-text mb-4">Reopen Issue</h3>
            <p class="text-sm text-muted">Are you sure you want to reopen this issue?</p>
            <div class="mt-4 flex justify-end gap-2">
                <button @click="showCloseModal = false; showReopenModal = false" class="px-4 py-2 text-sm text-text hover:bg-surface-2 rounded">Cancel</button>
                <button wire:click="reopenIssue" class="bg-primary px-4 py-2 text-sm text-white rounded hover:bg-primary/90">Reopen</button>
            </div>
        </div>
    </div>
</div>
