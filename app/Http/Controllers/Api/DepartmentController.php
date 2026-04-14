<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Department\StoreDepartmentRequest;
use App\Http\Requests\Api\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\IssueResource;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DepartmentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Department::query();

        // Filter by active status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $query->orderBy('name');
        $departments = $query->paginate($request->input('per_page', 15));

        return DepartmentResource::collection($departments);
    }

    public function show(Department $department): DepartmentResource
    {
        $department->load('issues');
        return new DepartmentResource($department);
    }

    public function store(StoreDepartmentRequest $request): DepartmentResource
    {
        $department = Department::create($request->validated());
        return new DepartmentResource($department);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): DepartmentResource
    {
        $department->update($request->validated());
        return new DepartmentResource($department);
    }

    public function destroy(Department $department): JsonResponse
    {
        $department->delete();
        return response()->json(['message' => 'Department deleted successfully']);
    }

    public function issues(Request $request, Department $department): AnonymousResourceCollection
    {
        $query = $department->issues()
            ->with(['departments', 'issueTypes', 'createdBy', 'assignedTo']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        $issues = $query->paginate($request->input('per_page', 15));

        return IssueResource::collection($issues);
    }
}
