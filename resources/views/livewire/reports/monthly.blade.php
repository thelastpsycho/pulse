<div>
    <style>
        .glass-card {
            background: rgba(var(--surface-1-rgb, 255 255 255), 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(var(--border-rgb, 200 200 200), 0.3);
        }
    </style>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Ambient Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute top-1/4 right-0 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Header -->
        <div class="mb-8 ">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-text">
                            <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">Monthly Report</span>
                        </h1>
                    </div>
                    <p class="text-muted ml-15">Detailed breakdown of issues for a specific month</p>
                </div>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-surface-2/50 hover:bg-surface-2 border border-border/50 hover:border-accent/30 transition-all group">
                    <svg class="w-5 h-5 text-muted group-hover:text-accent transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span class="text-sm font-medium text-text">Back to Reports</span>
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-card rounded-2xl p-6 mb-8  1">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Year -->
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Year</label>
                    <select wire:model.live="selectedYear"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        @foreach($availableYears as $year => $label)
                            <option value="{{ $year }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Month -->
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Month</label>
                    <select wire:model.live="selectedMonth"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        @foreach($availableMonths as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-text mb-2">Category</label>
                    <select wire:model.live="selectedCategoryId"
                            class="w-full bg-surface-2 border border-border text-text rounded-xl px-4 py-3 focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                        <option value="">All Categories</option>
                        @foreach($availableCategories as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @if($report)
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                <!-- Total Issues -->
                <div class="glass-card rounded-2xl p-6  1 hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-muted mb-1">Total Issues</p>
                            <p class="text-4xl font-bold text-text">{{ $report['total_issues'] }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                            <svg class="w-7 h-7 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Open Issues -->
                <div class="glass-card rounded-2xl p-6  2 hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-muted mb-1">Open Issues</p>
                            <p class="text-4xl font-bold text-warning">{{ $report['by_status']['open'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-warning/20 to-warning/5 flex items-center justify-center">
                            <svg class="w-7 h-7 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Closed Issues -->
                <div class="glass-card rounded-2xl p-6  3 hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-muted mb-1">Closed Issues</p>
                            <p class="text-4xl font-bold text-success">{{ $report['by_status']['closed'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center">
                            <svg class="w-7 h-7 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Avg Close Time -->
                <div class="glass-card rounded-2xl p-6  3 hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-muted mb-1">Avg. Close Time</p>
                            <p class="text-4xl font-bold text-primary">{{ $report['avg_close_time_hours'] }}<span class="text-lg">h</span></p>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center">
                            <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breakdown Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- By Department -->
                <div class="glass-card rounded-2xl  2">
                    <div class="p-6 border-b border-border/50">
                        <h3 class="text-lg font-semibold text-text flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            By Department
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_department']->count() > 0)
                            @php $maxDept = $report['by_department']->max() ?? 1; @endphp
                            <div class="space-y-4">
                                @foreach($report['by_department'] as $department => $count)
                                    <div class="group">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-text">{{ $department }}</span>
                                            <span class="text-sm font-bold text-text">{{ $count }}</span>
                                        </div>
                                        <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-primary to-accent rounded-full transition-all duration-500 group-hover:from-primary/80 group-hover:to-accent/80"
                                                 style="width: {{ ($count / max($report['total_issues'], 1)) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted text-center py-4">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- By Issue Type -->
                <div class="glass-card rounded-2xl  3">
                    <div class="p-6 border-b border-border/50">
                        <h3 class="text-lg font-semibold text-text flex items-center gap-2">
                            <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            By Issue Type
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_issue_type']->count() > 0)
                            @php $maxType = $report['by_issue_type']->max() ?? 1; @endphp
                            <div class="space-y-4">
                                @foreach($report['by_issue_type'] as $type => $count)
                                    <div class="group">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-text">{{ $type }}</span>
                                            <span class="text-sm font-bold text-text">{{ $count }}</span>
                                        </div>
                                        <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-accent to-primary rounded-full transition-all duration-500 group-hover:from-accent/80 group-hover:to-primary/80"
                                                 style="width: {{ ($count / max($report['total_issues'], 1)) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted text-center py-4">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="glass-card rounded-2xl  2">
                    <div class="p-6 border-b border-border/50">
                        <h3 class="text-lg font-semibold text-text flex items-center gap-2">
                            <svg class="w-5 h-5 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                            </svg>
                            Status Distribution
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-6">
                            <!-- Open -->
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-warning/20 to-warning/5 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-warning">{{ $report['by_status']['open'] ?? 0 }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-text">Open</p>
                                    <div class="mt-2 h-2 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-warning to-amber-600 rounded-full"
                                             style="width: {{ max((($report['by_status']['open'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Closed -->
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-success">{{ $report['by_status']['closed'] ?? 0 }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-text">Closed</p>
                                    <div class="mt-2 h-2 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-success to-emerald-600 rounded-full"
                                             style="width: {{ max((($report['by_status']['closed'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
