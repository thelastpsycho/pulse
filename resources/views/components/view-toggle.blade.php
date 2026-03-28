@props(['viewMode' => 'table'])

<div
    x-data="{
        currentView: '{{ $viewMode }}',
        setView(mode) {
            this.currentView = mode;
            @this.set('viewMode', mode);
        }
    }"
    role="tablist"
    aria-label="View mode"
    class="glass rounded-2xl p-1.5 inline-flex"
>
    <!-- Table View Tab -->
    <button
        @click="setView('table')"
        :class="currentView === 'table' ? 'bg-primary text-white shadow-lg' : 'text-muted hover:text-text hover:bg-surface-2'"
        role="tab"
        :aria-selected="currentView === 'table'"
        aria-controls="issues-table-view"
        class="relative flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
        </svg>
        <span>Table View</span>
        @if($viewMode === 'table')
            <div class="absolute bottom-1 left-1/2 h-0.5 w-6 -translate-x-1/2 rounded-full bg-white/50"></div>
        @endif
    </button>

    <!-- Board View Tab -->
    <button
        @click="setView('kanban')"
        :class="currentView === 'kanban' ? 'bg-primary text-white shadow-lg' : 'text-muted hover:text-text hover:bg-surface-2'"
        role="tab"
        :aria-selected="currentView === 'kanban'"
        aria-controls="issues-kanban-view"
        class="relative flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
        </svg>
        <span>Board View</span>
        @if($viewMode === 'kanban')
            <div class="absolute bottom-1 left-1/2 h-0.5 w-6 -translate-x-1/2 rounded-full bg-white/50"></div>
        @endif
    </button>
</div>
