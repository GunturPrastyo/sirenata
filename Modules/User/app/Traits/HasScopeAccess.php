<?php

namespace Modules\User\Traits;

trait HasScopeAccess
{
    public function hasCompleteScope(): bool
    {
        if (!$this->scopeArea) {
            return false;
        }

        if ($this->hasRole('super-admin')) {
            return true;
        }

        if ($this->hasRole('admin-province')) {
            return !empty($this->scopeArea->province_code);
        }

        if ($this->hasRole('admin-kab-kota')) {
            return !empty($this->scopeArea->province_code)
                && !empty($this->scopeArea->regency_code);
        }

        return false;
    }
}
