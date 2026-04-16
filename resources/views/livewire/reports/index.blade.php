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

        .chart-clickable {
            cursor: pointer;
        }
        .chart-clickable:hover {
            opacity: 0.8;
        }
    </style>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Ambient Background -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-1/4 left-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-accent/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <!-- Header -->
    <div class="mb-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-accent/20 to-primary/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-semibold text-text">
                        <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">Reports</span>
                    </h1>
                </div>
                <p class="text-sm text-muted ml-13">Analyze issue data with detailed insights and trends</p>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="glass-card rounded-xl mb-6">
        <div class="p-4 border-b border-border/50">
            <h3 class="text-base font-medium text-text flex items-center gap-2">
                <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Quick Links
            </h3>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('reports.logbook') }}" class="flex items-center gap-3 p-4 rounded-xl bg-surface-2/50 hover:bg-accent/10 hover:border-accent/30 border border-transparent transition-all group cursor-pointer">
                <div class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-text">Logbook</p>
                    <p class="text-xs text-muted">Printable report</p>
                </div>
                <svg class="w-5 h-5 text-muted group-hover:text-accent group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <a href="{{ route('issues.index') }}" class="flex items-center gap-3 p-4 rounded-xl bg-surface-2/50 hover:bg-primary/10 hover:border-primary/30 border border-transparent transition-all group cursor-pointer">
                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                    <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-medium text-text">All Issues</p>
                    <p class="text-xs text-muted">View & manage</p>
                </div>
                <svg class="w-5 h-5 text-muted group-hover:text-primary group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Date Range Preset -->
            <div>
                <label class="block text-sm font-medium text-text mb-1.5">Date Range</label>
                <select wire:model.live="dateRangePreset"
                        class="w-full bg-surface-2 border border-border text-text rounded-lg px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                    @foreach($presetOptions as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            @if($dateRangePreset === 'custom')
                <!-- Custom Date Range -->
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">From</label>
                    <input type="date" wire:model.live="dateFrom"
                           class="w-full bg-surface-2 border border-border text-text rounded-lg px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">To</label>
                    <input type="date" wire:model.live="dateTo"
                           class="w-full bg-surface-2 border border-border text-text rounded-lg px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                </div>
            @endif

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-text mb-1.5">Category</label>
                <select wire:model.live="selectedCategoryId"
                        class="w-full bg-surface-2 border border-border text-text rounded-lg px-3 py-2 text-sm focus:border-accent focus:ring-2 focus:ring-accent/20 transition-all">
                    <option value="">All Categories</option>
                    @foreach($availableCategories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Date Range Summary -->
        <div class="mt-3 flex items-center gap-2 text-sm">
            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-muted">
                Showing data from <strong class="text-text">{{ $report['date_from'] }}</strong> to <strong class="text-text">{{ $report['date_to'] }}</strong>
                @if($report['category_id'])
                    @php $categoryName = $availableCategories[$report['category_id']] ?? 'Unknown'; @endphp
                    in <strong class="text-accent">{{ $categoryName }}</strong>
                @endif
            </span>
        </div>
    </div>

    @if($report)
        <!-- Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Issues -->
            <a href="{{ route('issues.index', ['date_from' => $report['date_from'], 'date_to' => $report['date_to']]) }}" target="_blank" class="glass-card rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300 block chart-clickable">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted mb-1">Total Issues</p>
                        <p class="text-3xl font-bold text-text">{{ $report['total_issues'] }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 h-2 bg-surface-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-accent to-primary rounded-full" style="width: 100%"></div>
                </div>
            </a>

            <!-- Open Issues -->
            <a href="{{ route('issues.index', ['tab' => 'open', 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) }}" target="_blank" class="glass-card rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300 block chart-clickable">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted mb-1">Open Issues</p>
                        <p class="text-3xl font-bold text-warning">{{ $report['by_status']['open'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning/20 to-warning/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 h-2 bg-surface-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-warning to-amber-600 rounded-full" style="width: {{ max((($report['by_status']['open'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                </div>
            </a>

            <!-- Closed Issues -->
            <a href="{{ route('issues.index', ['tab' => 'closed', 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) }}" target="_blank" class="glass-card rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300 block chart-clickable">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted mb-1">Closed Issues</p>
                        <p class="text-3xl font-bold text-success">{{ $report['by_status']['closed'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="mt-3 h-2 bg-surface-2 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-success to-emerald-600 rounded-full" style="width: {{ max((($report['by_status']['closed'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                </div>
            </a>

            <!-- Avg Close Time -->
            <div class="glass-card rounded-xl p-4 hover:scale-[1.02] transition-transform duration-300">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted mb-1">Avg. Close Time</p>
                        <p class="text-3xl font-bold text-primary">{{ $report['avg_close_time_hours'] }}<span class="text-base">h</span></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="mt-3 text-xs text-muted">Average time to resolve issues</p>
            </div>
        </div>

        <!-- Daily Trend Chart -->
        <div class="glass-card rounded-xl mb-6 overflow-hidden">
            <div class="p-4 border-b border-border/50">
                <h3 class="text-base font-medium text-text flex items-center gap-2">
                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                    </svg>
                    Daily Trend
                </h3>
            </div>
            <div class="p-4">
                @if($report['daily_trend']->count() > 0)
                    @php
                        $maxCount = $report['daily_trend']->max() ?? 1;
                        $dates = $report['daily_trend']->sortKeys();
                        $dataPoints = [];
                        $chartWidth = 800;
                        $chartHeight = 200;
                        $paddingLeft = 40;
                        $paddingRight = 40;
                        $paddingTop = 20;
                        $paddingBottom = 30;
                        $usableWidth = $chartWidth - $paddingLeft - $paddingRight;
                        $usableHeight = $chartHeight - $paddingTop - $paddingBottom;

                        foreach($dates as $date => $count) {
                            $dateLabel = \Carbon\Carbon::parse($date)->format('M j');
                            $isWeekend = in_array(\Carbon\Carbon::parse($date)->dayOfWeek, [0, 6]);
                            $dataPoints[] = [
                                'date' => $date,
                                'label' => $dateLabel,
                                'count' => $count,
                                'isWeekend' => $isWeekend
                            ];
                        }

                        $pointCount = count($dataPoints);
                        if ($pointCount > 1) {
                            $stepX = $usableWidth / ($pointCount - 1);
                        } else {
                            $stepX = $usableWidth;
                        }

                        // Determine which labels to show (grouping)
                        $labelInterval = max(1, floor($pointCount / 8));
                        $showLabels = [];
                        for ($i = 0; $i < $pointCount; $i += $labelInterval) {
                            $showLabels[$i] = true;
                        }
                        // Always show the last label
                        $showLabels[$pointCount - 1] = true;
                    @endphp

                    <div class="relative">
                        <svg viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" class="w-full h-56 overflow-visible" preserveAspectRatio="xMidYMid meet">
                            <defs>
                                <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:rgb(var(--color-success));stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:rgb(var(--color-primary));stop-opacity:1" />
                                </linearGradient>
                                <linearGradient id="areaGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:rgb(var(--color-success));stop-opacity:0.2" />
                                    <stop offset="100%" style="stop-color:rgb(var(--color-success));stop-opacity:0" />
                                </linearGradient>
                            </defs>

                            <!-- Grid lines -->
                            @for($i = 0; $i <= 4; $i++)
                                @php
                                    $y = $paddingTop + ($usableHeight * $i / 4);
                                    $labelValue = round($maxCount * (4 - $i) / 4);
                                @endphp
                                <line x1="{{ $paddingLeft }}" y1="{{ $y }}" x2="{{ $chartWidth - $paddingRight }}" y2="{{ $y }}"
                                      stroke="rgb(var(--border) / 0.3)" stroke-width="1" stroke-dasharray="4 4"/>
                                <text x="{{ $paddingLeft - 10 }}" y="{{ $y + 4 }}"
                                      text-anchor="end" class="text-xs" fill="rgb(var(--muted))">{{ $labelValue }}</text>
                            @endfor

                            <!-- Area under the line -->
                            @php
                                $pathD = 'M ' . ($paddingLeft) . ' ' . ($paddingTop + $usableHeight);
                                $linePathD = '';
                                foreach($dataPoints as $index => $point) {
                                    $x = $paddingLeft + ($pointCount > 1 ? $index * $stepX : $usableWidth / 2);
                                    $y = $paddingTop + $usableHeight - (($point['count'] / $maxCount) * $usableHeight);
                                    if ($index === 0) {
                                        $linePathD .= 'M ' . $x . ' ' . $y;
                                    } else {
                                        $linePathD .= ' L ' . $x . ' ' . $y;
                                    }
                                    $pathD .= ' L ' . $x . ' ' . $y;
                                }
                                $pathD .= ' L ' . ($paddingLeft + ($pointCount > 1 ? ($pointCount - 1) * $stepX : $usableWidth / 2)) . ' ' . ($paddingTop + $usableHeight);
                                $pathD .= ' Z';
                            @endphp
                            <path d="{{ $pathD }}" fill="url(#areaGradient)" />

                            <!-- Smooth line with curve -->
                            <path d="{{ $linePathD }}"
                                  fill="none"
                                  stroke="#22c55e"
                                  stroke-width="2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  class="transition-all duration-300"/>

                            <!-- Data points and labels -->
                            @foreach($dataPoints as $index => $point)
                                @php
                                    $x = $paddingLeft + ($pointCount > 1 ? $index * $stepX : $usableWidth / 2);
                                    $y = $paddingTop + $usableHeight - (($point['count'] / $maxCount) * $usableHeight);
                                    $showLabel = isset($showLabels[$index]);
                                @endphp
                                <g class="data-point group">
                                    <circle cx="{{ $x }}" cy="{{ $y }}" r="4"
                                            fill="white"
                                            stroke="#22c55e"
                                            stroke-width="2"
                                            class="transition-all duration-200 group-hover:r-6"/>
                                    <circle cx="{{ $x }}" cy="{{ $y }}" r="2"
                                            fill="#22c55e"
                                            class="transition-all duration-200 group-hover:r-3"/>
                                    <!-- Tooltip -->
                                    <g class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                        <rect x="{{ $x - 30 }}" y="{{ $y - 35 }}" width="60" height="24" rx="4"
                                              fill="rgb(var(--surface-2))" stroke="rgb(var(--border))" stroke-width="1"/>
                                        <text x="{{ $x }}" y="{{ $y - 19 }}" text-anchor="middle"
                                              class="text-xs font-bold" fill="rgb(var(--text))">{{ $point['count'] }}</text>
                                    </g>
                                    @if($showLabel)
                                        <!-- Date label -->
                                        <text x="{{ $x }}" y="{{ $chartHeight - 8 }}"
                                              text-anchor="middle"
                                              class="text-xs {{ $point['isWeekend'] ? 'opacity-50' : '' }}"
                                              fill="rgb(var(--muted))">{{ $point['label'] }}</text>
                                    @endif
                                </g>
                            @endforeach
                        </svg>
                    </div>
                @else
                    <div class="h-56 flex items-center justify-center">
                        <p class="text-muted">No data available for the selected date range</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Breakdown Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
            <!-- By Department -->
            <div class="glass-card rounded-xl">
                <div class="p-4 border-b border-border/50">
                    <h3 class="text-base font-medium text-text flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        By Department
                    </h3>
                </div>
                <div class="p-4">
                    @if($report['by_department']->count() > 0)
                        @php $maxDept = $report['by_department']->max(function($item) { return is_array($item) ? $item['count'] : $item; }) ?? 1; @endphp
                        <div class="space-y-4">
                            @foreach($report['by_department'] as $department => $data)
                                @php
                                    $count = is_array($data) ? $data['count'] : $data;
                                    $deptId = is_array($data) ? $data['id'] : null;
                                    $filterUrl = $deptId ? route('issues.index', ['department_id' => $deptId, 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) : '#';
                                @endphp
                                <a href="{{ $filterUrl }}" target="_blank" class="block group chart-clickable">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-text group-hover:text-accent transition-colors">{{ $department }}</span>
                                        <span class="text-sm font-bold text-text">{{ $count }}</span>
                                    </div>
                                    <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-primary to-accent rounded-full transition-all duration-500 group-hover:from-primary/80 group-hover:to-accent/80"
                                             style="width: {{ ($count / $maxDept) * 100 }}%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>

            <!-- By Issue Type -->
            <div class="glass-card rounded-xl">
                <div class="p-4 border-b border-border/50">
                    <h3 class="text-base font-medium text-text flex items-center gap-2">
                        <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        By Issue Type
                    </h3>
                </div>
                <div class="p-4">
                    @if($report['by_issue_type']->count() > 0)
                        @php $maxType = $report['by_issue_type']->max(function($item) { return is_array($item) ? $item['count'] : $item; }) ?? 1; @endphp
                        <div class="space-y-4">
                            @foreach($report['by_issue_type'] as $type => $data)
                                @php
                                    $count = is_array($data) ? $data['count'] : $data;
                                    $typeId = is_array($data) ? $data['id'] : null;
                                    $filterUrl = $typeId ? route('issues.index', ['issue_type_id' => $typeId, 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) : '#';
                                @endphp
                                <a href="{{ $filterUrl }}" target="_blank" class="block group chart-clickable">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-text group-hover:text-accent transition-colors">{{ $type }}</span>
                                        <span class="text-sm font-bold text-text">{{ $count }}</span>
                                    </div>
                                    <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-accent to-primary rounded-full transition-all duration-500 group-hover:from-accent/80 group-hover:to-primary/80"
                                             style="width: {{ ($count / $maxType) * 100 }}%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>

            <!-- By Category -->
            <div class="glass-card rounded-xl">
                <div class="p-4 border-b border-border/50">
                    <h3 class="text-base font-medium text-text flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                        By Category
                    </h3>
                </div>
                <div class="p-4">
                    @if($report['by_category']->count() > 0)
                        @php $maxCat = $report['by_category']->max(function($item) { return is_array($item) ? $item['count'] : $item; }) ?? 1; @endphp
                        <div class="space-y-4">
                            @foreach($report['by_category'] as $category => $data)
                                @php
                                    $count = is_array($data) ? $data['count'] : $data;
                                    $categoryId = is_array($data) ? $data['id'] : null;
                                    $filterUrl = $categoryId ? route('issues.index', ['category_id' => $categoryId, 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) : '#';
                                @endphp
                                <a href="{{ $filterUrl }}" target="_blank" class="block group chart-clickable">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-text group-hover:text-purple-400 transition-colors">{{ $category }}</span>
                                        <span class="text-sm font-bold text-text">{{ $count }}</span>
                                    </div>
                                    <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-500 group-hover:from-purple-500/80 group-hover:to-purple-600/80"
                                             style="width: {{ ($count / $maxCat) * 100 }}%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>

            <!-- By Priority -->
            <div class="glass-card rounded-xl">
                <div class="p-4 border-b border-border/50">
                    <h3 class="text-base font-medium text-text flex items-center gap-2">
                        <svg class="w-4 h-4 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        By Priority
                    </h3>
                </div>
                <div class="p-4">
                    @if($report['by_priority']->count() > 0)
                        @php
                            $maxPriority = $report['by_priority']->max() ?? 1;
                            $priorityColors = [
                                'urgent' => 'from-danger to-red-600',
                                'high' => 'from-warning to-orange-600',
                                'medium' => 'from-accent to-blue-600',
                                'low' => 'from-success to-emerald-600',
                            ];
                        @endphp
                        <div class="space-y-4">
                            @foreach($report['by_priority'] as $priority => $count)
                                @php
                                    $filterUrl = route('issues.index', ['priority' => $priority, 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]);
                                @endphp
                                <a href="{{ $filterUrl }}" target="_blank" class="block group chart-clickable">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-text capitalize group-hover:text-danger transition-colors">{{ $priority }}</span>
                                        <span class="text-sm font-bold text-text">{{ $count }}</span>
                                    </div>
                                    <div class="h-3 bg-surface-2 rounded-full overflow-hidden">
                                        <div class="h-full bg-gradient-to-r {{ $priorityColors[$priority] ?? 'from-muted to-gray-500' }} rounded-full transition-all duration-500"
                                             style="width: {{ ($count / $maxPriority) * 100 }}%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-muted text-center py-4">No data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="glass-card rounded-xl">
            <div class="p-4 border-b border-border/50">
                <h3 class="text-base font-medium text-text flex items-center gap-2">
                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                    </svg>
                    Status Distribution
                </h3>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Open -->
                    <a href="{{ route('issues.index', ['tab' => 'open', 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) }}" target="_blank" class="flex items-center gap-4 group chart-clickable cursor-pointer">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-warning/20 to-warning/5 flex items-center justify-center">
                            <span class="text-2xl font-bold text-warning">{{ $report['by_status']['open'] ?? 0 }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text group-hover:text-warning transition-colors">Open Issues</p>
                            <div class="mt-2 h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-warning to-amber-600 rounded-full"
                                     style="width: {{ max((($report['by_status']['open'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                            </div>
                        </div>
                    </a>
                    <!-- Closed -->
                    <a href="{{ route('issues.index', ['tab' => 'closed', 'date_from' => $report['date_from'], 'date_to' => $report['date_to']]) }}" target="_blank" class="flex items-center gap-4 group chart-clickable cursor-pointer">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-success/20 to-success/5 flex items-center justify-center">
                            <span class="text-2xl font-bold text-success">{{ $report['by_status']['closed'] ?? 0 }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text group-hover:text-success transition-colors">Closed Issues</p>
                            <div class="mt-2 h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-success to-emerald-600 rounded-full"
                                     style="width: {{ max((($report['by_status']['closed'] ?? 0) / max($report['total_issues'], 1)) * 100, 2) }}%"></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
