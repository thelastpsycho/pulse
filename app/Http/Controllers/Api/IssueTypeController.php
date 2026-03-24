<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IssueType\StoreIssueTypeRequest;
use App\Http\Requests\Api\IssueType\UpdateIssueTypeRequest;
use App\Http\Resources\IssueTypeResource;
use App\Models\IssueType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IssueTypeController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = IssueType::query()->with('issueCategory');

        // Filter by category
        if ($request->has('issue_category_id')) {
            $query->where('issue_category_id', $request->issue_category_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->orderBy('name');
        $issueTypes = $query->paginate($request->input('per_page', 15));

        return IssueTypeResource::collection($issueTypes);
    }

    public function show(IssueType $issueType): IssueTypeResource
    {
        $issueType->load(['issueCategory', 'issues']);
        return new IssueTypeResource($issueType);
    }

    public function store(StoreIssueTypeRequest $request): IssueTypeResource
    {
        $issueType = IssueType::create($request->validated());
        $issueType->load('issueCategory');
        return new IssueTypeResource($issueType);
    }

    public function update(UpdateIssueTypeRequest $request, IssueType $issueType): IssueTypeResource
    {
        $issueType->update($request->validated());
        $issueType->load('issueCategory');
        return new IssueTypeResource($issueType);
    }

    public function destroy(IssueType $issueType): JsonResponse
    {
        $issueType->delete();
        return response()->json(['message' => 'Issue type deleted successfully']);
    }
}
