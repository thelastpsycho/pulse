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
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-3xl font-bold text-text font-heading">
                    {{ match($tab) {
                        'all' => 'All Issues',
                        'open' => 'Open Issues',
                        'closed' => 'Closed Issues',
                        default => 'Issues'
                    } }}
                </h1>
                <p class="mt-1 text-muted">
                    {{ $issues->total() }} {{ Str::plural('issue', $issues->total()) }} found
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <!-- Keyboard Shortcuts Help Button -->
            <button
                @click="showKeyboardShortcuts = true"
                class="inline-flex items-center gap-1.5 rounded-xl border border-border/50 bg-surface-2/50 px-3 py-2 text-sm text-muted transition-all duration-200 hover:border-primary/50 hover:text-text"
                title="Keyboard shortcuts (?)"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="hidden sm:inline">Shortcuts</span>
                <kbd class="rounded bg-surface px-1.5 py-0.5 text-xs font-mono">?</kbd>
            </button>
            @can('viewAny', \App\Models\Issue::class)
                <button wire:click="exportCSV" class="btn btn-outline inline-flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </button>
            @endcan
            @can('create', \App\Models\Issue::class)
                <a href="{{ route('issues.create') }}" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Issue
                </a>
            @endcan
        </div>
    </div>

    <!-- Tabs - Enhanced Design -->
    <div class="glass rounded-2xl p-1.5">
        <div class="flex gap-1">
            <button
                wire:click="setTab('all')"
                class="{{ $tab === 'all' ? 'bg-primary text-white shadow-lg' : 'text-muted hover:text-text hover:bg-surface-2' }} relative flex-1 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200">
                <span class="flex items-center justify-center gap-2">
                    All
                    <span class="{{ $tab === 'all' ? 'bg-white/20' : 'bg-muted/20' }} rounded-full px-2 py-0.5 text-xs">{{ \App\Models\Issue::count() }}</span>
                </span>
                @if($tab === 'all')
                    <div class="absolute bottom-1 left-1/2 h-0.5 w-8 -translate-x-1/2 rounded-full bg-white/50"></div>
                @endif
            </button>
            <button
                wire:click="setTab('open')"
                class="{{ $tab === 'open' ? 'bg-success text-white shadow-lg' : 'text-muted hover:text-text hover:bg-surface-2' }} relative flex-1 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200">
                <span class="flex items-center justify-center gap-2">
                    Open
                    <span class="{{ $tab === 'open' ? 'bg-white/20' : 'bg-muted/20' }} rounded-full px-2 py-0.5 text-xs">{{ \App\Models\Issue::open()->count() }}</span>
                </span>
                @if($tab === 'open')
                    <div class="absolute bottom-1 left-1/2 h-0.5 w-8 -translate-x-1/2 rounded-full bg-white/50"></div>
                @endif
            </button>
            <button
                wire:click="setTab('closed')"
                class="{{ $tab === 'closed' ? 'bg-muted text-white shadow-lg' : 'text-muted hover:text-text hover:bg-surface-2' }} relative flex-1 rounded-xl px-4 py-3 text-sm font-semibold transition-all duration-200">
                <span class="flex items-center justify-center gap-2">
                    Closed
                    <span class="{{ $tab === 'closed' ? 'bg-white/20' : 'bg-muted/20' }} rounded-full px-2 py-0.5 text-xs">{{ \App\Models\Issue::closed()->count() }}</span>
                </span>
                @if($tab === 'closed')
                    <div class="absolute bottom-1 left-1/2 h-0.5 w-8 -translate-x-1/2 rounded-full bg-white/50"></div>
                @endif
            </button>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card overflow-hidden">
        <div class="border-b border-border/50 p-4">
            <!-- Saved Filters -->
            @if(count($this->savedFilters) > 0)
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-muted">Saved filters:</span>
                    @foreach($this->savedFilters as $savedFilter)
                        <div class="group relative inline-flex">
                            <button
                                wire:click="loadFilter({{ $savedFilter['id'] }})"
                                class="inline-flex items-center gap-1 rounded-lg border border-border/50 bg-surface-2/50 px-3 py-1.5 text-sm text-text transition-all duration-200 hover:border-primary/50 hover:bg-surface-2"
                            >
                                <svg class="h-3.5 w-3.5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                {{ $savedFilter['name'] }}
                            </button>
                            <button
                                wire:click="deleteFilter({{ $savedFilter['id'] }})"
                                class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-danger text-white opacity-0 transition-opacity duration-200 group-hover:opacity-100"
                                title="Delete filter"
                            >
                                <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endforeach
                </div>
            @endif
            <div class="flex flex-col gap-4 lg:flex-row">
                <!-- Search -->
                <div class="flex-1">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search issues..."
                            class="w-full rounded-xl border border-border/50 bg-surface-2/50 py-3 pl-12 pr-12 text-text placeholder-muted transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                        <kbd class="absolute right-3 top-1/2 -translate-y-1/2 rounded bg-surface px-1.5 py-0.5 text-xs font-mono text-muted border border-border">/</kbd>
                    </div>
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <select wire:model.live="department_id" class="rounded-xl border border-border/50 bg-surface-2/50 px-4 py-3 text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">All Departments</option>
                        @foreach($this->departments as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="issue_type_id" class="rounded-xl border border-border/50 bg-surface-2/50 px-4 py-3 text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">All Types</option>
                        @foreach($this->issueTypes as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="priority" class="rounded-xl border border-border/50 bg-surface-2/50 px-4 py-3 text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">All Priorities</option>
                        @foreach($this->priorities as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="assigned_to" class="rounded-xl border border-border/50 bg-surface-2/50 px-4 py-3 text-text transition-all duration-200 focus:border-primary focus:ring-2 focus:ring-primary/20">
                        <option value="">All Users</option>
                        @foreach($this->users as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <!-- Date Range Picker -->
                    <div x-data="{
                        isOpen: false,
                        dateFrom: '{{ $date_from ?? '' }}',
                        dateTo: '{{ $date_to ?? '' }}',
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
                    }" class="relative">
                        <button
                            @click="isOpen = !isOpen"
                            type="button"
                            class="flex items-center gap-2 rounded-xl border border-border/50 bg-surface-2/50 px-4 py-3 text-text transition-all duration-200 hover:border-primary/50 focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >
                            <svg class="h-5 w-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm" x-text="dateFrom || dateTo ? (dateFrom + ' - ' + dateTo) : 'Date Range'"></span>
                            <svg class="h-4 w-4 text-muted" :class="{'rotate-180': isOpen}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            class="absolute right-0 z-50 mt-2 w-80 rounded-xl border border-border bg-surface shadow-lg"
                            style="display: none;"
                        >
                            <div class="p-4">
                                <h4 class="mb-3 text-sm font-semibold text-text">Date Range</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="mb-1 block text-xs text-muted">From</label>
                                        <input
                                            type="date"
                                            x-model="dateFrom"
                                            class="w-full rounded-lg border border-border bg-surface-2 px-3 py-2 text-sm text-text focus:border-primary focus:outline-none"
                                        />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-xs text-muted">To</label>
                                        <input
                                            type="date"
                                            x-model="dateTo"
                                            class="w-full rounded-lg border border-border bg-surface-2 px-3 py-2 text-sm text-text focus:border-primary focus:outline-none"
                                        />
                                    </div>
                                </div>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button
                                        @click="clear()"
                                        type="button"
                                        class="rounded-lg px-3 py-1.5 text-xs font-medium text-muted hover:bg-surface-2"
                                    >
                                        Clear
                                    </button>
                                    <button
                                        @click="apply()"
                                        type="button"
                                        class="rounded-lg bg-primary px-3 py-1.5 text-xs font-medium text-white hover:bg-primary/90"
                                    >
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Filters -->
            @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="text-sm text-muted">Active filters:</span>
                    @if($search)
                        <span class="badge badge-muted flex items-center gap-1">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $date_from ?? '?' }} - {{ $date_to ?? '?' }}
                        </span>
                    @endif
                    <button wire:click="clearFilters" class="ml-2 text-sm font-medium text-primary hover:underline">Clear all</button>
                    @if($search || $department_id || $issue_type_id || $priority || $assigned_to || $date_from || $date_to)
                        <button wire:click="openSaveFilterModal" class="ml-2 text-sm font-medium text-muted hover:text-text" title="Save current filters">
                            <svg class="inline h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Save
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    @if(count($selectedIssues) > 0)
        <div class="gradient-border rounded-2xl p-4 animate-fade-in">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/20">
                        <svg class="h-5 w-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="font-semibold text-text">{{ count($selectedIssues) }} issue(s) selected</span>
                </div>
                <div class="flex gap-2">
                    @if($tab === 'all' || $tab === 'open')
                        <button wire:click="closeSelected" class="btn btn-success">Close Selected</button>
                    @else
                        <button wire:click="reopenSelected" class="btn btn-primary">Reopen Selected</button>
                    @endif
                    <button wire:click="deleteSelected" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Issues List -->
    @if($issues->count() > 0)
        <div class="space-y-3">
            @foreach($issues as $index => $issue)
                <div wire:key="{{ $issue->id }}" class="issue-card animate-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                    <div class="flex items-start gap-4">
                        <!-- Checkbox -->
                        <div class="pt-1">
                            <label class="relative flex h-5 w-5 cursor-pointer items-center justify-center">
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedIssues"
                                    value="{{ $issue->id }}"
                                    class="peer h-5 w-5 rounded-md border-2 border-border/50 bg-surface-2/50 text-primary transition-all duration-200 focus:ring-2 focus:ring-primary/20"
                                />
                                <svg class="pointer-events-none absolute h-3.5 w-3.5 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                </svg>
                            </label>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <!-- Priority & Meta Badges -->
                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                <span class="badge {{ match($issue->priority) {
                                    'urgent' => 'badge-danger',
                                    'high' => 'badge-warning',
                                    'low' => 'badge-muted',
                                    default => 'badge-muted'
                                } }}">
                                    {{ ucfirst($issue->priority) }}
                                </span>
                                @foreach($issue->departments->take(2) as $department)
                                    <span class="badge badge-muted">{{ $department->name }}</span>
                                @endforeach
                                @if($issue->departments->count() > 2)
                                    <span class="badge badge-muted">+{{ $issue->departments->count() - 2 }}</span>
                                @endif
                                @if($issue->assignedTo)
                                    <span class="flex items-center gap-1 text-xs text-muted">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $issue->assignedTo->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Title & Description -->
                            <a href="{{ route('issues.show', $issue) }}" class="group">
                                <h3 class="text-lg font-semibold text-text transition-colors duration-200 group-hover:text-primary">
                                    {{ $issue->title }}
                                </h3>
                            </a>
                            @if($issue->description)
                                <p class="mt-1 text-sm text-muted line-clamp-2">{{ \Illuminate\Support\Str::limit($issue->description, 150) }}</p>
                            @endif

                            <!-- Meta Info -->
                            <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-muted">
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $issue->createdBy->name ?? 'Unknown' }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $issue->created_at->diffForHumans() }}
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
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-shrink-0 items-center gap-1">
                            <a href="{{ route('issues.show', $issue) }}" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-surface-2 transition-all duration-200" title="View">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                            @can('update', $issue)
                                <a href="{{ route('issues.edit', $issue) }}" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-surface-2 transition-all duration-200" title="Edit">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endcan
                            @if($tab === 'all' || $tab === 'open')
                                @can('close', $issue)
                                    <button wire:click="closeIssue({{ $issue->id }})" class="p-2 text-muted hover:text-success rounded-lg hover:bg-surface-2 transition-all duration-200" title="Close">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @endcan
                            @else
                                @can('reopen', $issue)
                                    <button wire:click="reopenIssue({{ $issue->id }})" class="p-2 text-muted hover:text-primary rounded-lg hover:bg-surface-2 transition-all duration-200" title="Reopen">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                @endcan
                            @endif
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
        <div class="card flex flex-col items-center justify-center py-16 text-center">
            <div class="relative mb-6">
                <div class="absolute inset-0 animate-pulse rounded-full bg-primary/20 blur-2xl"></div>
                <svg class="relative h-24 w-24 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-text font-heading">No issues found</h3>
            <p class="mt-2 text-muted">Get started by creating a new issue.</p>
            @can('create', \App\Models\Issue::class)
                <a href="{{ route('issues.create') }}" class="btn btn-primary mt-6">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Create Issue
                </a>
            @endcan
        </div>
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
</div>
