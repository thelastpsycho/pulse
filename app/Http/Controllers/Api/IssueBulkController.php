<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Issue\StoreIssueRequest;
use App\Http\Requests\Api\Issue\UpdateIssueRequest;
use App\Http\Resources\IssueResource;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IssueBulkController extends Controller
{
    public function bulkCreate(Request $request): JsonResponse
    {
        $request->validate([
            'issues' => 'required|array|max:100',
            'issues.*.title' => 'required|string|max:255',
            'issues.*.description' => 'nullable|string',
            'issues.*.priority' => 'required|in:urgent,high,medium,low',
            'issues.*.status' => 'nullable|in:open,in_progress,closed,cancelled',
            'issues.*.issue_date' => 'nullable|date',
            'issues.*.assigned_to_user_id' => 'nullable|exists:users,id',
            'issues.*.department_ids' => 'nullable|array',
            'issues.*.department_ids.*' => 'exists:departments,id',
            'issues.*.issue_type_ids' => 'nullable|array',
            'issues.*.issue_type_ids.*' => 'exists:issue_types,id',
        ]);

        $issues = collect($request->issues)->map(function ($issueData) {
            $issue = Issue::create([
                'title' => $issueData['title'],
                'description' => $issueData['description'] ?? null,
                'priority' => $issueData['priority'],
                'status' => $issueData['status'] ?? 'open',
                'issue_date' => $issueData['issue_date'] ?? now(),
                'assigned_to_user_id' => $issueData['assigned_to_user_id'] ?? null,
                'created_by_user_id' => auth()->id(),
            ]);

            if (isset($issueData['department_ids'])) {
                $issue->departments()->sync($issueData['department_ids']);
            }

            if (isset($issueData['issue_type_ids'])) {
                $issue->issueTypes()->sync($issueData['issue_type_ids']);
            }

            return $issue;
        });

        return response()->json([
            'message' => 'Bulk issues created successfully',
            'count' => $issues->count(),
            'issues' => IssueResource::collection($issues)
        ], 201);
    }

    public function bulkUpdate(Request $request): JsonResponse
    {
        $request->validate([
            'issue_ids' => 'required|array|max:100',
            'issue_ids.*' => 'exists:issues,id',
            'updates' => 'required|array',
            'updates.priority' => 'nullable|in:urgent,high,medium,low',
            'updates.status' => 'nullable|in:open,in_progress,closed,cancelled',
            'updates.assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

        $issues = Issue::whereIn('id', $request->issue_ids)->get();
        $updatedCount = 0;

        foreach ($issues as $issue) {
            $this->authorize('update', $issue);

            $updateData = array_filter($request->updates, function ($value) {
                return $value !== null;
            });

            if (!empty($updateData)) {
                $issue->update($updateData);
                $updatedCount++;
            }
        }

        return response()->json([
            'message' => 'Bulk issues updated successfully',
            'updated_count' => $updatedCount,
            'issues' => IssueResource::collection($issues)
        ]);
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        $request->validate([
            'issue_ids' => 'required|array|max:100',
            'issue_ids.*' => 'exists:issues,id',
        ]);

        $issues = Issue::whereIn('id', $request->issue_ids)->get();
        $deletedCount = 0;

        foreach ($issues as $issue) {
            $this->authorize('delete', $issue);
            $issue->delete();
            $deletedCount++;
        }

        return response()->json([
            'message' => 'Bulk issues deleted successfully',
            'deleted_count' => $deletedCount
        ]);
    }

    public function bulkClose(Request $request): JsonResponse
    {
        $request->validate([
            'issue_ids' => 'required|array|max:100',
            'issue_ids.*' => 'exists:issues,id',
            'note' => 'nullable|string'
        ]);

        $issues = Issue::whereIn('id', $request->issue_ids)
            ->where('status', '!=', 'closed')
            ->get();
        $closedCount = 0;

        foreach ($issues as $issue) {
            $this->authorize('update', $issue);
            $issue->close(auth()->id(), $request->note ?? null);
            $closedCount++;
        }

        return response()->json([
            'message' => 'Bulk issues closed successfully',
            'closed_count' => $closedCount,
            'issues' => IssueResource::collection($issues)
        ]);
    }

    public function bulkReopen(Request $request): JsonResponse
    {
        $request->validate([
            'issue_ids' => 'required|array|max:100',
            'issue_ids.*' => 'exists:issues,id',
        ]);

        $issues = Issue::whereIn('id', $request->issue_ids)
            ->where('status', 'closed')
            ->get();
        $reopenedCount = 0;

        foreach ($issues as $issue) {
            $this->authorize('update', $issue);
            $issue->reopen();
            $reopenedCount++;
        }

        return response()->json([
            'message' => 'Bulk issues reopened successfully',
            'reopened_count' => $reopenedCount,
            'issues' => IssueResource::collection($issues)
        ]);
    }
}
