<div>
    <style>
        .glass-card {
            background: rgba(var(--surface-1-rgb, 255 255 255), 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(var(--border-rgb, 200 200 200), 0.3);
        }

        /* Print styles */
        @media print {
            .no-print { display: none !important; }
            .glass-card { background: white; backdrop-filter: none; border: 1px solid #ddd; }
            body { background: white; }
        }
    </style>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Ambient Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none no-print">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 right-0 w-80 h-80 bg-primary/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Header -->
        <div class="mb-8 ">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-text">
                            <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">Logbook</span>
                        </h1>
                    </div>
                    <p class="text-muted ml-15">Printable list of issues with filtering options</p>
                </div>
                <div class="flex items-center gap-3 no-print">
                    <a href="{{ $this->getExportUrl() }}" target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-accent to-primary text-white font-semibold rounded-xl shadow-lg shadow-accent/25 hover:shadow-accent/40 hover:scale-105 transition-all duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Export PDF
                    </a>
                    <a href="{{ route('reports.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-surface-2/50 hover:bg-surface-2 border border-border/50 hover:border-accent/30 transition-all group">
                        <svg class="w-5 h-5 text-muted group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="text-sm font-medium text-text">Back</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card rounded-2xl p-6 mb-6  1 no-print">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-2">From Date</label>
                    <input type="date" wire:model.live="dateFrom"
                           class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-2">To Date</label>
                    <input type="date" wire:model.live="dateTo"
                           class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Department</label>
                    <select wire:model.live="departmentId"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Issue Type</label>
                    <select wire:model.live="issueTypeId"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        <option value="">All Types</option>
                        @foreach($issueTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Status</label>
                    <select wire:model.live="status"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        <option value="">All Statuses</option>
                        <option value="open">Open</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
            @if($dateFrom || $dateTo || $departmentId || $issueTypeId || $status)
                <div class="mt-4 flex items-center justify-between">
                    <button wire:click="clearFilters" class="text-sm text-accent hover:text-accent/80 font-medium flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear all filters
                    </button>
                    @if($dateFrom || $dateTo)
                        <p class="text-sm text-muted">
                            @if($dateFrom && $dateTo)
                                {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                            @elseif($dateFrom)
                                From {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}
                            @else
                                Until {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                            @endif
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Results Summary -->
        <div class="mb-4 flex items-center justify-between  2">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-text">
                    <span class="font-bold">{{ $issues->count() }}</span> issue{{ $issues->count() !== 1 ? 's' : '' }} found
                </p>
            </div>
        </div>

        <!-- Issues Table -->
        <div class="glass-card rounded-2xl overflow-hidden  2">
            @if($issues->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-surface-2/50 border-b border-border">
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Issue Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Room</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Check-in</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Check-out</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Source</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-muted uppercase tracking-wider">Nationality</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-muted uppercase tracking-wider">Recovery Cost</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border/50">
                            @foreach($issues as $issue)
                                <tr class="hover:bg-surface-2/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-2 font-mono text-xs font-medium text-muted">
                                            #{{ $issue->id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <p class="text-sm font-semibold text-text">{{ $issue->title }}</p>
                                            @if($issue->description)
                                                <p class="text-xs text-muted mt-1 line-clamp-2">{{ \Illuminate\Support\Str::limit(strip_tags($issue->description), 80) }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-text">{{ $issue->name ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary/10 text-primary text-xs font-medium">
                                            {{ $issue->room_number ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-muted">{{ $issue->checkin_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-muted">{{ $issue->checkout_date?->format('M d, Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-text">{{ $issue->source ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-text">{{ $issue->nationality ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        @if($issue->recovery_cost !== null)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-success/10 text-success text-sm font-semibold">
                                                {{ number_format($issue->recovery_cost) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-16 text-center">
                    <div class="w-20 h-20 mx-auto mb-5 rounded-2xl bg-surface-2 flex items-center justify-center">
                        <svg class="w-10 h-10 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-text mb-2">No issues found</h3>
                    <p class="text-muted">Try adjusting your filters or date range.</p>
                </div>
            @endif
        </div>
    </div>
</div>
