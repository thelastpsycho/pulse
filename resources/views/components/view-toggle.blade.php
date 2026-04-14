@props(['viewMode' => 'table'])

<div
    x-data="{
        currentView: '{{ $viewMode }}',
        isLoading: false,
        setView(mode) {
            if (this.currentView === mode) return;
            this.isLoading = true;
            this.currentView = mode;
            $wire.setViewMode(mode).then(() => {
                this.isLoading = false;
            });
        }
    }"
    role="tablist"
    aria-label="View mode"
    class="glass rounded-xl p-1 inline-flex relative"
>
    <!-- Table View Tab -->
    <button
        @click="setView('table')"
        :class="currentView === 'table' ? 'bg-primary text-white shadow-md' : 'text-muted hover:text-text hover:bg-surface-2/50'"
        :disabled="isLoading"
        role="tab"
        :aria-selected="currentView === 'table'"
        aria-controls="issues-table-view"
        class="relative flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <span>Table</span>
    </button>

    <!-- Board View Tab -->
    <button
        @click="setView('kanban')"
        :class="currentView === 'kanban' ? 'bg-primary text-white shadow-md' : 'text-muted hover:text-text hover:bg-surface-2/50'"
        :disabled="isLoading"
        role="tab"
        :aria-selected="currentView === 'kanban'"
        aria-controls="issues-kanban-view"
        class="relative flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-xs font-medium transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <svg class="view-transition h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
        </svg>
        <span>Board</span>
    </button>

    <!-- Loading Spinner -->
    <div x-show="isLoading" class="absolute inset-0 flex items-center justify-center bg-background/50 rounded-lg backdrop-blur-sm" style="display: none;">
        <svg class="h-4 w-4 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    </div>
</div>
