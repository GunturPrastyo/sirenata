<?php

namespace Modules\RTK\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RTK\Models\RencanaTenagaKerja;
use Modules\RTK\Traits\AuthorizesRTKByScope;

class RencanaTenagaKerjaPolicy
{
    use HandlesAuthorization, AuthorizesRTKByScope;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function view(User $user, RencanaTenagaKerja $rtk): bool
    {
        return $this->canAccessRTK($user, $rtk);
    }

    public function update(User $user, RencanaTenagaKerja $rtk): bool
    {
        return $this->canAccessRTK($user, $rtk);
    }

    public function delete(User $user, RencanaTenagaKerja $rtk): bool
    {
        return $this->canAccessRTK($user, $rtk);
    }
}
