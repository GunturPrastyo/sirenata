<?php

namespace App\Livewire\Dashboard\SuperAdmin;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\User\Services\UserService;


class UserManagementTable extends Component
{
    use WithPagination;

    #[Url]
    public $limit = 10;
    #[Url]
    public $search = '';
    #[Url]
    public $orderBy = 'desc';
    /**
     * @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\User>|null
     */
    public $user;

    protected UserService $userService;
    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function resetFilter()
    {
        $this->reset(['search', 'limit']);
        $this->resetPage();
    }

    public function bulkDelete(array $ids)
    {
        $currentUserId = Auth::id();
        $idsToDelete = collect($ids)->reject(fn ($id) => $id == $currentUserId)->all();
        $selfDeleteAttempt = in_array($currentUserId, $ids);

        if (!empty($idsToDelete)) {
            User::whereIn('id', $idsToDelete)->delete();
            $message = count($idsToDelete) . ' user berhasil dihapus.';
            if ($selfDeleteAttempt) {
                $message .= ' Anda tidak dapat menghapus akun Anda sendiri.';
            }
            session()->flash('success', $message);
        } elseif ($selfDeleteAttempt) {
            session()->flash('warning', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $this->dispatch('bulk-cleared');
        
    }

    public function bulkActivate(array $ids)
    {
        User::whereIn('id', $ids)->update(['is_active' => true]);

        $this->dispatch('bulk-cleared');
        session()->flash('success', count($ids) . ' user berhasil diaktifkan.');
    }

    public function bulkDeactivate(array $ids)
    {
        $currentUserId = Auth::id();
        $idsToDeactivate = collect($ids)->reject(fn ($id) => $id == $currentUserId)->all();
        $selfDeactivationAttempt = in_array($currentUserId, $ids);

        if (!empty($idsToDeactivate)) {
            User::whereIn('id', $idsToDeactivate)->update(['is_active' => false]);
            $message = count($idsToDeactivate) . ' user berhasil dinonaktifkan.';
            if ($selfDeactivationAttempt) {
                $message .= ' Anda tidak dapat menonaktifkan akun Anda sendiri.';
            }
            session()->flash('success', $message);
        } elseif ($selfDeactivationAttempt) {
            session()->flash('warning', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $this->dispatch('bulk-cleared');
    }

    public function render()
    {
        $users = $this->userService->paginateFilteredUsers($this->search, $this->orderBy, $this->limit);
        return view('livewire.dashboard.super-admin.user-management-table', compact('users'));
    }
}
