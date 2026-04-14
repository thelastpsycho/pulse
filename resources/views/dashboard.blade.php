<x-app-layout>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.4; }
            50% { opacity: 0.7; }
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out forwards; }
        .animate-slide-in { animation: slideInRight 0.5s ease-out forwards; }
        .animate-pulse-slow { animation: pulse-slow 3s ease-in-out infinite; }
        .gradient-animate {
            background-size: 200% 200%;
            animation: gradient-shift 8s ease infinite;
        }
        .stagger-1 { animation-delay: 0.1s; opacity: 0; }
        .stagger-2 { animation-delay: 0.2s; opacity: 0; }
        .stagger-3 { animation-delay: 0.3s; opacity: 0; }
        .stagger-4 { animation-delay: 0.4s; opacity: 0; }
        .stagger-5 { animation-delay: 0.5s; opacity: 0; }
        .stagger-6 { animation-delay: 0.6s; opacity: 0; }

        .glass-card {
            background: rgba(var(--surface-1-rgb, 255 255 255), 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(var(--border-rgb, 200 200 200), 0.3);
        }

        .metric-card {
            position: relative;
            overflow: hidden;
        }
        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.4;
        }
        .gradient-accent::before { background: linear-gradient(135deg, var(--color-accent), var(--color-primary)); }
        .gradient-success::before { background: linear-gradient(135deg, #10b981, #059669); }
        .gradient-warning::before { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .gradient-danger::before { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .timeline-line {
            position: absolute;
            left: 20px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--color-accent), var(--color-primary));
        }
    </style>

    <!-- Ambient Background Effects -->
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-accent/10 rounded-full blur-3xl animate-pulse-slow"></div>
        <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-primary/10 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 1.5s;"></div>
        <div class="absolute bottom-0 left-1/3 w-64 h-64 bg-warning/5 rounded-full blur-3xl animate-pulse-slow" style="animation-delay: 3s;"></div>
    </div>

    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Hero Section -->
        <div class="mb-8 animate-fade-in-up">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-accent mb-2 tracking-wide uppercase">
                        {{ now()->format('l, F j, Y') }}
                    </p>
                    <h1 class="text-3xl sm:text-4xl font-semibold text-text mb-2">
                        Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 17 ? 'Afternoon' : 'Evening') }},
                        <span class="bg-gradient-to-r from-accent to-primary bg-clip-text text-transparent">
                            {{ auth()->user()->name }}
                        </span>
                    </h1>
                    <p class="text-sm text-muted max-w-2xl">
                        Here's what's happening with your issues today.
                        <span class="text-text font-medium">{{ \App\Models\Issue::query()->where('status', 'open')->count() }} open</span> issues need your attention.
                    </p>
                </div>
                @can('create', \App\Models\Issue::class)
                    <a href="{{ route('issues.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-accent to-primary text-white font-medium rounded-lg shadow-lg shadow-accent/25 hover:shadow-accent/40 hover:scale-105 transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        New Issue
                    </a>
                @endcan
            </div>
        </div>

        <!-- Metrics Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Open Issues -->
            <div class="metric-card gradient-accent glass-card rounded-2xl p-4 animate-fade-in-up stagger-1 hover:scale-[1.02] transition-transform duration-300">
                <div class="relative">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-accent/20 to-accent/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            +12%
                        </span>
                    </div>
                    <p class="text-sm text-muted mb-1">Open Issues</p>
                    <p class="text-2xl font-semibold text-text">{{ \App\Models\Issue::query()->where('status', 'open')->count() }}</p>
                    <div class="mt-3 h-1.5 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full w-3/4 bg-gradient-to-r from-accent to-primary rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Closed Today -->
            <div class="metric-card gradient-success glass-card rounded-2xl p-4 animate-fade-in-up stagger-2 hover:scale-[1.02] transition-transform duration-300">
                <div class="relative">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-success/20 to-success/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="flex items-center gap-1 text-xs font-medium text-success bg-success/10 px-2 py-1 rounded-full">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                            </svg>
                            +8%
                        </span>
                    </div>
                    <p class="text-sm text-muted mb-1">Closed Today</p>
                    <p class="text-2xl font-semibold text-text">{{ \App\Models\Issue::query()->where('status', 'closed')->whereDate('closed_at', today())->count() }}</p>
                    <div class="mt-3 h-1.5 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full w-1/2 bg-gradient-to-r from-success to-emerald-600 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Urgent Issues -->
            <div class="metric-card gradient-danger glass-card rounded-2xl p-4 animate-fade-in-up stagger-3 hover:scale-[1.02] transition-transform duration-300">
                <div class="relative">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-danger/20 to-danger/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <span class="flex items-center gap-1 text-xs font-medium text-danger bg-danger/10 px-2 py-1 rounded-full animate-pulse">
                            Urgent
                        </span>
                    </div>
                    <p class="text-sm text-muted mb-1">Urgent Issues</p>
                    <p class="text-2xl font-semibold text-text">{{ \App\Models\Issue::query()->where('status', 'open')->where('priority', 'urgent')->count() }}</p>
                    <div class="mt-3 h-1.5 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full w-1/4 bg-gradient-to-r from-danger to-red-600 rounded-full"></div>
                    </div>
                </div>
            </div>

            <!-- Assigned to Me -->
            <div class="metric-card gradient-warning glass-card rounded-2xl p-4 animate-fade-in-up stagger-4 hover:scale-[1.02] transition-transform duration-300">
                <div class="relative">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-warning/20 to-warning/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="flex items-center gap-1 text-xs font-medium text-warning bg-warning/10 px-2 py-1 rounded-full">
                            My Tasks
                        </span>
                    </div>
                    <p class="text-sm text-muted mb-1">Assigned to Me</p>
                    <p class="text-2xl font-semibold text-text">{{ \App\Models\Issue::query()->where('assigned_to_user_id', auth()->id())->where('status', 'open')->count() }}</p>
                    <div class="mt-3 h-1.5 bg-surface-2 rounded-full overflow-hidden">
                        <div class="h-full w-2/3 bg-gradient-to-r from-warning to-amber-600 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Recent Issues & Activity (2/3 width) -->
            <div class="lg:col-span-2 space-y-4">
                <!-- Recent Issues -->
                <div class="glass-card rounded-2xl animate-fade-in-up stagger-5">
                    <div class="p-4 border-b border-border/50 flex items-center justify-between">
                        <div>
                            <h2 class="text-base font-medium text-text">Recent Issues</h2>
                            <p class="text-sm text-muted mt-0.5">Latest updates from your team</p>
                        </div>
                        <a href="{{ route('issues.index') }}" class="text-sm text-accent hover:text-accent/80 font-medium flex items-center gap-1 transition-colors">
                            View all
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                    @php
                        $recentIssues = \App\Models\Issue::query()
                            ->with(['departments', 'createdBy'])
                            ->orderBy('created_at', 'desc')
                            ->limit(6)
                            ->get();
                    @endphp
                    @if($recentIssues->count() > 0)
                        <div class="divide-y divide-border/50">
                            @foreach($recentIssues as $index => $issue)
                                <div class="p-4 flex items-center gap-4 hover:bg-surface-2/50 transition-all duration-200 animate-slide-in" style="animation-delay: {{ $index * 0.1 }}s; opacity: 0;">
                                    <!-- Priority Indicator -->
                                    <div class="w-2 h-12 rounded-full {{ match($issue->priority) {
                                        'urgent' => 'bg-danger',
                                        'high' => 'bg-warning',
                                        'medium' => 'bg-accent',
                                        default => 'bg-muted'
                                    } }}"></div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h3 class="font-medium text-text truncate">{{ $issue->title }}</h3>
                                            <span class="badge {{ $issue->status === 'open' ? 'badge-success' : 'badge-muted' }} text-xs shrink-0">
                                                {{ ucfirst($issue->status) }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 text-sm text-muted">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                </svg>
                                                {{ $issue->departments->pluck('name')->join(', ') ?: 'No department' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                {{ $issue->createdBy->name ?? 'Unknown' }}
                                            </span>
                                            <span>{{ $issue->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('issues.show', $issue) }}"
                                       class="p-2 rounded-lg bg-surface-2 hover:bg-accent hover:text-white transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-surface-2 flex items-center justify-center">
                                <svg class="w-8 h-8 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-base font-medium text-text mb-2">No issues yet</h3>
                            <p class="text-sm text-muted mb-4">Get started by creating your first issue.</p>
                            @can('create', \App\Models\Issue::class)
                                <a href="{{ route('issues.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-white font-medium rounded-lg hover:bg-accent/90 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create Issue
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>

                <!-- Charts Section Placeholder -->
                <div class="glass-card rounded-2xl p-4 animate-fade-in-up stagger-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-base font-medium text-text">Issue Trends</h2>
                            <p class="text-sm text-muted mt-0.5">Weekly activity overview</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-sm font-medium text-accent bg-accent/10 rounded-lg">Week</button>
                            <button class="px-3 py-1.5 text-sm font-medium text-muted hover:bg-surface-2 rounded-lg transition-colors">Month</button>
                            <button class="px-3 py-1.5 text-sm font-medium text-muted hover:bg-surface-2 rounded-lg transition-colors">Year</button>
                        </div>
                    </div>
                    <!-- Chart Placeholder -->
                    <div class="h-64 bg-gradient-to-br from-surface-2/50 to-surface-2/30 rounded-xl flex items-center justify-center border border-dashed border-border/50">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-muted/50 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-sm text-muted">Chart integration ready</p>
                            <p class="text-xs text-muted/70 mt-1">Connect your preferred charting library</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar (1/3 width) -->
            <div class="space-y-4">
                <!-- Quick Actions -->
                <div class="glass-card rounded-2xl p-4 animate-fade-in-up stagger-6">
                    <h2 class="text-base font-medium text-text mb-3">Quick Actions</h2>
                    <div class="space-y-1">
                        @can('create', \App\Models\Issue::class)
                            <a href="{{ route('issues.create') }}"
                               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-2/50 transition-all duration-200 group">
                                <div class="w-9 h-9 rounded-lg bg-accent/10 flex items-center justify-center group-hover:bg-accent/20 transition-colors">
                                    <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-text">New Issue</p>
                                    <p class="text-xs text-muted">Create a new issue</p>
                                </div>
                            </a>
                        @endcan
                        <a href="{{ route('issues.index') }}"
                           class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-2/50 transition-all duration-200 group">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-text">Browse Issues</p>
                                <p class="text-xs text-muted">View all issues</p>
                            </div>
                        </a>
                        @can('viewAny', \App\Models\Issue::class)
                            <a href="{{ route('reports.index') }}"
                               class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-surface-2/50 transition-all duration-200 group">
                                <div class="w-9 h-9 rounded-lg bg-success/10 flex items-center justify-center group-hover:bg-success/20 transition-colors">
                                    <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-text">Reports</p>
                                    <p class="text-xs text-muted">View analytics</p>
                                </div>
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- Priority Breakdown -->
                <div class="glass-card rounded-2xl p-4 animate-fade-in-up stagger-6">
                    <h2 class="text-base font-medium text-text mb-3">Priority Breakdown</h2>
                    <div class="space-y-3">
                        @php
                            $urgentCount = \App\Models\Issue::query()->where('status', 'open')->where('priority', 'urgent')->count();
                            $highCount = \App\Models\Issue::query()->where('status', 'open')->where('priority', 'high')->count();
                            $mediumCount = \App\Models\Issue::query()->where('status', 'open')->where('priority', 'medium')->count();
                            $lowCount = \App\Models\Issue::query()->where('status', 'open')->where('priority', 'low')->count();
                            $totalOpen = $urgentCount + $highCount + $mediumCount + $lowCount ?: 1;
                        @endphp

                        <div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-danger"></span>
                                    <span class="text-text">Urgent</span>
                                </span>
                                <span class="font-medium text-text">{{ $urgentCount }}</span>
                            </div>
                            <div class="h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-danger rounded-full transition-all duration-500" style="width: {{ ($urgentCount / $totalOpen) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-warning"></span>
                                    <span class="text-text">High</span>
                                </span>
                                <span class="font-medium text-text">{{ $highCount }}</span>
                            </div>
                            <div class="h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-warning rounded-full transition-all duration-500" style="width: {{ ($highCount / $totalOpen) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-accent"></span>
                                    <span class="text-text">Medium</span>
                                </span>
                                <span class="font-medium text-text">{{ $mediumCount }}</span>
                            </div>
                            <div class="h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-accent rounded-full transition-all duration-500" style="width: {{ ($mediumCount / $totalOpen) * 100 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between text-sm mb-2">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-muted"></span>
                                    <span class="text-text">Low</span>
                                </span>
                                <span class="font-medium text-text">{{ $lowCount }}</span>
                            </div>
                            <div class="h-2 bg-surface-2 rounded-full overflow-hidden">
                                <div class="h-full bg-muted rounded-full transition-all duration-500" style="width: {{ ($lowCount / $totalOpen) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Timeline -->
                <div class="glass-card rounded-2xl p-4 animate-fade-in-up stagger-6">
                    <h2 class="text-base font-medium text-text mb-3">Recent Activity</h2>
                    @php
                        $activities = \App\Models\ActivityLog::query()
                            ->with(['actor', 'subject'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    @if($activities->count() > 0)
                        <div class="relative">
                            <div class="timeline-line"></div>
                            <div class="space-y-4">
                                @foreach($activities as $activity)
                                    <div class="relative pl-10">
                                        <div class="absolute left-3.5 top-1.5 w-3 h-3 rounded-full bg-accent ring-4 ring-surface-1"></div>
                                        <p class="text-sm text-text">{{ $activity->description }}</p>
                                        <p class="text-xs text-muted mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <p class="text-sm text-muted text-center py-4">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
