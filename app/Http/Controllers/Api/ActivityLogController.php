<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityLogResource;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityLogController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ActivityLog::query()->with(['actor', 'subject']);

        // Filter by action
        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        // Filter by subject type
        if ($request->has('subject_type')) {
            $query->where('subject_type', $request->subject_type);
        }

        // Filter by actor
        if ($request->has('actor_id')) {
            $query->where('actor_user_id', $request->actor_id);
        }

        // Date range filter
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $query->orderBy('created_at', 'desc');
        $logs = $query->paginate($request->input('per_page', 50));

        return ActivityLogResource::collection($logs);
    }

    public function show(ActivityLog $activityLog): ActivityLogResource
    {
        $activityLog->load(['actor', 'subject']);
        return new ActivityLogResource($activityLog);
    }

    public function bySubject(Request $request, string $subjectType, int $subjectId): AnonymousResourceCollection
    {
        $query = ActivityLog::query()
            ->where('subject_type', $subjectType)
            ->where('subject_id', $subjectId)
            ->with(['actor', 'subject']);

        if ($request->has('action')) {
            $query->where('action', $request->action);
        }

        $query->orderBy('created_at', 'desc');
        $logs = $query->paginate($request->input('per_page', 50));

        return ActivityLogResource::collection($logs);
    }
}
