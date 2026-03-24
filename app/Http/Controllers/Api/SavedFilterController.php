<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SavedFilter\StoreSavedFilterRequest;
use App\Http\Requests\Api\SavedFilter\UpdateSavedFilterRequest;
use App\Http\Resources\SavedFilterResource;
use App\Models\SavedFilter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SavedFilterController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = SavedFilter::query()->with('user');

        // Filter by user
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->orderBy('name');
        $filters = $query->paginate($request->input('per_page', 15));

        return SavedFilterResource::collection($filters);
    }

    public function show(SavedFilter $savedFilter): SavedFilterResource
    {
        $savedFilter->load('user');
        return new SavedFilterResource($savedFilter);
    }

    public function store(StoreSavedFilterRequest $request): SavedFilterResource
    {
        $filter = SavedFilter::create($request->validated());
        $filter->load('user');
        return new SavedFilterResource($filter);
    }

    public function update(UpdateSavedFilterRequest $request, SavedFilter $savedFilter): SavedFilterResource
    {
        $this->authorize('update', $savedFilter);
        $savedFilter->update($request->validated());
        $savedFilter->load('user');
        return new SavedFilterResource($savedFilter);
    }

    public function destroy(SavedFilter $savedFilter): JsonResponse
    {
        $this->authorize('delete', $savedFilter);
        $savedFilter->delete();
        return response()->json(['message' => 'Saved filter deleted successfully']);
    }
}
