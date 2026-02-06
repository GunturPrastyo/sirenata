<?php

namespace Modules\RTK\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;

class RTKDService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;
    
    /**
     * Get filtered query builder for RTKD Province
     * Admin Pusat
     * RTKD grouped by province
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
                DB::raw('MAX(start_date) as start_date'),
                DB::raw('MAX(end_date) as end_date'),
                DB::raw('MAX(document_path) as document_path'),
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

    /**
     * Get filtered query builder for RTKD by Province Code
     * Admin Provinsi
     * RTKD by single province
     */
    public function getFilteredQueryBuilderRTKDByProvinceCode(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ): Builder {
        $user = Auth::user();
        return DB::table('rencana_tenaga_kerjas')
            ->where('type', TypeRtk::PROVINSI->value)
            ->where('province_code', $user->scopeArea->province_code)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', $sortBy);
    }

    /**
     * Get paginated filtered RTKD by Province Code
     */
    public function paginateFilteredRTKDByProvinceCode(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByProvinceCode(
            $search,
            $sortBy,
            $status
        )->paginate($limit)->withQueryString();
    }

    /**
     * Get filtered query builder for RTKD by Kab/Kota
     * Admin Province
     * RTKD grouped by kab/kota (per provinsi login)
     */
    public function getFilteredQueryBuilderRTKDByKabKota(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ): Builder {
        $user = Auth::user();

        return DB::table('rencana_tenaga_kerjas')
            ->select(
                'province_code',
                'regency_code',
                DB::raw('MAX(id) as id'),
                DB::raw('MAX(name) as name'),
                DB::raw('MAX(status) as status'),
                DB::raw('MAX(start_date) as start_date'),
                DB::raw('MAX(end_date) as end_date'),
                DB::raw('MAX(document_path) as document_path'),
                DB::raw('MAX(created_at) as created_at')
            )
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $user->scopeArea?->province_code)
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->groupBy('province_code', 'regency_code')
            ->orderByRaw('MAX(created_at) ' . $sortBy);
    }

    /**
     * Get paginated filtered RTKD by Kab/Kota
     */
    public function paginateFilteredRTKDByKabKota(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByKabKota(
            $search,
            $sortBy,
            $status
        )->paginate($limit)->withQueryString();
    }


    /**
     * Delete RTKD
     */
    public function deleteRTKD(RencanaTenagaKerja $rencanaTenagaKerja)
    {
        if ($rencanaTenagaKerja->document_path) {
            Storage::disk('public')->delete($rencanaTenagaKerja->document_path);
        }
        $rencanaTenagaKerja->delete();

        ToastMagic::success("RTKN berhasil dihapus!");
        return $rencanaTenagaKerja;
    }

    /**
     * create RTK Provinsi
     * 
     * @param array $data
     * @return RencanaTenagaKerja
     */
    public function createRTKProvince(array $data): RencanaTenagaKerja
    {
        $user = Auth::user();
        return DB::transaction(function () use ($data, $user) {
            $documentPath = null;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/province',
                    'public'
                );
            }
            $rtkdProvince = RencanaTenagaKerja::create([
                'user_id' => $user->id,
                'province_code' => $user->scopeArea->province_code,
                'regency_code' => null,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => RTKStatus::PENDING->value,
                'type' => TypeRtk::PROVINSI->value,
                'document_path' => $documentPath,
            ]);

            ToastMagic::success("RTKD Provinsi berhasil ditambahkan!");
            return $rtkdProvince;
        });
    }

    /**
     * Update RTK Provinsi
     * 
     * @param RencanaTenagaKerja $rtkdProvince
     * @param array $data
     * @return RencanaTenagaKerja
     */
    public function updateRTKProvince(RencanaTenagaKerja $rtkdProvince, array $data): RencanaTenagaKerja
    {
        $user = Auth::user();
        return DB::transaction(function () use ($rtkdProvince, $data, $user) {
            $documentPath = $rtkdProvince->document_path;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/province',
                    'public'
                );
            }
            $rtkdProvince->update([
                'user_id' => $user->id,
                'province_code' => $user->scopeArea->province_code,
                'regency_code' => null,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'] ?? RTKStatus::PENDING->value,
                'type' => TypeRtk::PROVINSI->value,
                'document_path' => $documentPath,
            ]);

            ToastMagic::success("RTKD Provinsi berhasil diupdate!");
            return $rtkdProvince;
        });
    }
}