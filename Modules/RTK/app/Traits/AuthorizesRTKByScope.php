<?php

namespace Modules\RTK\Traits;

use App\Models\User;
use Modules\RTK\Models\RencanaTenagaKerja;

trait AuthorizesRTKByScope {
    protected function canAccessRTK(User $user, RencanaTenagaKerja $rtk): bool
    {
        // Full akses
        if ($user->hasAnyRole(['super-admin', 'admin-pusat'])) {
            return true;
        }

        if (!$user->scopeArea) {
            return false;
        }

        if ($user->hasRole('admin-province')) {
            return $rtk->province_code === $user->scopeArea->province_code;
        }

        if ($user->hasRole('admin-kab-kota')) {
            return
                $rtk->province_code === $user->scopeArea->province_code &&
                $rtk->regency_code === $user->scopeArea->regency_code;
        }

        return false;
    }
}
