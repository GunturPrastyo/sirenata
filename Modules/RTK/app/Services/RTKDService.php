<?php

namespace Modules\RTK\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;
use phpDocumentor\Reflection\Types\Boolean;

class RTKDService
{
    private const DEFAULT_SORT = 'desc';
    private const DEFAULT_LIMIT = 10;

    /**
     * Build query for active RTK Province per province
     *
     * - Prioritaskan RTK berlaku (APPROVED + VALID + is_active)
     * - Kalau tidak ada berlaku, tampilkan is_active = true lainnya
     * - Tambahkan count RTK yang ingin diubah (pending_count)
     * - Used by Admin Pusat halaman 1
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

        $provinces     = $provinceQuery->paginate($limit);
        $provinceCodes = $provinces->getCollection()->pluck('code')->toArray();

        if (empty($provinceCodes)) {
            return $provinces;
        }

        // Ambil semua RTK is_active = true per provinsi
        $allActiveRtks = RencanaTenagaKerja::query()
            ->with(['approver.profile', 'province', 'regency'])
            ->where('type', TypeRtk::PROVINSI->value)
            ->whereIn('province_code', $provinceCodes)
            ->where('is_active', true)
            ->when($status, fn($q) => $q->where('status_verification', $status))
            ->orderByDesc('end_date')
            ->get()
            ->groupBy('province_code');

        // Count RTK yang is_active = true dan bukan berlaku penuh (untuk tooltip)
        // Kondisi: pending + is_active ATAU approved + NA + is_active
        $pendingCounts = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::PROVINSI->value)
            ->whereIn('province_code', $provinceCodes)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('status_verification', RTKStatusVerification::PENDING->value)
                    ->orWhere(function ($q2) {
                        $q2->where('status_verification', RTKStatusVerification::APPROVED->value)
                            ->where('status_document', StatusDocument::NA->value);
                    });
            })
            ->selectRaw('province_code, COUNT(*) as count')
            ->groupBy('province_code')
            ->pluck('count', 'province_code');

        $provinces->getCollection()->transform(function ($province) use ($allActiveRtks, $pendingCounts) {
            $rtksByProvince = $allActiveRtks[$province->code] ?? collect();

            // Prioritaskan RTK berlaku penuh (APPROVED + VALID + is_active)
            $rtkBerlaku = $rtksByProvince->first(function ($rtk) {
                return $rtk->status_verification->value === RTKStatusVerification::APPROVED->value
                    && $rtk->status_document->value === StatusDocument::VALID->value;
            });

            // Kalau tidak ada berlaku, ambil yang is_active = true lainnya
            $province->latest_rtk = $rtkBerlaku ?? $rtksByProvince->first();

            // Count RTK yang ingin diubah (untuk tooltip)
            $province->pending_rtk_count = $pendingCounts[$province->code] ?? 0;

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
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ) {
        return RencanaTenagaKerja::query()
            ->where('type', TypeRtk::PROVINSI->value)
            ->where('province_code', $provinceCode)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($statusVerification, fn($q) => $q->where('status_verification', $statusVerification))
            ->when($statusDocument, fn($q) => $q->where('status_document', $statusDocument))
            ->when($isActive !== null && $isActive !== '', function ($q) use ($isActive) {
                $q->where('is_active', (bool) $isActive);
            })
            ->orderBy('created_at', $sortBy);
    }

    public function paginateFilteredRTKDByProvinceCode(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        int $limit = self::DEFAULT_LIMIT,
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByProvinceCode(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        )->paginate($limit)->withQueryString();
    }

    public function exportRtkProvince(
        string $provinceCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ) {
        return $this->getFilteredQueryBuilderRTKDByProvinceCode(
            provinceCode: $provinceCode,
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
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

        // 2️⃣ Ambil semua RTK is_active = true per kabupaten/kota
        $allActiveRtks = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->whereIn('regency_code', $regencyCodes)
            ->where('is_active', true)
            ->when($status, fn($q) => $q->where('status_verification', $status))
            ->orderByDesc('end_date')
            ->get()
            ->groupBy('regency_code');

        // 3️⃣ Count RTK yang is_active = true dan bukan berlaku penuh (untuk tooltip)
        // Kondisi: pending + is_active ATAU approved + NA + is_active
        $pendingCounts = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->whereIn('regency_code', $regencyCodes)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('status_verification', RTKStatusVerification::PENDING->value)
                    ->orWhere(function ($q2) {
                        $q2->where('status_verification', RTKStatusVerification::APPROVED->value)
                            ->where('status_document', StatusDocument::NA->value);
                    });
            })
            ->selectRaw('regency_code, COUNT(*) as count')
            ->groupBy('regency_code')
            ->pluck('count', 'regency_code');

        // 4️⃣ Transformasi data collection
        $regencies->getCollection()->transform(function ($regency) use ($allActiveRtks, $pendingCounts) {
            $rtksByRegency = $allActiveRtks[$regency->code] ?? collect();

            // Prioritaskan RTK berlaku penuh (APPROVED + VALID + is_active)
            $rtkBerlaku = $rtksByRegency->first(function ($rtk) {
                return $rtk->status_verification->value === RTKStatusVerification::APPROVED->value
                    && $rtk->status_document->value === StatusDocument::VALID->value;
            });

            // Kalau tidak ada berlaku, ambil yang is_active = true lainnya
            $regency->latest_rtk = $rtkBerlaku ?? $rtksByRegency->first();

            // Count RTK yang ingin diubah (untuk tooltip)
            $regency->pending_rtk_count = $pendingCounts[$regency->code] ?? 0;

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

    public function createRTKProvince(array $data): RencanaTenagaKerja
    {
        $user = Auth::user();

        return DB::transaction(function () use ($data, $user) {
            $provinceCode = $user->scopeArea->province_code;
            $isActive     = (bool) ($data['is_active'] ?? false);

            // Kalau user set is_active = true
            if ($isActive) {
                // Cek apakah ada RTK yang berlaku penuh
                $rtkBerlaku = RencanaTenagaKerja::where('province_code', $provinceCode)
                    ->where('type', TypeRtk::PROVINSI->value)
                    ->berlaku() // APPROVED + VALID + is_active
                    ->exists();

                if (! $rtkBerlaku) {
                    // Tidak ada yang berlaku penuh — non-aktifkan yang lama
                    RencanaTenagaKerja::where('province_code', $provinceCode)
                        ->where('type', TypeRtk::PROVINSI->value)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
                // Kalau ada yang berlaku penuh — biarkan, is_active tetap true untuk RTK baru
                // Penggantian RTK acuan dihandle admin pusat
            }

            $documentPath = null;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtk/documents/province',
                    'public'
                );
            }

            $rtk = RencanaTenagaKerja::create([
                'user_id'             => $user->id,
                'province_code'       => $provinceCode,
                'regency_code'        => null,
                'name'                => $data['name'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
                'status_verification' => RTKStatusVerification::PENDING->value,
                'status_document'     => StatusDocument::NA->value,
                'type'                => TypeRtk::PROVINSI->value,
                'is_active'           => $isActive,
                'document_path'       => $documentPath,
            ]);

            ToastMagic::success('RTK Provinsi berhasil diajukan.');

            return $rtk;
        });
    }

    public function updateRTKProvince(RencanaTenagaKerja $rtk, array $data): RencanaTenagaKerja
    {
        return DB::transaction(function () use ($data, $rtk) {

            // Tidak boleh edit kalau RTK ini sendiri sudah berlaku penuh
            if ($rtk->is_berlaku) {
                throw new \Exception('RTK yang sedang berlaku tidak bisa diubah.');
            }

            // Boleh edit kalau:
            // 1. status_verification = PENDING (semua kondisi)
            // 2. status_verification = APPROVED + status_document = NA (belum di-approve dokumen)
            // 3. status_verification = REJECTED
            $bolehEdit =
                $rtk->status_verification === RTKStatusVerification::PENDING ||
                $rtk->status_verification === RTKStatusVerification::REJECTED ||
                (
                    $rtk->status_verification === RTKStatusVerification::APPROVED &&
                    $rtk->status_document === StatusDocument::NA
                );

            if (! $bolehEdit) {
                throw new \Exception('RTK ini tidak bisa diubah.');
            }

            $isActive = (bool) ($data['is_active'] ?? false);

            if ($isActive) {
                // Cek apakah ada RTK berlaku penuh di provinsi yang sama
                $adaRtkBerlaku = RencanaTenagaKerja::where('province_code', $rtk->province_code)
                    ->where('type', TypeRtk::PROVINSI->value)
                    ->where('id', '!=', $rtk->id)
                    ->berlaku()
                    ->exists();

                if ($adaRtkBerlaku) {
                    // Ada RTK berlaku penuh (data 1) — tetap biarkan is_active-nya
                    // Tapi non-aktifkan RTK lain yang is_active = true tapi BUKAN berlaku penuh
                    // Contoh: data 2 (pending, is_active=true) harus jadi false
                    RencanaTenagaKerja::where('province_code', $rtk->province_code)
                        ->where('type', TypeRtk::PROVINSI->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->where(function ($q) {
                            // Yang bukan berlaku penuh — salah satu kondisi tidak terpenuhi
                            $q->where('status_verification', '!=', RTKStatusVerification::APPROVED->value)
                                ->orWhere('status_document', '!=', StatusDocument::VALID->value);
                        })
                        ->update(['is_active' => false]);
                } else {
                    // Tidak ada RTK berlaku penuh — non-aktifkan semua is_active yang lain
                    RencanaTenagaKerja::where('province_code', $rtk->province_code)
                        ->where('type', TypeRtk::PROVINSI->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
            }

            if (isset($data['document_path'])) {
                if ($rtk->document_path) {
                    Storage::disk('public')->delete($rtk->document_path);
                }
                $data['document_path'] = $data['document_path']->store(
                    'rtk/documents/province',
                    'public'
                );
            }

            $rtk->update([
                'name'                => $data['name'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
                'is_active'           => $isActive,
                'document_path'       => $data['document_path'] ?? $rtk->document_path,
                'status_verification' => RTKStatusVerification::PENDING->value,
                'rejected_reason'     => null,
                'approved_by'         => null,
                'approved_at'         => null,
            ]);

            ToastMagic::success('RTK Provinsi berhasil diupdate.');

            return $rtk->fresh();
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
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ) {
        return RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('province_code', $provinceCode)
            ->where('regency_code', $regencyCode)
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($statusVerification, fn($q) => $q->where('status_verification', $statusVerification))
            ->when($statusDocument, fn($q) => $q->where('status_document', $statusDocument))
            ->when($isActive !== null && $isActive !== '', function ($q) use ($isActive) {
                $q->where('is_active', (bool) $isActive);
            })
            ->orderBy('created_at', $sortBy);
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
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ): LengthAwarePaginator {
        return $this->getFilteredQueryBuilderRTKDByKabKotaCode(
            provinceCode: $provinceCode,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        )->paginate($limit)->withQueryString();
    }

    public function exportRtkRegency(
        string $provinceCode,
        string $regencyCode,
        ?string $search = null,
        string $sortBy = self::DEFAULT_SORT,
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
    ) {
        return $this->getFilteredQueryBuilderRTKDByKabKotaCode(
            provinceCode: $provinceCode,
            regencyCode: $regencyCode,
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive,
        );
    }

    public function createRTKKabKota(array $data): RencanaTenagaKerja
    {
        $user = Auth::user();

        return DB::transaction(function () use ($data, $user) {
            $provinceCode = $user->scopeArea->province_code;
            $regencyCode = $user->scopeArea->regency_code;

            $isActive     = (bool) ($data['is_active'] ?? false);

            // Kalau user set is_active = true
            if ($isActive) {
                // Cek apakah ada RTK yang berlaku penuh
                $rtkBerlaku = RencanaTenagaKerja::where('regency_code', $regencyCode)
                    ->where('type', TypeRtk::KAB_KOTA->value)
                    ->berlaku() // APPROVED + VALID + is_active
                    ->exists();

                if (! $rtkBerlaku) {
                    // Tidak ada yang berlaku penuh — non-aktifkan yang lama
                    RencanaTenagaKerja::where('regency_code', $regencyCode)
                        ->where('type', TypeRtk::KAB_KOTA->value)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
                // Kalau ada yang berlaku penuh — biarkan, is_active tetap true untuk RTK baru
                // Penggantian RTK acuan dihandle admin pusat
            }

            $documentPath = null;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkd/documents/kab-kota',
                    'public'
                );
            }

            $rtk = RencanaTenagaKerja::create([
                'user_id'             => $user->id,
                'province_code'       => $provinceCode,
                'regency_code'        => $regencyCode,
                'name'                => $data['name'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
                'status_verification' => RTKStatusVerification::PENDING->value,
                'status_document'     => StatusDocument::NA->value,
                'type'                => TypeRtk::KAB_KOTA->value,
                'is_active'           => $isActive,
                'document_path'       => $documentPath,
            ]);

            ToastMagic::success('RTK Kab/Kota berhasil diajukan.');

            return $rtk;
        });
    }

    /**
     * Update RTK Kab/Kota
     * Disesuaikan dengan logic updateRTKProvince
     */
    public function updateRTKKabKota(RencanaTenagaKerja $rtk, array $data): RencanaTenagaKerja
    {
        return DB::transaction(function () use ($data, $rtk) {

            // Tidak boleh edit kalau RTK ini sendiri sudah berlaku penuh
            if ($rtk->is_berlaku) {
                throw new \Exception('RTK yang sedang berlaku tidak bisa diubah.');
            }

            // Boleh edit kalau:
            // 1. status_verification = PENDING
            // 2. status_verification = APPROVED + status_document = NA
            // 3. status_verification = REJECTED
            $bolehEdit =
                $rtk->status_verification === RTKStatusVerification::PENDING ||
                $rtk->status_verification === RTKStatusVerification::REJECTED ||
                (
                    $rtk->status_verification === RTKStatusVerification::APPROVED &&
                    $rtk->status_document === StatusDocument::NA
                );

            if (! $bolehEdit) {
                throw new \Exception('RTK ini tidak bisa diubah.');
            }

            $isActive = (bool) ($data['is_active'] ?? false);

            if ($isActive) {
                // Cek apakah ada RTK berlaku penuh di regency yang sama
                $adaRtkBerlaku = RencanaTenagaKerja::where('regency_code', $rtk->regency_code)
                    ->where('type', TypeRtk::KAB_KOTA->value)
                    ->where('id', '!=', $rtk->id)
                    ->berlaku()
                    ->exists();

                if ($adaRtkBerlaku) {
                    // Ada RTK berlaku penuh — tetap biarkan is_active-nya
                    // Non-aktifkan RTK lain yang is_active = true tapi BUKAN berlaku penuh
                    RencanaTenagaKerja::where('regency_code', $rtk->regency_code)
                        ->where('type', TypeRtk::KAB_KOTA->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->where('status_verification', '!=', RTKStatusVerification::APPROVED->value)
                                ->orWhere('status_document', '!=', StatusDocument::VALID->value);
                        })
                        ->update(['is_active' => false]);
                } else {
                    // Tidak ada RTK berlaku penuh — non-aktifkan semua is_active yang lain
                    RencanaTenagaKerja::where('regency_code', $rtk->regency_code)
                        ->where('type', TypeRtk::KAB_KOTA->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
            }

            if (isset($data['document_path'])) {
                if ($rtk->document_path) {
                    Storage::disk('public')->delete($rtk->document_path);
                }
                $data['document_path'] = $data['document_path']->store(
                    'rtk/documents/kab-kota', // ← path kab-kota
                    'public'
                );
            }

            $rtk->update([
                'name'                => $data['name'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
                'is_active'           => $isActive,
                'document_path'       => $data['document_path'] ?? $rtk->document_path,
                'status_verification' => RTKStatusVerification::PENDING->value, // ← reset ke PENDING
                'rejected_reason'     => null,
                'approved_by'         => null,
                'approved_at'         => null,
            ]);

            ToastMagic::success('RTK Kab/Kota berhasil diupdate.');

            return $rtk->fresh();
        });
    }

    /**
     * Get active RTK Kab/Kota for current user
     */
    public function rtkKabKotaActive(): ?RencanaTenagaKerja
    {
        $user = Auth::user();

        // Prioritaskan RTK berlaku penuh dulu
        $rtkBerlaku = RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('regency_code', $user->scopeArea?->regency_code)
            ->berlaku()
            ->first();

        if ($rtkBerlaku) return $rtkBerlaku;

        // Kalau tidak ada berlaku, ambil yang is_active = true
        return RencanaTenagaKerja::query()
            ->where('type', TypeRtk::KAB_KOTA->value)
            ->where('regency_code', $user->scopeArea?->regency_code)
            ->where('is_active', true)
            ->orderByDesc('updated_at')
            ->first();
    }
}
