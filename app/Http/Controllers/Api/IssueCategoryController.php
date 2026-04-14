<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IssueCategory\StoreIssueCategoryRequest;
use App\Http\Requests\Api\IssueCategory\UpdateIssueCategoryRequest;
use App\Http\Resources\IssueCategoryResource;
use App\Http\Resources\IssueTypeResource;
use App\Models\IssueCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IssueCategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = IssueCategory::query();

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->orderBy('name');
        $categories = $query->paginate($request->input('per_page', 15));

        return IssueCategoryResource::collection($categories);
    }

    public function show(IssueCategory $issueCategory): IssueCategoryResource
    {
        $issueCategory->load('issueTypes');
        return new IssueCategoryResource($issueCategory);
    }

    public function store(StoreIssueCategoryRequest $request): IssueCategoryResource
    {
        $category = IssueCategory::create($request->validated());
        return new IssueCategoryResource($category);
    }

    public function update(UpdateIssueCategoryRequest $request, IssueCategory $issueCategory): IssueCategoryResource
    {
        $issueCategory->update($request->validated());
        return new IssueCategoryResource($issueCategory);
    }

    public function destroy(IssueCategory $issueCategory): JsonResponse
    {
        $issueCategory->delete();
        return response()->json(['message' => 'Issue category deleted successfully']);
    }

    public function types(Request $request, IssueCategory $issueCategory): AnonymousResourceCollection
    {
        $query = $issueCategory->issueTypes();

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $types = $query->paginate($request->input('per_page', 15));

        return IssueTypeResource::collection($types);
    }
}
