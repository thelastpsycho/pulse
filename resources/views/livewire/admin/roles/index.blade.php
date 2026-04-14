<div>
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-accent/20 border border-accent/30 text-accent">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 rounded-lg bg-danger/20 border border-danger/30 text-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <div>
            <h1 class="text-xl font-semibold text-text">Roles</h1>
            <p class="text-sm text-muted">Manage system roles and their permissions</p>
        </div>
        @can('admin.roles.create')
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Role
            </a>
        @endcan
    </div>

    <!-- Search -->
    <div class="card mb-4">
        <div class="p-4">
            <div class="flex flex-col lg:flex-row gap-3">
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search roles by name or description..."
                        class="w-full bg-surface-2 border border-border text-sm text-text placeholder-muted rounded-lg px-3 py-1.5 focus:border-primary focus:ring-primary"
                    />
                </div>

                @if($search)
                    <button wire:click="clearFilters" class="text-primary hover:underline text-sm">Clear search</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="card">
        @if($roles->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-muted tracking-wider cursor-pointer hover:text-text"
                                wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    Role
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getSortIcon('name') }}"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-muted tracking-wider">
                                Description
                            </th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-muted tracking-wider">
                                Permissions
                            </th>
                            <th class="px-4 py-2.5 text-left text-xs font-medium text-muted tracking-wider cursor-pointer hover:text-text"
                                wire:click="sortBy('users_count')">
                                <div class="flex items-center gap-1">
                                    Users
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getSortIcon('users_count') }}"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-4 py-2.5 text-right text-xs font-medium text-muted tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($roles as $role)
                            <tr class="hover:bg-surface-2 transition-smooth">
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-primary/20 flex items-center justify-center text-primary font-semibold">
                                            {{ substr($role->name, 0, 2) }}
                                        </div>
                                        <div class="font-medium text-text">{{ $role->name }}</div>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="text-sm text-muted">{{ $role->description ?? 'No description' }}</span>
                                </td>
                                <td class="px-4 py-2.5">
                                    <div class="flex flex-wrap gap-1">
                                        @if($role->permissions->count() > 0)
                                            @foreach($role->permissions->take(3) as $permission)
                                                <span class="badge badge-muted text-xs">{{ $permission->name }}</span>
                                            @endforeach
                                            @if($role->permissions->count() > 3)
                                                <span class="badge badge-muted text-xs">+{{ $role->permissions->count() - 3 }} more</span>
                                            @endif
                                        @else
                                            <span class="text-sm text-muted">No permissions</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-2.5">
                                    <span class="badge badge-{{ $role->users_count > 0 ? 'info' : 'muted' }} text-xs">
                                        {{ $role->users_count }} user{{ $role->users_count != 1 ? 's' : '' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2.5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('admin.roles.update')
                                            <a href="{{ route('admin.roles.edit', $role) }}"
                                               class="text-primary hover:underline text-sm">Edit</a>
                                        @endcan
                                        @can('admin.roles.delete')
                                            @if($role->users_count === 0)
                                                <button wire:click="deleteRole({{ $role->id }})"
                                                        wire:confirm="Are you sure you want to delete this role?"
                                                        class="text-danger hover:underline text-sm">Delete</button>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-4 py-2.5 border-t border-border">
                {{ $roles->links() }}
            </div>
        @else
            <div class="p-8 text-center">
                <svg class="h-12 w-12 mx-auto text-muted mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-base font-medium text-text mb-2">No roles found</h3>
                <p class="text-sm text-muted mb-4">Get started by creating a new role.</p>
                @can('admin.roles.create')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Add Role</a>
                @endcan
            </div>
        @endif
    </div>
</div>
