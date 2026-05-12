<?php

namespace Modules\RTK\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\RTK\Enums\RTKStatus;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\StatusDocument;

class RTKNService
{
    public function getFilteredQueryBuilderRTKN(
        ?string $search = null,
        string $sortBy = 'desc',
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null
    ) {
        return RencanaTenagaKerja::query()
            ->with(['user'])
            ->where('type', TypeRtk::NASIONAL->value)
            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->when($statusVerification, fn($q) => $q->where('status_verification', $statusVerification))
            ->when($statusDocument, fn($q) => $q->where('status_document', $statusDocument))
            ->when($isActive !== null && $isActive !== '', function ($q) use ($isActive) {
                $q->where('is_active', (bool) $isActive);
            })
            ->orderBy('created_at', $sortBy);
    }

    public function paginateFilteredRTKN(
        ?string $search = null,
        string $sortBy = 'desc',
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null,
        int $limit = 15
    ) {
        return $this->getFilteredQueryBuilderRTKN(
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive
        )->paginate($limit)->withQueryString();
    }

    public function exportUserRTKN(
        ?string $search = null,
        string $sortBy = 'desc',
        ?string $statusVerification = null,
        ?string $statusDocument = null,
        ?string $isActive = null
    ) {
        return $this->getFilteredQueryBuilderRTKN(
            search: $search,
            sortBy: $sortBy,
            statusVerification: $statusVerification,
            statusDocument: $statusDocument,
            isActive: $isActive
        );
    }


    /**
     * Create RTKN baru
     * Karena admin pusat yang handle sendiri, is_active bisa langsung di-set
     * tapi tetap tidak boleh non-aktifkan RTK yang sedang berlaku penuh
     */
    public function createRTKN(array $data): RencanaTenagaKerja
    {
        $user = Auth::user();

        return DB::transaction(function () use ($data, $user) {
            $isActive = (bool) ($data['is_active'] ?? false);

            if ($isActive) {
                // Cek apakah ada RTKN yang berlaku penuh
                $adaRtknBerlaku = RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                    ->berlaku()
                    ->exists();

                if (! $adaRtknBerlaku) {
                    // Tidak ada yang berlaku penuh — non-aktifkan yang lama
                    RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
                // Kalau ada yang berlaku penuh — biarkan, penggantian dihandle via approve
            }

            $documentPath = null;
            if (isset($data['document_path'])) {
                $documentPath = $data['document_path']->store(
                    'rtkn/documents',
                    'public'
                );
            }

            $rtkn = RencanaTenagaKerja::create([
                'user_id'             => $user->id,
                'province_code'       => null,
                'regency_code'        => null,
                'name'                => $data['name'],
                'start_date'          => $data['start_date'],
                'end_date'            => $data['end_date'],
                'status_verification' => RTKStatusVerification::PENDING->value,
                'status_document'     => StatusDocument::NA->value,
                'type'                => TypeRtk::NASIONAL->value,
                'is_active'           => $isActive,
                'document_path'       => $documentPath,
            ]);

            ToastMagic::success('RTKN berhasil ditambahkan!');

            return $rtkn;
        });
    }


    /**
     * Update RTKN
     * Tidak boleh edit kalau sudah berlaku penuh
     */
    public function updateRTKN(RencanaTenagaKerja $rtk, array $data): RencanaTenagaKerja
    {
        return DB::transaction(function () use ($data, $rtk) {

            if ($rtk->is_berlaku) {
                throw new \Exception('RTKN yang sedang berlaku tidak bisa diubah.');
            }

            $bolehEdit =
                $rtk->status_verification === RTKStatusVerification::PENDING ||
                $rtk->status_verification === RTKStatusVerification::REJECTED ||
                (
                    $rtk->status_verification === RTKStatusVerification::APPROVED &&
                    $rtk->status_document === StatusDocument::NA
                );

            if (! $bolehEdit) {
                throw new \Exception('RTKN ini tidak bisa diubah.');
            }

            $isActive = (bool) ($data['is_active'] ?? false);

            if ($isActive) {
                $adaRtknBerlaku = RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                    ->where('id', '!=', $rtk->id)
                    ->berlaku()
                    ->exists();

                if ($adaRtknBerlaku) {
                    // Non-aktifkan yang is_active = true tapi bukan berlaku penuh
                    RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->where(function ($q) {
                            $q->where('status_verification', '!=', RTKStatusVerification::APPROVED->value)
                                ->orWhere('status_document', '!=', StatusDocument::VALID->value);
                        })
                        ->update(['is_active' => false]);
                } else {
                    RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                        ->where('id', '!=', $rtk->id)
                        ->where('is_active', true)
                        ->update(['is_active' => false]);
                }
            }

            if (isset($data['document_path'])) {
                if ($rtk->document_path) {
                    Storage::disk('public')->delete($rtk->document_path);
                }
                $data['document_path'] = $data['document_path']->store('rtkn/documents', 'public');
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

            ToastMagic::success('RTKN berhasil diupdate.');

            return $rtk->fresh();
        });
    }

    public function deleteRTKN(RencanaTenagaKerja $rencanaTenagaKerjaNasional)
    {
        if ($rencanaTenagaKerjaNasional->document_path) {
            Storage::disk('public')->delete($rencanaTenagaKerjaNasional->document_path);
        }
        $rencanaTenagaKerjaNasional->delete();

        ToastMagic::success("RTKN berhasil dihapus!");
        return $rencanaTenagaKerjaNasional;
    }


    /**
     * Step 1 — Approve status_verification RTKN
     */
    public function approveVerification(RencanaTenagaKerja $rtk): array
    {
        if ($rtk->status_verification !== RTKStatusVerification::PENDING) {
            return [
                'success' => false,
                'message' => 'RTKN harus dalam status PENDING untuk diverifikasi',
            ];
        }

        $rtk->update([
            'status_verification' => RTKStatusVerification::APPROVED->value,
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::success('Status verifikasi RTKN berhasil disetujui.');

        return ['success' => true, 'message' => 'Status verifikasi berhasil diapprove'];
    }

    /**
     * Step 2 — Approve status_document RTKN
     * Jika ada RTKN berlaku → set yang lama jadi EXPIRED + is_active = false
     */
    public function approveDocument(RencanaTenagaKerja $rtk): array
    {
        if ($rtk->status_verification !== RTKStatusVerification::APPROVED) {
            return [
                'success' => false,
                'message' => 'Status verifikasi harus APPROVED sebelum approve dokumen',
            ];
        }

        if ($rtk->status_document === StatusDocument::VALID) {
            return [
                'success' => false,
                'message' => 'Dokumen sudah berstatus VALID',
            ];
        }

        return DB::transaction(function () use ($rtk) {

            // Cek RTKN berlaku penuh
            $rtknBerlaku = RencanaTenagaKerja::where('type', TypeRtk::NASIONAL->value)
                ->where('id', '!=', $rtk->id)
                ->berlaku()
                ->first();

            if ($rtknBerlaku) {
                // Set yang lama jadi EXPIRED
                $rtknBerlaku->update([
                    'status_document' => StatusDocument::EXPIRED->value,
                    'is_active'       => false,
                ]);
            }

            $rtk->update([
                'status_document' => StatusDocument::VALID->value,
            ]);

            ToastMagic::success('Dokumen RTKN berhasil divalidasi. RTKN sekarang berlaku.');

            return ['success' => true, 'message' => 'RTKN sekarang berlaku'];
        });
    }

    /**
     * Reject RTKN
     * is_active tetap true agar admin bisa edit dan resubmit
     */
    public function rejectRTKN(RencanaTenagaKerja $rtk, string $reason): array
    {
        if ($rtk->is_berlaku) {
            return [
                'success' => false,
                'message' => 'RTKN yang sedang berlaku tidak bisa ditolak.',
            ];
        }

        $bolehReject = $rtk->is_active
            && $rtk->status_document === StatusDocument::NA
            && in_array($rtk->status_verification, [
                RTKStatusVerification::PENDING,
                RTKStatusVerification::APPROVED,
            ]);

        if (! $bolehReject) {
            return [
                'success' => false,
                'message' => 'RTKN ini tidak bisa ditolak.',
            ];
        }

        $rtk->update([
            'status_verification' => RTKStatusVerification::REJECTED->value,
            'status_document'     => StatusDocument::NA->value,
            // is_active tetap true agar bisa edit
            'rejected_reason'     => $reason,
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::error('RTKN berhasil ditolak.');

        return ['success' => true, 'message' => 'RTKN berhasil ditolak'];
    }
}
