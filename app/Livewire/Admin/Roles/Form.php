<?php

namespace App\Livewire\Admin\Roles;

use App\Models\Role;
use App\Models\Permission;
use Livewire\Component;

class Form extends Component
{
    public ?Role $role = null;

    public string $name = '';
    public ?string $description = null;
    public array $permissions = [];

    public bool $isEditing = false;
    public bool $selectAll = false;

    public function mount(?Role $role = null): void
    {
        if ($role) {
            $this->role = $role;
            $this->isEditing = true;
            $this->name = $role->name;
            $this->description = $role->description;
            $this->permissions = $role->permissions->pluck('id')->toArray();
            $this->selectAll = count($this->permissions) === Permission::count();

            $this->authorize('admin.roles.update');
        } else {
            $this->authorize('admin.roles.create');
        }
    }

    public function render()
    {
        // Group permissions by category
        $groupedPermissions = Permission::all()->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            return match($parts[0] ?? 'other') {
                'issues' => 'Issues',
                'admin' => match($parts[1] ?? 'other') {
                    'users' => 'User Management',
                    'roles' => 'Role Management',
                    'permissions' => 'Permission Management',
                    'departments' => 'Department Management',
                    'issue-types' => 'Issue Type Management',
                    default => 'Admin',
                },
                'reports' => 'Reports',
                'graphs' => 'Graphs',
                'statistics' => 'Statistics',
                default => 'Other',
            };
        });

        return view('livewire.admin.roles.form', [
            'groupedPermissions' => $groupedPermissions,
        ])->layout('layouts.app')->title($this->isEditing ? 'Edit Role' : 'Add Role');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->role->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
            $this->role->permissions()->sync($this->permissions);

            session()->flash('success', 'Role updated successfully.');
        } else {
            $role = Role::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            $role->permissions()->attach($this->permissions);

            session()->flash('success', 'Role created successfully.');
        }

        return $this->redirectRoute('admin.roles.index');
    }

    public function cancel()
    {
        return $this->redirectRoute('admin.roles.index');
    }

    public function toggleSelectAll(): void
    {
        $this->selectAll = !$this->selectAll;
        if ($this->selectAll) {
            $this->permissions = Permission::pluck('id')->toArray();
        } else {
            $this->permissions = [];
        }
    }

    public function updatedPermissions(): void
    {
        $this->selectAll = count($this->permissions) === Permission::count();
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', $this->isEditing ? 'unique:roles,name,' . $this->role->id : 'unique:roles,name'],
            'description' => ['nullable', 'string', 'max:500'],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }
}
