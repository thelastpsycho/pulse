<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\DmLogBook\StoreDmLogBookRequest;
use App\Http\Requests\Api\DmLogBook\UpdateDmLogBookRequest;
use App\Http\Resources\DmLogBookResource;
use App\Models\DmLogBook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DmLogBookController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = DmLogBook::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Filter by room number
        if ($request->has('room_number')) {
            $query->where('room_number', 'like', "%{$request->room_number}%");
        }

        // Filter by assigned user
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('guest_name', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%")
                    ->orWhere('room_number', 'like', "%{$request->search}%");
            });
        }

        $query->orderBy('created_at', 'desc');
        $entries = $query->paginate($request->input('per_page', 15));

        return DmLogBookResource::collection($entries);
    }

    public function show(DmLogBook $dmLogBook): DmLogBookResource
    {
        return new DmLogBookResource($dmLogBook);
    }

    public function store(StoreDmLogBookRequest $request): DmLogBookResource
    {
        $entry = DmLogBook::create($request->validated());
        return new DmLogBookResource($entry);
    }

    public function update(UpdateDmLogBookRequest $request, DmLogBook $dmLogBook): DmLogBookResource
    {
        $dmLogBook->update($request->validated());
        return new DmLogBookResource($dmLogBook);
    }

    public function destroy(DmLogBook $dmLogBook): JsonResponse
    {
        $dmLogBook->delete();
        return response()->json(['message' => 'Log book entry deleted successfully']);
    }
}
