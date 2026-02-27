<?php

namespace Modules\RTK\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;

class RTKDService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;
    
    /**
     * Build query for active RTK Province per province
     *
     * - Returns only the most recent RTK for each province
     * - Supports search by RTK name or province name
     * - Can be filtered by RTK status
     * - Used by Admin Pusat
     */
    public function queryActiveRTKProvince(
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ): LengthAwarePaginator {

        $provinceQuery = $search
            ? Province::search($search)->orderBy('name')
            : Province::query()->orderBy('name');

        $provinces = $provinceQuery->paginate($limit);
        $provinceCodes = $provinces->getCollection()->pluck('code')->toArray();

        if (empty($provinceCodes)) {
            return $provinces;
        }
        
        $rtks = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::PROVINSI->value)
            ->whereIn('province_code', $provinceCodes)
            ->where('is_active', true)
            // ->where('end_date', '>=', Carbon::now()->year)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('end_date') // safety
            ->get()
            ->unique('province_code')
            ->keyBy('province_code');

        $provinces->getCollection()->transform(function ($province) use ($rtks) {
            $province->latest_rtk = $rtks[$province->code] ?? null;
            return $province;
        });

        return $provinces;
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
        return $this->queryActiveRTKProvince(
            search: $search,
            sortBy: $sortBy,
            limit: $limit,
            status: $status
        )->withQueryString();
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
        int $limit = self::DEFAULT_LIMIT,
        ?string $status = null
    ) {
        // 1️⃣ Regency (SQLite - nusa)
        $regencyQuery = $search
            ? Regency::search($search)->where('province_code', $provinceCode)->orderBy('name', $sortBy)
            : Regency::query()->where('province_code', $provinceCode)->orderBy('name', $sortBy);

        $regencies = $regencyQuery->paginate($limit)->withQueryString();

        $regencyCodes = $regencies->getCollection()->pluck('code')->toArray();

        if (empty($regencyCodes)) {
            return $regencies;
        }

        $rtks = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->whereIn('regency_code', $regencyCodes)
            ->where('is_active', true)
            // ->where('end_date', '>=', Carbon::now()->year)
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('end_date')
            ->get()
            ->unique('regency_code')
            ->keyBy('regency_code');

        // 3️⃣ Attach RTK ke Regency
        $regencies->getCollection()->transform(function ($regency) use ($rtks) {
            $regency->latest_rtk = $rtks[$regency->code] ?? null;
            return $regency;
        });

        return $regencies;
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
            limit: $limit,
            status: $status
        );
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
                // 'status' => RTKStatus::PENDING->value,
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

            $isActive = $data['is_active'] ?? $rtkdKabKota->is_active;
            if ($isActive) {
                RencanaTenagaKerja::where('type', TypeRtk::KAB_KOTA->value)
                    ->where('province_code', $user->scopeArea->province_code)
                    ->where('regency_code', $user->scopeArea->regency_code)
                    ->where('is_active', true)
                    ->where('id', '!=', $rtkdKabKota->id)
                    ->update([
                        // 'status' => RTKStatus::TIDAK_BERLAKU->value,
                        'is_active' => false,
                    ]);
                // $isActive = true;
            } 
            // else {
            //     // Kalau bukan diset berlaku
            //     $isActive = $rtkdKabKota->is_active;
            // }

            $rtkdKabKota->update([
                'province_code' => $user->scopeArea->province_code,
                'regency_code'  => $user->scopeArea->regency_code,
                'name'          => $data['name'],
                'start_date'    => $data['start_date'],
                'end_date'      => $data['end_date'],
                // 'status'        => $status,
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