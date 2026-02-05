<?php

namespace Modules\RTK\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\TypeRtk;

class RTKDService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;
    
    /**
     * Get filtered query builder for RTKD Province
     */
    public function getFilteredQueryBuilderRTKDProvince(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ): Builder {
        return DB::table('rencana_tenaga_kerjas')
            ->select(
                'province_code',
                DB::raw('MAX(id) as id'),
                DB::raw('MAX(name) as name'),
                DB::raw('MAX(status) as status'),
                DB::raw('MAX(created_at) as created_at')
            )
            ->where('type', TypeRtk::PROVINSI->value)
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($status, fn($query) => $query->where('status', $status))
            ->groupBy('province_code')
            ->orderBy('created_at', $sortBy);
    }

    /**
     * Get paginated filtered RTKD Province data
     */
    public function paginateFilteredRTKDProvince(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDProvince($search, $sortBy, $status)
            ->paginate($limit)
            ->withQueryString();
    }
}