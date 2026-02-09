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


    // Admin Kab/kota
    /**
     * Get filtered query builder for RTKD by Kab/Kota Code
     * Admin Kab/Kota
     * RTKD by single kab/kota
     */
    public function getFilteredQueryBuilderRTKDByKabKotaCode(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ): Builder {
        $scopeArea = Auth::user()->scopeArea;
        
        return DB::table('rencana_tenaga_kerjas as rtk')
            ->join('users as u', 'rtk.user_id', '=', 'u.id')
            ->select('rtk.*')
            ->where([
                ['rtk.type', '=', TypeRtk::KAB_KOTA->value],
                ['rtk.province_code', '=', $scopeArea->province_code],
                ['rtk.regency_code', '=', $scopeArea->regency_code],
            ])
            ->when($search, fn($q) => $q->where('rtk.name', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('rtk.status', $status))
            ->orderBy('rtk.created_at', $sortBy);
    }

    /**
     * Get paginated filtered RTKD by Kab/Kota Code
     */
    public function paginateFilteredRTKDByKabKotaCode(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByKabKotaCode(
            search: $search,
            sortBy: $sortBy,
            status: $status
        )->paginate($limit)->withQueryString();
    }

    /**
     * Create RTK Kab/Kota
     * 
     * @param array $data
     * @return RencanaTenagaKerja
     */
    public function createRTKKabKota(array $data): RencanaTenagaKerja
    {
        $user = Auth::user();
        if (!$user->scopeArea) {
            throw new \LogicException('Wilayah kerja akun belum terdaftar di sistem');
        }

        return DB::transaction(function () use ($data, $user) {
            $documentPath = null;
            if (!empty($data['document_path']) 
                && $data['document_path'] instanceof \Illuminate\Http\UploadedFile) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/kab-kota',
                    'public'
                );
            }

            $rtkkabkota = RencanaTenagaKerja::create([
                'user_id' => $user->id,
                'province_code' => $user->scopeArea->province_code,
                'regency_code' => $user->scopeArea->regency_code,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => RTKStatus::PENDING->value,
                'type' => TypeRtk::KAB_KOTA->value,
                'document_path' => $documentPath,
            ]);

            return $rtkkabkota;
        });
    }

    /**
     * Update RTK Kab/Kota
     * 
     * @param RencanaTenagaKerja $rtkdKabKota
     * @param array $data
     * @return RencanaTenagaKerja
     */
    public function updateRTKKabKota(RencanaTenagaKerja $rtkdKabKota, array $data): RencanaTenagaKerja {
        $user = Auth::user();
        if (!$user->scopeArea) {
            throw new \LogicException('Wilayah kerja akun belum terdaftar di sistem');
        }
        return DB::transaction(function () use ($rtkdKabKota, $data, $user) {
            if (
                isset($data['status']) &&
                $data['status'] === RTKStatus::BERLAKU->value
            ) {
                RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
                    ->where('province_code', $user->scopeArea->province_code)
                    ->where('regency_code', $user->scopeArea->regency_code)
                    ->where('status', RTKStatus::BERLAKU->value)
                    ->where('id', '!=', $rtkdKabKota->id)
                    ->update([
                        'status' => RTKStatus::TIDAK_BERLAKU->value,
                    ]);
            }

            $documentPath = $rtkdKabKota->document_path;

            if (!empty($data['document_path'])) {
                if ($documentPath && Storage::disk('public')->exists($documentPath)) {
                    Storage::disk('public')->delete($documentPath);
                }

                $documentPath = $data['document_path']->store(
                    'rtkd/documents/kab-kota',
                    'public'
                );
            }

            $rtkdKabKota->update([
                'province_code' => $user->scopeArea->province_code,
                'regency_code' => $user->scopeArea->regency_code,
                'name' => $data['name'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'] ?? RTKStatus::PENDING->value,
                'type' => TypeRtk::KAB_KOTA->value,
                'document_path' => $documentPath,
            ]);

            return $rtkdKabKota;
        });
    }

    public function rtkKabKotaActive(): ?RencanaTenagaKerja
    {
        $user = Auth::user();
        $rtkAktif = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $user->scopeArea?->province_code)
            ->where('regency_code', $user->scopeArea?->regency_code)
            ->where('status', RTKStatus::BERLAKU->value)
            ->orderByDesc('start_date')
            ->first();

        return $rtkAktif;
    }
}