<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    // Password reset modal
    public bool $showResetPasswordModal = false;
    public ?int $resettingUserId = null;
    public ?User $resettingUser = null;
    public string $new_password = '';
    public string $new_password_confirmation = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->roleFilter, function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', $this->roleFilter);
                });
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $roles = Role::orderBy('name')->pluck('name', 'name');

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => $roles,
        ])->layout('layouts.app')->title('Users');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleUserStatus(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->authorize('update', $user);

        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate your own account.');
            return;
        }

        $user->update(['is_active' => !$user->is_active]);

        session()->flash('success', $user->is_active ? 'User activated successfully.' : 'User deactivated successfully.');
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        $this->authorize('delete', $user);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully.');
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'roleFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function openResetPasswordModal(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->authorize('resetPassword', $user);

        $this->resettingUser = $user;
        $this->resettingUserId = $userId;
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->showResetPasswordModal = true;
    }

    public function closeResetPasswordModal(): void
    {
        $this->showResetPasswordModal = false;
        $this->resettingUser = null;
        $this->resettingUserId = null;
        $this->new_password = '';
        $this->new_password_confirmation = '';
    }

    public function resetPassword(): void
    {
        $this->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'new_password.required' => 'The new password field is required.',
            'new_password.min' => 'The password must be at least 8 characters.',
            'new_password.confirmed' => 'The password confirmation does not match.',
        ]);

        if (!$this->resettingUser) {
            return;
        }

        $this->authorize('update', $this->resettingUser);

        $this->resettingUser->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->closeResetPasswordModal();

        session()->flash('success', "Password reset successfully for {$this->resettingUser->name}.");
    }

    public function getSortIcon(string $field): string
    {
        if ($this->sortField !== $field) {
            return 'M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4 4';
        }

        return $this->sortDirection === 'asc'
            ? 'M4.5 15.75l7.5-7.5 7.5 7.5'
            : 'M19.5 8.25l-7.5 7.5-7.5-7.5';
    }
}
