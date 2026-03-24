<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PermissionController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Permission::query();

        // Filter by category (based on permission name pattern)
        if ($request->has('category')) {
            $query->where('name', 'like', "{$request->category}.%");
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->orderBy('name');
        $permissions = $query->paginate($request->input('per_page', 50));

        return PermissionResource::collection($permissions);
    }

    public function show(Permission $permission): PermissionResource
    {
        $permission->load(['roles', 'users']);
        return new PermissionResource($permission);
    }
}
