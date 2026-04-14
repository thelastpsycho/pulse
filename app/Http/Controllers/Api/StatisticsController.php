<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $totalIssues = Issue::count();
        $openIssues = Issue::open()->count();
        $closedIssues = Issue::closed()->count();
        $urgentIssues = Issue::where('priority', 'urgent')->where('status', '!=', 'closed')->count();

        $issuesByPriority = Issue::where('status', '!=', 'closed')
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority')
            ->toArray();

        $issuesByStatus = Issue::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentActivity = Issue::with(['createdBy', 'assignedTo'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $avgResolutionTime = Issue::whereNotNull('closed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, closed_at)) as avg_hours')
            ->value('avg_hours');

        return response()->json([
            'summary' => [
                'total_issues' => $totalIssues,
                'open_issues' => $openIssues,
                'closed_issues' => $closedIssues,
                'urgent_issues' => $urgentIssues,
                'avg_resolution_hours' => round($avgResolutionTime ?? 0, 2),
            ],
            'by_priority' => $issuesByPriority,
            'by_status' => $issuesByStatus,
            'recent_activity' => $recentActivity,
        ]);
    }

    public function byDepartment(Request $request): JsonResponse
    {
        $request->validate([
            'department_id' => 'nullable|exists:departments,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);

        $query = Department::withCount(['issues as open_issues' => function ($q) {
            $q->where('status', '!=', 'closed');
        }])->withCount(['issues as closed_issues' => function ($q) {
            $q->where('status', 'closed');
        }]);

        if ($request->has('department_id')) {
            $query->where('id', $request->department_id);
        }

        $departments = $query->get();

        $departmentStats = $departments->map(function ($department) {
            $total = $department->open_issues + $department->closed_issues;
            $closureRate = $total > 0 ? round(($department->closed_issues / $total) * 100, 2) : 0;

            return [
                'id' => $department->id,
                'name' => $department->name,
                'code' => $department->code,
                'open_issues' => $department->open_issues,
                'closed_issues' => $department->closed_issues,
                'total_issues' => $total,
                'closure_rate' => $closureRate,
            ];
        });

        return response()->json([
            'departments' => $departmentStats,
            'summary' => [
                'total_departments' => $departments->count(),
                'total_open_issues' => $departments->sum('open_issues'),
                'total_closed_issues' => $departments->sum('closed_issues'),
            ]
        ]);
    }

    public function byUser(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $query = User::with(['roles'])
            ->withCount(['assignedIssues as open_issues' => function ($q) {
                $q->where('status', '!=', 'closed');
            }])
            ->withCount(['assignedIssues as closed_issues' => function ($q) {
                $q->where('status', 'closed');
            }])
            ->withCount(['createdIssues'])
            ->where('is_active', true);

        if ($request->has('user_id')) {
            $query->where('id', $request->user_id);
        }

        if ($request->has('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        $limit = $request->input('limit', 20);
        $users = $query->limit($limit)->get();

        $userStats = $users->map(function ($user) {
            $total = $user->open_issues + $user->closed_issues;
            $completionRate = $total > 0 ? round(($user->closed_issues / $total) * 100, 2) : 0;

            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name'),
                'assigned_open' => $user->open_issues,
                'assigned_closed' => $user->closed_issues,
                'created_count' => $user->created_issues_count,
                'total_assigned' => $total,
                'completion_rate' => $completionRate,
            ];
        });

        return response()->json([
            'users' => $userStats,
            'summary' => [
                'total_users' => $users->count(),
                'total_open_issues' => $users->sum('open_issues'),
                'total_closed_issues' => $users->sum('closed_issues'),
            ]
        ]);
    }

    public function trends(Request $request): JsonResponse
    {
        $request->validate([
            'period' => 'nullable|in:daily,weekly,monthly',
            'limit' => 'nullable|integer|min:1|max:365',
        ]);

        $period = $request->input('period', 'daily');
        $limit = $request->input('limit', 30);

        $dateFormat = match($period) {
            'daily' => '%Y-%m-%d',
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d'
        };

        $createdTrend = Issue::selectRaw("DATE_FORMAT(created_at, '{$dateFormat}') as period, COUNT(*) as count")
            ->where('created_at', '>=', now()->subDays($limit))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $closedTrend = Issue::selectRaw("DATE_FORMAT(closed_at, '{$dateFormat}') as period, COUNT(*) as count")
            ->whereNotNull('closed_at')
            ->where('closed_at', '>=', now()->subDays($limit))
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        $priorityTrend = Issue::selectRaw('priority, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays($limit))
            ->groupBy('priority')
            ->get();

        $departmentTrend = Department::with(['issues' => function ($q) use ($limit) {
            $q->where('issues.created_at', '>=', now()->subDays($limit));
        }])
            ->get()
            ->map(function ($department) {
                return [
                    'department' => $department->name,
                    'issue_count' => $department->issues->count(),
                ];
            })
            ->sortByDesc('issue_count')
            ->values();

        return response()->json([
            'created_trend' => $createdTrend,
            'closed_trend' => $closedTrend,
            'priority_distribution' => $priorityTrend,
            'department_ranking' => $departmentTrend,
            'summary' => [
                'period' => $period,
                'days_analyzed' => $limit,
                'total_created' => $createdTrend->sum('count'),
                'total_closed' => $closedTrend->sum('count'),
            ]
        ]);
    }
}
