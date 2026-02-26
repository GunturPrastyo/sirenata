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
     * Build query for latest RTK Province per province
     *
     * - Returns only the most recent RTK for each province
     * - Supports search by RTK name or province name
     * - Can be filtered by RTK status
     * - Used by Admin Pusat
     */
    public function queryLatestRTKProvince(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ) {
        return RencanaTenagaKerja::query()
            ->with(['user', 'province'])
            ->where('type', TypeRtk::PROVINSI->value)
            ->where('is_active', true)
            ->whereIn('id', function ($sub) {
                $sub->selectRaw('MAX(id)')
                    ->from('rencana_tenaga_kerjas')
                    ->where('type', TypeRtk::PROVINSI->value)
                    ->where('is_active', true)
                    ->groupBy('province_code');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('province', function ($provinceQuery) use ($search) {
                            $provinceQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
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
        return $this->queryLatestRTKProvince($search, $sortBy, $status)
            ->paginate($limit)
            ->withQueryString();
    }

    /**
     * Get filtered query builder for RTKD by Province Code
     * Admin Provinsi
     * RTKD by single province
     */
    public function getFilteredQueryBuilderRTKDByProvinceCode(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null,
        ?string $year = null
    ): Builder {
        return DB::table('rencana_tenaga_kerjas')
            ->where('type', TypeRtk::PROVINSI->value)
            ->where('province_code', $provinceCode)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($year, fn($q) => $q->whereYear('start_date', $year))
            ->orderBy('created_at', $sortBy);
    }

    public function paginateFilteredRTKDByProvinceCode(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null,
        ?string $year = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByProvinceCode(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $sortBy,
            status: $status,
            year: $year
        )->paginate($limit)->withQueryString();
    }

    /**
     * Build query for latest RTK Kab/Kota per regency in a province
     *
     * - Returns only latest RTK per kab/kota
     * - Province is explicitly provided (Admin Pusat / Provinsi)
     * - Supports search by RTK name or regency name
     * - Can be filtered by status
     */
    public function queryLatestRTKKabKotaByProvince(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $status = null
    ) {
        return RencanaTenagaKerja::query()
            ->with(['user', 'regency'])
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->where('is_active', true)
            ->whereIn('id', function ($sub) use ($provinceCode) {
                $sub->selectRaw('MAX(id)')
                    ->from('rencana_tenaga_kerjas')
                    ->where('type', TypeRtk::KAB_KOTA->value)
                    ->where('province_code', $provinceCode)
                    ->where('is_active', true)
                    ->groupBy('province_code', 'regency_code');
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('regency', function ($regencyQuery) use ($search) {
                            $regencyQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->orderBy('created_at', $sortBy);
    }


    /**
     * Paginate latest RTK Kab/Kota by Province
     */
    public function paginateFilteredRTKKabKotaByProvince(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->queryLatestRTKKabKotaByProvince(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $sortBy,
            status: $status
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
                // 'status' => RTKStatus::PENDING->value,
                'type' => TypeRtk::PROVINSI->value,
                'document_path' => $documentPath,
            ]);

            ToastMagic::success("RTKD Provinsi berhasil ditambahkan!");
            return $rtkdProvince;
        });
    }

    public function updateRTKProvince(RencanaTenagaKerja $rtkdProvince, array $data): RencanaTenagaKerja {

        $user = Auth::user();
        return DB::transaction(function () use ($rtkdProvince, $data, $user) {
            $documentPath = $rtkdProvince->document_path;

            if (!empty($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/province',
                    'public'
                );
            }
            // $status = $data['status'] ?? $rtkdProvince->status;
            $isActive = $data['is_active'] ?? $rtkdProvince->is_active;

            // Jika RTK ini diset menjadi BERLAKU
            if ($isActive) {
                RencanaTenagaKerja::where('type', TypeRtk::PROVINSI->value)
                    ->where('province_code', $user->scopeArea->province_code)
                    ->where('is_active', true)
                    ->where('id', '!=', $rtkdProvince->id)
                    ->update([
                        'is_active' => false,
                    ]);

                // $isActive = true;
            } 
            // else {
            //     // Jangan ubah is_active kalau bukan diset berlaku
            //     $isActive = $rtkdProvince->is_active;
            // }

            $rtkdProvince->update([
                'province_code' => $user->scopeArea->province_code,
                'regency_code'  => null,
                'name'          => $data['name'],
                'start_date'    => $data['start_date'],
                'end_date'      => $data['end_date'],
                // 'status'        => $status,
                'type'          => TypeRtk::PROVINSI->value,
                'document_path' => $documentPath,
                'is_active'     => $isActive,
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
        string $provinceCode,
        string $regencyCode,
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
                ['rtk.province_code', '=', $provinceCode],
                ['rtk.regency_code', '=', $regencyCode],
            ])
            ->when($search, fn($q) => $q->where('rtk.name', 'like', "%{$search}%"))
            ->when($status, fn($q) => $q->where('rtk.status', $status))
            ->orderBy('rtk.created_at', $sortBy);
    }

    /**
     * Get paginated filtered RTKD by Kab/Kota Code
     */
    public function paginateFilteredRTKDByKabKotaCode(
        string $provinceCode,
        string $regencyCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByKabKotaCode(
            provinceCode: $provinceCode,
            regencyCode: $regencyCode,
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

            $documentPath = $rtkdKabKota->document_path;

            if (!empty($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/kab-kota',
                    'public'
                );
            }

            $status = $data['status'] ?? $rtkdKabKota->status;
            // Jika diset menjadi BERLAKU
            if ($status === RTKStatus::BERLAKU->value) {

                // Nonaktifkan versi aktif sebelumnya
                RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
                    ->where('province_code', $user->scopeArea->province_code)
                    ->where('regency_code', $user->scopeArea->regency_code)
                    ->where('is_active', true)
                    ->where('id', '!=', $rtkdKabKota->id)
                    ->update([
                        'status' => RTKStatus::TIDAK_BERLAKU->value,
                        'is_active' => false,
                    ]);

                $isActive = true;

            } else {
                // Kalau bukan diset berlaku
                $isActive = $rtkdKabKota->is_active;
            }

            $rtkdKabKota->update([
                'province_code' => $user->scopeArea->province_code,
                'regency_code'  => $user->scopeArea->regency_code,
                'name'          => $data['name'],
                'start_date'    => $data['start_date'],
                'end_date'      => $data['end_date'],
                'status'        => $status,
                'type'          => TypeRtk::KAB_KOTA->value,
                'document_path' => $documentPath,
                'is_active'     => $isActive,
            ]);

            return $rtkdKabKota;
        });
    }

    /**
     * Get active RTK Kab/Kota for current user
     */
    public function rtkKabKotaActive(): ?RencanaTenagaKerja
    {
        $user = Auth::user();
        $rtkAktif = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $user->scopeArea?->province_code)
            ->where('regency_code', $user->scopeArea?->regency_code)
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        return $rtkAktif;
    }
}