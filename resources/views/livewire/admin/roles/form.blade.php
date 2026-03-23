<div>
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg bg-accent/20 border border-accent/30 text-accent">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.roles.index') }}" class="text-muted hover:text-text transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-text">{{ $isEditing ? 'Edit Role' : 'Add Role' }}</h1>
        </div>
        <p class="text-muted mt-1">{{ $isEditing ? 'Update role details and permissions' : 'Create a new role and assign permissions' }}</p>
    </div>

    <form wire:submit="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Role Details -->
            <div class="lg:col-span-1">
                <div class="card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold text-text mb-4">Role Details</h2>

                        <div class="space-y-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium text-text mb-1">
                                    Name <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    wire:model="name"
                                    placeholder="e.g., Manager, Supervisor"
                                    class="w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg px-4 py-2 focus:border-primary focus:ring-primary @error('name') border-danger @enderror"
                                />
                                @error('name')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="block text-sm font-medium text-text mb-1">
                                    Description
                                </label>
                                <textarea
                                    wire:model="description"
                                    rows="3"
                                    placeholder="Brief description of this role's purpose..."
                                    class="w-full bg-surface-2 border border-border text-text placeholder-muted rounded-lg px-4 py-2 focus:border-primary focus:ring-primary resize-none"
                                ></textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 border-t border-border flex items-center justify-between">
                        <button type="button" wire:click="cancel" class="text-muted hover:text-text transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ $isEditing ? 'Update Role' : 'Create Role' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Permissions -->
            <div class="lg:col-span-2">
                <div class="card">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-text">Permissions</h2>
                            <button type="button" wire:click="toggleSelectAll" class="text-sm text-primary hover:underline">
                                {{ $selectAll ? 'Deselect All' : 'Select All' }}
                            </button>
                        </div>

                        <div class="space-y-6">
                            @foreach($groupedPermissions as $category => $categoryPermissions)
                                <div>
                                    <h3 class="text-sm font-semibold text-text uppercase tracking-wider mb-3">{{ $category }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        @foreach($categoryPermissions as $permission)
                                            <label class="flex gap-3 p-3 rounded-lg border border-border hover:bg-surface-2 cursor-pointer transition-colors">
                                                <input type="checkbox"
                                                       wire:model="permissions"
                                                       value="{{ $permission->id }}"
                                                       class="mt-1 w-4 h-4 text-primary rounded border-border focus:ring-primary cursor-pointer flex-shrink-0">
                                                <div class="flex-1 min-w-0">
                                                    <div class="text-sm font-medium text-text leading-tight">{{ $permission->name }}</div>
                                                    <div class="text-xs text-muted mt-0.5 leading-snug">{{ $permission->description }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
