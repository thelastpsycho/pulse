<div class="space-y-6" x-data="{
    showKeyboardShortcuts: false,
    showSaveFilterModal: $wire.entangle('showSaveFilterModal'),
    init() {
        this.$nextTick(() => {
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                // Ignore if in input, textarea, or select
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(document.activeElement.tagName)) {
                    // Only allow Escape key
                    if (e.key !== 'Escape') return;
                }

                // Keyboard shortcuts
                switch(e.key.toLowerCase()) {
                    case '/':
                        e.preventDefault();
                        document.querySelector('input[placeholder*=\"Search\"]')?.focus();
                        break;
                    case 'c':
                    case 'n':
                        @if(auth()->check() && auth()->user()->can('create', \App\Models\Issue::class))
                            e.preventDefault();
                            window.location.href = '{{ route('issues.create') }}';
                        @endif
                        break;
                    case 'a':
                        @if(auth()->check())
                            e.preventDefault();
                            @this.set('selectAll', true);
                        @endif
                        break;
                    case 'escape':
                        @if(auth()->check())
                            @this.set('selectedIssues', []);
                            @this.set('selectAll', false);
                        @endif
                        break;
                    case '?':
                        e.preventDefault();
                        this.showKeyboardShortcuts = true;
                        break;
                }
            });
        });
    }
}">
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="relative overflow-hidden rounded-2xl border border-success/30 bg-gradient-to-r from-success/20 to-success/10 p-4 animate-fade-in">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-success/20">
                    <svg class="h-5 w-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="font-medium text-text">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Page Header with Stats -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-semibold text-text font-heading">
                    {{ match($tab) {
                        'all' => 'All Issues',
                        'open' => 'Open Issues',
                        'closed' => 'Closed Issues',
                        default => 'Issues'
                    } }}
                </h1>
                <p class="mt-0.5 text-sm text-muted">
                    {{ $issues->total() }} {{ Str::plural('issue', $issues->total()) }}
                </p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <x-view-toggle :viewMode="$viewMode" />
            <div class="flex items-center gap-2">
                <!-- Keyboard Shortcuts Help Button -->
                <button
                    @click="showKeyboardShortcuts = true"
                    class="inline-flex items-center gap-1.5 rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-muted transition-all duration-200 hover:border-primary/50 hover:text-text"
                    title="Keyboard shortcuts (?)"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="hidden sm:inline">Shortcuts</span>
                    <kbd class="rounded bg-surface px-1 py-0.5 text-xs font-mono">?</kbd>
                </button>
                @can('viewAny', \App\Models\Issue::class)
                    <button wire:click="exportCSV" class="btn btn-outline inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="hidden sm:inline">Export</span>
                    </button>
                @endcan
                @can('create', \App\Models\Issue::class)
                    <a href="{{ route('issues.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="hidden sm:inline">New Issue</span>
                        <span class="sm:hidden">New</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Tabs - Enhanced Design -->
    <div class="glass rounded-xl p-1">
        <div class="flex gap-1">
            <button
                wire:click="setTab('all')"
                class="{{ $tab === 'all' ? 'bg-primary text-white shadow-md' : 'text-muted hover:text-text hover:bg-surface-2/50' }} relative flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200">
                <span class="flex items-center justify-center gap-1.5">
                    All
                    <span class="{{ $tab === 'all' ? 'bg-white/20' : 'bg-muted/10' }} rounded-full px-1.5 py-0.5 text-xs">{{ \App\Models\Issue::count() }}</span>
                </span>
            </button>
            <button
                wire:click="setTab('open')"
                class="{{ $tab === 'open' ? 'bg-success text-white shadow-md' : 'text-muted hover:text-text hover:bg-surface-2/50' }} relative flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200">
                <span class="flex items-center justify-center gap-1.5">
                    Open
                    <span class="{{ $tab === 'open' ? 'bg-white/20' : 'bg-muted/10' }} rounded-full px-1.5 py-0.5 text-xs">{{ \App\Models\Issue::open()->count() }}</span>
                </span>
            </button>
            <button
                wire:click="setTab('closed')"
                class="{{ $tab === 'closed' ? 'bg-muted-foreground text-white shadow-md' : 'text-muted hover:text-text hover:bg-surface-2/50' }} relative flex-1 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200">
                <span class="flex items-center justify-center gap-1.5">
                    Closed
                    <span class="{{ $tab === 'closed' ? 'bg-white/20' : 'bg-muted/10' }} rounded-full px-1.5 py-0.5 text-xs">{{ \App\Models\Issue::closed()->count() }}</span>
                </span>
            </button>
        </div>
    </div>

    <!-- Search & Filters - Redesigned -->
    <div class="card overflow-hidden">
        <div class="border-b border-border/50 p-4 sm:p-6">
            <div x-data="{
                filtersOpen: false,
                hasActiveFilters: {{ ($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to) ? 'true' : 'false' }}
            }" class="space-y-4">

                <!-- Search Bar - Always Visible -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search issues..."
                        class="w-full rounded-lg border border-border/50 bg-surface-2/50 py-2.5 pl-10 pr-24 text-sm text-text placeholder-muted transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]"
                    />
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-2">
                        <button
                            @click="filtersOpen = !filtersOpen"
                            type="button"
                            class="inline-flex items-center gap-1.5 rounded-md px-2.5 py-1.5 text-xs font-medium transition-all duration-200 hover:bg-surface-2"
                            :class="filtersOpen || hasActiveFilters ? 'bg-primary/10 text-primary' : 'text-muted hover:text-text'"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="hidden sm:inline">Filters</span>
                            @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
                                <span class="flex h-4 w-4 items-center justify-center rounded-full bg-primary text-[10px] font-bold text-white">●</span>
                            @endif
                        </button>
                        <kbd class="hidden sm:block rounded bg-surface px-1.5 py-0.5 text-[10px] font-mono text-muted border border-border">/</kbd>
                    </div>
                </div>

                <!-- Collapsible Filters Section -->
                <div
                    x-show="filtersOpen || hasActiveFilters"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="space-y-4"
                    style="display: none;"
                >
                    <!-- Filter Grid -->
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Department Filter -->
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-muted">Department</label>
                            <select wire:model.live="department_id" class="w-full rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]">
                                <option value="">All Departments</option>
                                @foreach($this->departments as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Issue Type Filter -->
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-muted">Issue Type</label>
                            <select wire:model.live="issue_type_id" class="w-full rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]">
                                <option value="">All Types</option>
                                @foreach($this->issueTypes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-muted">Priority</label>
                            <select wire:model.live="priority" class="w-full rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]">
                                <option value="">All Priorities</option>
                                @foreach($this->priorities as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Assigned To Filter -->
                        <div class="space-y-1">
                            <label class="block text-xs font-medium text-muted">Assigned To</label>
                            <select wire:model.live="assigned_to" class="w-full rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]">
                                <option value="">All Users</option>
                                @foreach($this->users as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Date Range & Actions Row -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <!-- Date Range Picker -->
                        <div x-data="{
                            isOpen: false,
                            dateFrom: '{{ $date_from ?? '' }}',
                            dateTo: '{{ $date_to ?? '' }}',
                            dropdownStyle: '',
                            toggleDropdown($event) {
                                this.isOpen = !this.isOpen;
                                if (this.isOpen) {
                                    this.$nextTick(() => {
                                        const button = $event.currentTarget;
                                        const rect = button.getBoundingClientRect();
                                        const dropdownWidth = 288; // w-72 in pixels

                                        // Default: align right edge with right edge of button
                                        let left = rect.right - dropdownWidth;

                                        // Check if it would go off left edge of screen
                                        if (left < 8) {
                                            left = 8; // Minimum margin from left edge
                                        }

                                        // Check if it would go off right edge of screen
                                        if (left + dropdownWidth > window.innerWidth - 8) {
                                            left = window.innerWidth - dropdownWidth - 8;
                                        }

                                        this.dropdownStyle = `position: fixed; top: ${rect.bottom + 8}px; left: ${left}px;`;
                                    });
                                }
                            },
                            apply() {
                                @this.set('date_from', this.dateFrom);
                                @this.set('date_to', this.dateTo);
                                this.close();
                            },
                            clear() {
                                this.dateFrom = '';
                                this.dateTo = '';
                                @this.set('date_from', '');
                                @this.set('date_to', '');
                                this.close();
                            },
                            close() {
                                this.isOpen = false;
                            }
                        }" class="sm:flex-1">
                            <button
                                @click="toggleDropdown()"
                                type="button"
                                class="flex w-full items-center gap-2 rounded-lg border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-text transition-all duration-200 hover:border-primary/50 focus:border-primary focus:ring-2 focus:ring-primary/20 min-h-[44px]"
                            >
                                <svg class="h-4 w-4 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="flex-1 text-left" x-text="dateFrom || dateTo ? (dateFrom + ' - ' + dateTo) : 'Date Range'"></span>
                                <svg class="h-3 w-3 text-muted transition-transform duration-200" :class="{'rotate-180': isOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <div
                                x-show="isOpen"
                                @click.away="close()"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="z-[9999] mt-2 w-72 rounded-lg border border-border bg-surface shadow-xl"
                                x-bind:style="dropdownStyle"
                                style="display: none;"
                            >
                                <div class="p-4">
                                    <h4 class="mb-3 text-sm font-semibold text-text">Date Range</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-muted">From</label>
                                            <input
                                                type="date"
                                                x-model="dateFrom"
                                                class="w-full rounded-lg border border-border bg-surface-2 px-3 py-2 text-sm text-text focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                            />
                                        </div>
                                        <div>
                                            <label class="mb-1.5 block text-xs font-medium text-muted">To</label>
                                            <input
                                                type="date"
                                                x-model="dateTo"
                                                class="w-full rounded-lg border border-border bg-surface-2 px-3 py-2 text-sm text-text focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                            />
                                        </div>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button
                                            @click="clear()"
                                            type="button"
                                            class="rounded-lg px-3 py-1.5 text-xs font-medium text-muted hover:bg-surface-2 transition-colors"
                                        >
                                            Clear
                                        </button>
                                        <button
                                            @click="apply()"
                                            type="button"
                                            class="rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-primary/90 transition-colors"
                                        >
                                            Apply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2">
                            @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
                                <button
                                    wire:click="clearFilters"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-border/50 px-3 py-2 text-sm font-medium text-muted transition-all duration-200 hover:border-danger/50 hover:text-danger hover:bg-danger/10 min-h-[44px]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Clear All
                                </button>

                                @if(auth()->check() && auth()->user()->can('viewAny', \App\Models\Issue::class))
                                <button
                                    wire:click="openSaveFilterModal"
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-primary/50 bg-primary/10 px-3 py-2 text-sm font-medium text-primary transition-all duration-200 hover:bg-primary/20 min-h-[44px]"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    Save Filter
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Active Filters Display -->
                    @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
                        <div class="flex flex-wrap items-center gap-2 rounded-lg bg-surface-2/30 p-3">
                            <span class="text-xs font-medium text-muted">Active filters:</span>
                            @if($search)
                                <span class="badge badge-muted flex items-center gap-1">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    {{ $search }}
                                </span>
                            @endif
                            @if($department_id)
                                <span class="badge badge-muted">{{ $this->departments[$department_id] ?? '' }}</span>
                            @endif
                            @if($issue_type_id)
                                <span class="badge badge-muted">{{ $this->issueTypes[$issue_type_id] ?? '' }}</span>
                            @endif
                            @if($priority)
                                <span class="badge badge-{{ $priority === 'urgent' ? 'danger' : ($priority === 'high' ? 'warning' : 'muted') }}">{{ ucfirst($priority) }}</span>
                            @endif
                            @if($assigned_to)
                                <span class="badge badge-muted">{{ $this->users[$assigned_to] ?? '' }}</span>
                            @endif
                            @if($date_from || $date_to)
                                <span class="badge badge-muted flex items-center gap-1">
                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $date_from ?? '?' }} - {{ $date_to ?? '?' }}
                                </span>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Saved Filters - Always Visible When Available -->
                @if(count($this->savedFilters) > 0)
                    <div class="flex flex-col gap-3 rounded-lg bg-surface-2/30 p-3">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            <span class="text-xs font-semibold text-text uppercase tracking-wide">Saved Filters</span>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($this->savedFilters as $savedFilter)
                            <div class="group relative inline-flex flex-shrink-0">
                                <button
                                    wire:click="loadFilter({{ $savedFilter['id'] }})"
                                    class="inline-flex items-center gap-1.5 rounded-md border border-border/50 bg-surface px-3 py-1.5 text-xs font-medium text-text transition-all duration-200 hover:border-primary/50 hover:bg-primary/5 hover:text-primary cursor-pointer"
                                >
                                    <svg class="h-3 w-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    <span class="max-w-[120px] truncate">{{ $savedFilter['name'] }}</span>
                                </button>
                                <button
                                    wire:click="deleteFilter({{ $savedFilter['id'] }})"
                                    class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-danger text-white opacity-0 transition-all duration-200 group-hover:opacity-100 hover:bg-danger/80 cursor-pointer"
                                    title="Delete filter '{{ $savedFilter['name'] }}'"
                                >
                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bulk Action Toolbar -->
    <x-bulk-action-toolbar
        :selectedCount="count($selectedIssues)"
        :canClose="auth()->check() && auth()->user()->can('issues.close')"
        :canDelete="auth()->check() && (auth()->user()->can('issues.delete') || auth()->user()->can('issues.delete.own'))"
    />

    <!-- Loading Indicator -->
    <div wire:loading.delay="100ms" wire:target="setViewMode" class="fixed inset-0 z-50 flex items-center justify-center bg-background/80 backdrop-blur-sm">
        <div class="flex flex-col items-center gap-4">
            <svg class="h-12 w-12 animate-spin text-primary" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-lg font-medium text-text">Loading view...</p>
        </div>
    </div>

    <!-- Table/Card List View -->
    @if($viewMode === 'table')
        <!-- Issues List -->
        @if($issues->count() > 0)
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto rounded-2xl border border-border/50">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-border/50 bg-surface-2/30">
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Select</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Issue</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Status</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Room Number</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Issue Date</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Guest Name</th>
                        <th class="px-4 py-2.5 text-left text-xs font-medium text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($issues as $issue)
                    <tr class="border-b border-border/30 hover:bg-surface-2/30 transition-colors">
                        <!-- Checkbox cell -->
                        <td class="px-4 py-2.5">
                            <input type="checkbox" wire:model.live="selectedIssues" value="{{ $issue->id }}"
                                class="h-3.5 w-3.5 rounded border-border bg-surface-2 text-primary focus:ring-2 focus:ring-primary/20" />
                        </td>
                        <!-- Title and description -->
                        <td class="px-4 py-2.5">
                            <a href="{{ route('issues.show', $issue) }}" class="text-sm font-medium text-text hover:text-primary">
                                {{ $issue->title }}
                            </a>
                            @if($issue->description)
                            <p class="mt-0.5 text-xs text-muted line-clamp-1">{{ \Illuminate\Support\Str::limit($issue->description, 80) }}</p>
                            @endif
                        </td>
                        <!-- Status badge -->
                        <td class="px-4 py-2.5">
                            <x-badge variant="{{ $issue->isClosed() ? 'success' : 'muted' }}">
                                {{ $issue->isClosed() ? 'Closed' : 'Open' }}
                            </x-badge>
                        </td>
                        <!-- Room Number -->
                        <td class="px-4 py-2.5 text-xs text-text">
                            {{ $issue->room_number ?? '-' }}
                        </td>
                        <!-- Issue Date -->
                        <td class="px-4 py-2.5 text-xs text-muted">
                            {{ $issue->issue_date?->format('M d, Y') ?? '-' }}
                        </td>
                        <!-- Guest Name -->
                        <td class="px-4 py-2.5 text-xs text-text">
                            {{ $issue->name ?? '-' }}
                        </td>
                        <!-- Inline action buttons -->
                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-1">
                                @if($tab === 'all' || $tab === 'open')
                                    @can('close', $issue)
                                    <button wire:click="closeIssue({{ $issue->id }})"
                                        class="p-2 text-success hover:bg-success/10 rounded-lg transition-colors"
                                        title="Close issue">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    @endcan
                                @else
                                    @can('reopen', $issue)
                                    <button wire:click="reopenIssue({{ $issue->id }})"
                                        class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                        title="Reopen issue">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    @endcan
                                @endif
                                <button
                                    wire:click="openQuickView({{ $issue->id }})"
                                    class="inline-action-button p-2 text-muted hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                    title="Quick view"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                                <a href="{{ route('issues.show', $issue) }}"
                                    class="p-2 text-muted hover:text-primary hover:bg-primary/10 rounded-lg transition-colors"
                                    title="View full details">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-2">
            @foreach($issues as $index => $issue)
                <div wire:key="{{ $issue->id }}" class="issue-card animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="flex items-start gap-3">
                        <!-- Checkbox -->
                        <div class="pt-0.5">
                            <label class="relative flex h-5 w-5 cursor-pointer items-center justify-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIssues"
                                    value="{{ $issue->id }}"
                                    class="peer h-4 w-4 rounded-md border-2 border-border/50 bg-surface-2/50 text-primary transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                                />
                                <svg class="pointer-events-none absolute h-3 w-3 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </label>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Room Number & Meta Badges -->
                            <div class="mb-1.5 flex flex-wrap items-center gap-1.5">
                                @if($issue->room_number)
                                    <span class="badge badge-muted">
                                        <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                        </svg>
                                        Room {{ $issue->room_number }}
                                    </span>
                                @endif
                                @if($issue->issue_date)
                                    <span class="badge badge-muted">
                                        <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $issue->issue_date->format('M d, Y') }}
                                    </span>
                                @endif
                                @if($issue->name)
                                    <span class="badge badge-muted">
                                        <svg class="h-3 w-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $issue->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title & Description -->
                            <a href="{{ route('issues.show', $issue) }}" class="group">
                                <h3 class="text-base font-medium text-text transition-colors duration-200 group-hover:text-primary">
                                    {{ $issue->title }}
                                </h3>
                            </a>
                            @if($issue->description)
                                <p class="mt-1 text-xs text-muted line-clamp-2">{{ \Illuminate\Support\Str::limit($issue->description, 150) }}</p>
                            @endif

                            <!-- Meta Info -->
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-[10px] text-muted">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $issue->createdBy->name ?? 'Unknown' }}
                                </span>
                                @if($issue->location)
                                    <span class="flex items-center gap-1.5">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        {{ $issue->location }}
                                    </span>
                                @endif
                            </div>

                            <!-- Mobile Actions -->
                            <div class="mt-4 flex gap-2">
                                @if($tab === 'all' || $tab === 'open')
                                    @can('close', $issue)
                                        <button wire:click="closeIssue({{ $issue->id }})"
                                            class="flex-1 flex items-center justify-center gap-2 h-11 px-4 rounded-xl font-semibold text-sm transition-all duration-200 text-success bg-success/10 hover:bg-success/20">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Close
                                        </button>
                                    @endcan
                                @else
                                    @can('reopen', $issue)
                                        <button wire:click="reopenIssue({{ $issue->id }})"
                                            class="flex-1 flex items-center justify-center gap-2 h-11 px-4 rounded-xl font-semibold text-sm transition-all duration-200 text-primary bg-primary/10 hover:bg-primary/20">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Reopen
                                        </button>
                                    @endcan
                                @endif
                                <button
                                    wire:click="openQuickView({{ $issue->id }})"
                                    class="flex-1 mobile-action-button flex items-center justify-center gap-2 h-11 px-4 rounded-xl font-semibold text-sm transition-all duration-200 text-primary bg-primary/10 hover:bg-primary/20">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="glass rounded-2xl p-4">
            {{ $issues->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card flex flex-col items-center justify-center py-12 text-center">
            <div class="relative mb-4">
                <div class="absolute inset-0 animate-pulse rounded-full bg-primary/20 blur-xl"></div>
                <svg class="relative h-16 w-16 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-text font-heading">No issues found</h3>
            <p class="mt-1.5 text-sm text-muted">Get started by creating a new issue.</p>
            @can('create', \App\Models\Issue::class)
                <a href="{{ route('issues.create') }}" class="btn btn-primary mt-4">
                    <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Issue
                </a>
            @endcan
        </div>
    @endif
    @elseif($viewMode === 'kanban')
        <!-- Live region for accessibility announcements -->
        <div
            x-data="{ announce: '' }"
            @announce.window="announce = $event.detail; setTimeout(() => announce = '', 1000)"
            aria-live="polite"
            aria-atomic="true"
            class="sr-only"
            x-text="announce"
        ></div>

        <!-- Kanban Board View -->
        <x-kanban-board
            :openIssues="$this->kanbanIssues['open']"
            :progressIssues="$this->kanbanIssues['in_progress']"
            :closedIssues="$this->kanbanIssues['closed']"
        />
    @endif

    <!-- Save Filter Modal -->
    <div
        x-show="showSaveFilterModal"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="closeSaveFilterModal"
    >
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative w-full max-w-md rounded-2xl border border-border bg-surface p-6 shadow-xl">
            <h3 class="text-lg font-semibold text-text mb-4">Save Current Filters</h3>

            <form wire:submit="saveFilter">
                <div>
                    <label class="mb-1 block text-sm font-medium text-text">Filter Name</label>
                    <input
                        type="text"
                        wire:model="savedFilterName"
                        class="w-full rounded-xl border border-border bg-surface-2 px-4 py-2.5 text-text placeholder-muted focus:border-primary focus:ring-2 focus:ring-primary/20"
                        placeholder="e.g., High Priority Front Desk Issues"
                        autofocus
                    />
                    <x-input-error :messages="$errors->get('savedFilterName')" class="mt-1" />
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button
                        type="button"
                        wire:click="closeSaveFilterModal"
                        class="rounded-xl px-4 py-2 text-sm font-medium text-text hover:bg-surface-2 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Save Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Keyboard Shortcuts Modal -->
    <div
        x-show="showKeyboardShortcuts"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
        @click.self="showKeyboardShortcuts = false"
        @keydown.escape="showKeyboardShortcuts = false"
    >
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="relative w-full max-w-lg rounded-2xl border border-border bg-surface p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-text">Keyboard Shortcuts</h3>
                <button @click="showKeyboardShortcuts = false" class="p-1 text-muted hover:text-text">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Search -->
                <div class="flex items-center justify-between rounded-lg bg-surface-2 p-3">
                    <span class="text-sm text-text">Focus search</span>
                    <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">/</kbd>
                </div>

                <!-- Create Issue -->
                @if(auth()->check() && auth()->user()->can('create', \App\Models\Issue::class))
                <div class="flex items-center justify-between rounded-lg bg-surface-2 p-3">
                    <span class="text-sm text-text">Create new issue</span>
                    <div class="flex gap-1">
                        <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">C</kbd>
                        <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">N</kbd>
                    </div>
                </div>
                @endif

                <!-- Select All -->
                @if(auth()->check())
                <div class="flex items-center justify-between rounded-lg bg-surface-2 p-3">
                    <span class="text-sm text-text">Select all</span>
                    <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">A</kbd>
                </div>
                @endif

                <!-- Clear Selection -->
                @if(auth()->check())
                <div class="flex items-center justify-between rounded-lg bg-surface-2 p-3">
                    <span class="text-sm text-text">Clear selection</span>
                    <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">Esc</kbd>
                </div>
                @endif

                <!-- Show Shortcuts -->
                <div class="flex items-center justify-between rounded-lg bg-surface-2 p-3">
                    <span class="text-sm text-text">Show this help</span>
                    <kbd class="rounded bg-surface px-2 py-1 text-xs font-mono text-muted border border-border">?</kbd>
                </div>
            </div>

            <div class="mt-6 text-center">
                <button @click="showKeyboardShortcuts = false" class="btn btn-primary w-full">
                    Got it
                </button>
            </div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <x-quick-view-modal
        :issue="$this->quickViewIssue"
        :show="$showQuickView"
    />
</div>
