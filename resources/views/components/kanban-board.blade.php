@props([
    'openIssues',      // Collection of open issues
    'progressIssues', // Collection of in-progress issues
    'closedIssues'    // Collection of closed issues
])

<div
    class="kanban-board"
    x-data="kanbanBoard()"
    x-init="initKanban()"
    role="region"
    aria-label="Kanban board for issue management"
>
    <!-- Performance Notice -->
    <div class="mb-4 rounded-xl border border-warning/30 bg-warning/10 px-4 py-3 text-sm">
        <div class="flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="font-medium text-warning">Performance Mode</p>
                <p class="text-muted">Showing the most recent 150 issues. Use filters or search for specific items. Switch to Table View to see all issues with pagination.</p>
            </div>
        </div>
    </div>

    <!-- Columns container -->
    <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
        <!-- Open column -->
        <x-kanban-column
            title="Open"
            status="open"
            :issues="$openIssues"
            columnId="open"
            :bgOpacity="100"
        />

        <!-- In Progress column -->
        <x-kanban-column
            title="In Progress"
            status="in_progress"
            :issues="$progressIssues"
            columnId="in-progress"
            :bgOpacity="95"
        />

        <!-- Closed column -->
        <x-kanban-column
            title="Closed"
            status="closed"
            :issues="$closedIssues"
            columnId="closed"
            :bgOpacity="90"
        />
    </div>
</div>
