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
