<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\AuditService;

class UserManagement extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $role = '';
    public $search = '';
    public $status = '';
    public $perPage = 10;

    // Modal properties
    public $showDeleteModal = false;
    public $userToDelete = null;
    public $deleteConfirmationText = '';

    protected $queryString = [
        'role' => ['except' => ''],
        'search' => ['except' => ''],
        'status' => ['except' => ''],
    ];

    protected $auditService;

    public function boot(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function mount()
    {
        $this->role = request()->query('role', '');
        $this->search = request()->query('search', '');
        $this->status = request()->query('status', '');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedRole()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['role', 'search', 'status']);
        $this->resetPage();
    }

    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent deactivating the current user
        if (Auth::user()->id === $user->id) {
            session()->flash('error', 'You cannot deactivate your own account');
            return;
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $this->auditService->log(
            'update',
            'User',
            $user->id,
            'User status changed to ' . ($user->is_active ? 'active' : 'inactive'),
            ['is_active' => $user->is_active]
        );

        $status = $user->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "User {$user->name} has been {$status} successfully");
    }

    public function confirmDelete($userId)
    {
        $this->userToDelete = User::findOrFail($userId);
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        if (!$this->userToDelete) {
            return;
        }

        // Check if the confirmation text matches
        if (strtoupper($this->deleteConfirmationText) !== 'DELETE') {
            session()->flash('error', 'Please type DELETE to confirm');
            return;
        }
        
        $user = $this->userToDelete;

        // Prevent deletion of the current user
        if (Auth::user()->id === $user->id) {
            session()->flash('error', 'You cannot delete your own account');
            $this->closeDeleteModal();
            return;
        }

        // Prevent deletion of super admin users by non-super admin users
        if ($user->role === 'super_admin' && Auth::user()->role !== 'super_admin') {
            session()->flash('error', 'You cannot delete super admin accounts');
            $this->closeDeleteModal();
            return;
        }

        // Log the user deletion action before deletion
        $this->auditService->log(
            'delete',
            'User',
            $user->id,
            'User deleted: ' . $user->name,
            $user->toArray()
        );

        $userName = $user->name;
        $user->delete();

        session()->flash('success', "User {$userName} has been deleted successfully");
        $this->closeDeleteModal();
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->userToDelete = null;
        $this->deleteConfirmationText = '';
    }

    public function render()
    {
        $query = User::query();

        // Only fetch admin users (admin and super_admin roles)
        $query->whereIn('role', ['admin', 'super_admin']);

        // Apply filters
        if ($this->role) {
            $query->where('role', $this->role);
        }

        if ($this->status !== '') {
            $query->where('is_active', $this->status === 'active');
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($this->perPage);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
