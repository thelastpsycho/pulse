<div>
    <style>
        .glass-card {
            background: rgba(var(--surface-1-rgb, 255 255 255), 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(var(--border-rgb, 200 200 200), 0.3);
        }

        .trend-bar {
            transition: all 0.3s ease;
        }
        .trend-bar:hover {
            filter: brightness(1.1);
            transform: scaleY(1.02);
            transform-origin: bottom;
        }
    </style>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Ambient Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
            <div class="absolute top-0 right-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-1/4 left-0 w-80 h-80 bg-accent/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Header -->
        <div class="mb-8 ">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-text">
                            <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">Yearly Report</span>
                        </h1>
                    </div>
                    <p class="text-muted ml-15">Annual overview and trend analysis</p>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg">
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
                    <div class="mt-4 h-2 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-accent to-primary rounded-full" style="width: 100%"></div>
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
                    <div class="mt-4 h-2 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-warning to-amber-600 rounded-full" style="width: {{ max((($report['by_status']['open'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
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
                    <div class="mt-4 h-2 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-success to-emerald-600 rounded-full" style="width: {{ max((($report['by_status']['closed'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
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
                    <p class="mt-4 text-xs text-muted">Average time to resolve</p>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="glass-card rounded-2xl mb-8  2 overflow-hidden">
                <div class="p-6 border-b border-border/50">
                    <h3 class="text-lg font-semibold text-text flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                        </svg>
                        Monthly Trend - {{ $report['year'] }}
                    </h3>
                </div>
                <div class="p-6">
                    @php $maxCount = $report['by_month']->max() ?? 1; @endphp
                    <div class="flex items-end justify-between gap-3 h-64">
                        @for($i = 1; $i <= 12; $i++)
                            @php
                                $count = $report['by_month'][$i] ?? 0;
                                $height = max(($count / $maxCount) * 100, 4);
                                $monthName = now()->setDateTime($report['year'], $i, 1, 0, 0)->format('M');
                                $isCurrentMonth = now()->year == $report['year'] && now()->month == $i;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-2 group">
                                <span class="text-xs font-bold text-text opacity-0 group-hover:opacity-100 transition-opacity">{{ $count }}</span>
                                <div class="w-full rounded-t-lg trend-bar cursor-default relative {{ $isCurrentMonth ? 'ring-2 ring-accent ring-offset-2' : '' }}"
                                     style="height: {{ $height }}%; min-height: 8px; background: linear-gradient(to top, var(--color-accent), var(--color-primary));"
                                     title="{{ $monthName }}: {{ $count }} issues">
                                    <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent rounded-t-lg"></div>
                                </div>
                                <span class="text-xs {{ $isCurrentMonth ? 'font-bold text-accent' : 'text-muted' }}">{{ $monthName }}</span>
                            </div>
                        @endfor
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
                                                 style="width: {{ ($count / $maxDept) * 100 }}%"></div>
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
                                                 style="width: {{ ($count / $maxType) * 100 }}%"></div>
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
