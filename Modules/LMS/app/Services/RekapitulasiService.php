<?php

namespace Modules\LMS\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\MasterData\Models\Province;
use Modules\User\Models\UserScope;

class RekapitulasiService
{
    private const DEFAULT_LIMIT = 10;

    /**
     *  Query Rekapitulasi Province
     */
    public function queryRekapitulasiProvince(?string $search = null)
    {
        $provinceCodes = null;

        if ($search) {
            $provinceCodes = Province::where('name', 'like', "%{$search}%")
                ->pluck('code')
                ->toArray();
        }

        return UserScope::query()
            ->select('province_code')
            ->selectRaw('COUNT(DISTINCT user_id) as total_users')
            ->whereNotNull('province_code')
            ->when($provinceCodes !== null, fn($q) =>
                $q->whereIn('province_code', $provinceCodes)
            )
            ->groupBy('province_code')
            ->with('province');
    }
    /**
     *  Paginate Rekapitulasi Province
     */
    public function paginateFilteredRekapitulasiProvince(
        ?string $search = null,
        int $limit = self::DEFAULT_LIMIT
    ): LengthAwarePaginator {
        return $this->queryRekapitulasiProvince($search)
            ->paginate($limit)
            ->withQueryString();
    }

    /**
     *  Query Rekapitulasi Regency Kab/Kota
     */
    public function queryRekapitulasiRegency(string $provinceCode, ?string $search = null)
    {
        return UserScope::query()
            ->select('regency_code')
            ->selectRaw('COUNT(DISTINCT user_id) as total_users')
            ->where('province_code', $provinceCode)
            ->whereNotNull('regency_code')
            ->when($search !== null, function ($query) use ($search) {
                $query->whereHas('regency', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->groupBy('regency_code')
            ->with('regency');
    }

    /**
     *  Paginate Rekapitulasi Regency Kab/Kota
     */
    public function paginateFilteredRekapitulasiRegency(
        string $provinceCode,
        ?string $search = null,
        int $limit = self::DEFAULT_LIMIT
    ): LengthAwarePaginator {
        return $this->queryRekapitulasiRegency($provinceCode, $search)
            ->paginate($limit)
            ->withQueryString();
    }

    /**
     *  Query Rekapitulasi User
     */
    public function queryUsersByRegion(string $provinceCode, ?string $regencyCode = null, ?string $search = null)
    {
        return User::query()
            ->whereHas('scopeArea', function ($q) use ($provinceCode, $regencyCode) {
                $q->where('province_code', $provinceCode);

                if ($regencyCode) {
                    $q->where('regency_code', $regencyCode);
                }
            })
            ->when($search !== null, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->with('scopeArea.province', 'scopeArea.regency');
    }

    /**
     *  Paginate Rekapitulasi User
     */
    public function paginateFilteredRekapitulasiUser(
        string $provinceCode,
        ?string $regencyCode = null,
        ?string $search = null,
        int $limit = self::DEFAULT_LIMIT
    ): LengthAwarePaginator {
        return $this->queryUsersByRegion($provinceCode, $regencyCode, $search)
            ->paginate($limit)
            ->withQueryString();
    }
}