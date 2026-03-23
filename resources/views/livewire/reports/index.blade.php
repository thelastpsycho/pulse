<div>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-text">Reports</h1>
                <p class="text-muted">View and analyze issue data with detailed reports</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-6">
            <div class="p-4">
                <div class="flex flex-wrap gap-4 items-end">
                    <div>
                        <x-input-label for="preset" value="Date Range" />
                        <select
                            id="preset"
                            wire:model.live="dateRangePreset"
                            class="mt-1 bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary min-w-[180px]"
                        >
                            @foreach($presetOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($dateRangePreset === 'custom')
                        <div>
                            <x-input-label for="date_from" value="From Date" />
                            <input
                                type="date"
                                id="date_from"
                                wire:model.live="dateFrom"
                                class="mt-1 bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary"
                            >
                        </div>
                        <div>
                            <x-input-label for="date_to" value="To Date" />
                            <input
                                type="date"
                                id="date_to"
                                wire:model.live="dateTo"
                                class="mt-1 bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary"
                            >
                        </div>
                    @endif
                    <div>
                        <x-input-label for="category" value="Category" />
                        <select
                            id="category"
                            wire:model.live="selectedCategoryId"
                            class="mt-1 bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary"
                        >
                            <option value="">All Categories</option>
                            @foreach($availableCategories as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-sm text-muted mt-2">
                    Showing issues from <strong>{{ $report['date_from'] }}</strong> to <strong>{{ $report['date_to'] }}</strong>
                    @if($report['category_id'])
                        @php $categoryName = $availableCategories[$report['category_id']] ?? 'Unknown'; @endphp
                        in category <strong>{{ $categoryName }}</strong>
                    @endif
                </p>
            </div>
        </div>

        @if($report)
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="card">
                    <div class="p-6">
                        <p class="text-sm text-muted mb-1">Total Issues</p>
                        <p class="text-3xl font-bold text-text">{{ $report['total_issues'] }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="p-6">
                        <p class="text-sm text-muted mb-1">Open Issues</p>
                        <p class="text-3xl font-bold text-warning">{{ $report['by_status']['open'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="p-6">
                        <p class="text-sm text-muted mb-1">Closed Issues</p>
                        <p class="text-3xl font-bold text-accent">{{ $report['by_status']['closed'] ?? 0 }}</p>
                    </div>
                </div>
                <div class="card">
                    <div class="p-6">
                        <p class="text-sm text-muted mb-1">Avg. Close Time</p>
                        <p class="text-3xl font-bold text-primary">{{ $report['avg_close_time_hours'] }}h</p>
                    </div>
                </div>
            </div>

            <!-- Daily Trend Chart -->
            <div class="card mb-6">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-semibold text-text">Daily Trend</h3>
                </div>
                <div class="p-6">
                    @if($report['daily_trend']->count() > 0)
                        <div class="flex items-end justify-between gap-1 h-48 overflow-x-auto">
                            @php
                                $maxCount = $report['daily_trend']->max() ?? 1;
                                $dates = $report['daily_trend']->sortKeys();
                            @endphp
                            @foreach($dates as $date => $count)
                                @php
                                    $height = max(($count / $maxCount) * 100, 2);
                                    $dateLabel = \Carbon\Carbon::parse($date)->format('M j');
                                @endphp
                                <div class="flex-1 flex flex-col items-center gap-1 min-w-[30px]">
                                    <span class="text-xs font-medium text-text">{{ $count }}</span>
                                    <div class="w-full bg-primary rounded-t hover:bg-primary/80 transition-smooth cursor-default" style="height: {{ $height }}%; min-height: 4px;" title="{{ $date }}: {{ $count }} issues"></div>
                                    <span class="text-xs text-muted">{{ $dateLabel }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted">No data available for the selected date range</p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- By Department -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="font-semibold text-text">By Department</h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_department']->count() > 0)
                            <div class="space-y-3">
                                @foreach($report['by_department'] as $department => $count)
                                    @php
                                        $maxDept = $report['by_department']->max() ?? 1;
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-text">{{ $department }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                                <div class="h-full bg-primary rounded-full" style="width: {{ ($count / $maxDept) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-text w-8 text-right">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- By Issue Type -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="font-semibold text-text">By Issue Type</h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_issue_type']->count() > 0)
                            <div class="space-y-3">
                                @foreach($report['by_issue_type'] as $type => $count)
                                    @php
                                        $maxType = $report['by_issue_type']->max() ?? 1;
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-text">{{ $type }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                                <div class="h-full bg-accent rounded-full" style="width: {{ ($count / $maxType) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-text w-8 text-right">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- By Category -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="font-semibold text-text">By Category</h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_category']->count() > 0)
                            <div class="space-y-3">
                                @foreach($report['by_category'] as $category => $count)
                                    @php
                                        $maxCat = $report['by_category']->max() ?? 1;
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-text">{{ $category }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                                <div class="h-full bg-purple-500 rounded-full" style="width: {{ ($count / $maxCat) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-text w-8 text-right">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- By Priority -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="font-semibold text-text">By Priority</h3>
                    </div>
                    <div class="p-6">
                        @if($report['by_priority']->count() > 0)
                            <div class="space-y-3">
                                @foreach($report['by_priority'] as $priority => $count)
                                    @php
                                        $maxPriority = $report['by_priority']->max() ?? 1;
                                        $priorityColors = [
                                            'low' => 'bg-green-500',
                                            'medium' => 'bg-yellow-500',
                                            'high' => 'bg-orange-500',
                                            'critical' => 'bg-red-500',
                                        ];
                                        $colorClass = $priorityColors[$priority] ?? 'bg-gray-500';
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-text capitalize">{{ $priority }}</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                                <div class="h-full {{ $colorClass }} rounded-full" style="width: {{ ($count / $maxPriority) * 100 }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-text w-8 text-right">{{ $count }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-muted">No data available</p>
                        @endif
                    </div>
                </div>

                <!-- Status Distribution -->
                <div class="card">
                    <div class="px-6 py-4 border-b border-border">
                        <h3 class="font-semibold text-text">Status Distribution</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-text">Open</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-warning rounded-full" style="width: {{ (($report['by_status']['open'] ?? 0) / max($report['total_issues'], 1)) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-text w-8 text-right">{{ $report['by_status']['open'] ?? 0 }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-text">Closed</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-32 h-2 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-accent rounded-full" style="width: {{ (($report['by_status']['closed'] ?? 0) / max($report['total_issues'], 1)) * 100 }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-text w-8 text-right">{{ $report['by_status']['closed'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="card">
                <div class="px-6 py-4 border-b border-border">
                    <h3 class="font-semibold text-text">Quick Links</h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('reports.logbook') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            View Logbook
                        </a>
                        <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            All Issues
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
