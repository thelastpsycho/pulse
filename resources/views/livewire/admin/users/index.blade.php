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
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-text">Users</h1>
            <p class="text-muted">Manage system users and their roles</p>
        </div>
        @can('create', App\Models\User::class)
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </a>
        @endcan
    </div>

    <!-- Search & Filters -->
    <div class="card mb-6">
        <div class="p-4">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search users by name or email..."
                        class="w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg px-4 py-2 focus:border-primary focus:ring-primary"
                    />
                </div>

                <!-- Filters -->
                <div class="flex flex-wrap gap-3">
                    <select wire:model.live="roleFilter" class="bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="statusFilter" class="bg-surface-2 border border-border text-text rounded-lg px-3 py-2 focus:border-primary focus:ring-primary">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    @if($search || $roleFilter || $statusFilter)
                        <button wire:click="clearFilters" class="text-primary hover:underline text-sm">Clear filters</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider cursor-pointer hover:text-text"
                                wire:click="sortBy('name')">
                                <div class="flex items-center gap-1">
                                    User
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getSortIcon('name') }}"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider">
                                Roles
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider cursor-pointer hover:text-text"
                                wire:click="sortBy('email')">
                                <div class="flex items-center gap-1">
                                    Email
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getSortIcon('email') }}"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-muted uppercase tracking-wider cursor-pointer hover:text-text"
                                wire:click="sortBy('is_active')">
                                <div class="flex items-center gap-1">
                                    Status
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $this->getSortIcon('is_active') }}"/>
                                    </svg>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-muted uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($users as $user)
                            <tr class="hover:bg-surface-2 transition-smooth">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center text-primary font-semibold">
                                            {{ $user->name[0] }}
                                        </div>
                                        <div>
                                            <div class="font-medium text-text">{{ $user->name }}</div>
                                            @if($user->id === auth()->id())
                                                <span class="text-xs text-muted">(You)</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-muted">{{ ucfirst($role->name) }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-muted">{{ $user->email }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @can('update', $user)
                                            <!-- Reset Password -->
                                            <button wire:click="openResetPasswordModal({{ $user->id }})"
                                                    class="p-2 text-accent hover:bg-accent/10 rounded-lg transition-smooth"
                                                    title="Reset Password">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                </svg>
                                            </button>
                                            @if($user->id !== auth()->id())
                                                <!-- Toggle Status -->
                                                <button wire:click="toggleUserStatus({{ $user->id }})"
                                                        class="p-2 {{ $user->is_active ? 'text-warning hover:bg-warning/10' : 'text-success hover:bg-success/10' }} rounded-lg transition-smooth"
                                                        title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                    </svg>
                                                </button>
                                            @endif
                                            <!-- Edit -->
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-smooth"
                                               title="Edit">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('delete', $user)
                                            @if($user->id !== auth()->id())
                                                <!-- Delete -->
                                                <button wire:click="deleteUser({{ $user->id }})"
                                                        wire:confirm="Are you sure you want to delete this user?"
                                                        class="p-2 text-danger hover:bg-danger/10 rounded-lg transition-smooth"
                                                        title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
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
            <div class="px-6 py-4 border-t border-border">
                {{ $users->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-muted mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-text mb-2">No users found</h3>
                <p class="text-muted mb-6">Get started by creating a new user.</p>
                @can('create', App\Models\User::class)
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Add User</a>
                @endcan
            </div>
        @endif
    </div>

    <!-- Reset Password Modal -->
    @if($showResetPasswordModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/50" wire:click="closeResetPasswordModal"></div>

            <!-- Modal -->
            <div class="relative bg-surface-1 rounded-lg shadow-xl max-w-md w-full mx-4">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-border">
                    <h3 class="text-lg font-semibold text-text">Reset Password</h3>
                    <button wire:click="closeResetPasswordModal" class="text-muted hover:text-text">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <p class="text-sm text-muted mb-4">
                        Set a new password for <strong>{{ $resettingUser->name ?? 'User' }}</strong> ({{ $resettingUser->email ?? '' }})
                    </p>

                    <form wire:submit="resetPassword">
                        @csrf

                        <!-- New Password -->
                        <div class="mb-4">
                            <x-input-label for="reset_new_password" value="New Password *" />
                            <x-text-input
                                id="reset_new_password"
                                wire:model="new_password"
                                type="password"
                                class="mt-1 block w-full"
                                required
                                autofocus
                                placeholder="Enter new password (min. 8 characters)"
                            />
                            <x-input-error :messages="$errors->get('new_password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <x-input-label for="reset_new_password_confirmation" value="Confirm Password *" />
                            <x-text-input
                                id="reset_new_password_confirmation"
                                wire:model="new_password_confirmation"
                                type="password"
                                class="mt-1 block w-full"
                                required
                                placeholder="Confirm new password"
                            />
                            <x-input-error :messages="$errors->get('new_password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Warning -->
                        <div class="p-3 rounded-lg bg-warning/20 border border-warning/30 mb-4">
                            <p class="text-sm text-warning flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                The user will need to use this new password to log in.
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" wire:click="closeResetPasswordModal" class="btn btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
