<?php

namespace App\Livewire\Dashboard\SuperAdmin;

use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\MasterData\Models\Province;
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
        $this->user = User::find($ids);
        User::whereIn('id', $ids)->delete();

        $this->dispatch('bulk-cleared');
        session()->flash('success', 'Data berhasil dihapus');
    }

    public function render()
    {
        $users = $this->userService->paginateFilteredUsers($this->search, $this->orderBy, $this->limit);
        return view('livewire.dashboard.super-admin.user-management-table', compact('users'));
    }
}
