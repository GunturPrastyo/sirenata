<?php

namespace Modules\RTK\Services;

use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\StatusDocument;

class ApprovalRtkService
{
    /**
     * Step 1 — Approve status_verification saja
     * Bisa dilakukan selama RTK masih PENDING
     */
    public function approveVerificationProvince(RencanaTenagaKerja $rtk): array
    {
        if ($rtk->status_verification !== RTKStatusVerification::PENDING) {
            return [
                'success' => false,
                'message' => 'RTK harus dalam status PENDING untuk diverifikasi',
            ];
        }

        $rtk->update([
            'status_verification' => RTKStatusVerification::APPROVED->value,
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::success('Status verifikasi RTK berhasil disetujui');

        return ['success' => true, 'message' => 'Status verifikasi berhasil diapprove'];
    }


    /**
     * Step 2 — Approve status_document = VALID
     * Hanya bisa kalau status_verification sudah APPROVED
     * Jika ada RTK berlaku → set RTK lama jadi EXPIRED + is_active = false
     */
    public function approveDocumentProvince(RencanaTenagaKerja $rtk): array
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

            // Cek ada RTK berlaku di provinsi yang sama
            $rtkBerlaku = RencanaTenagaKerja::where('province_code', $rtk->province_code)
                ->where('type', TypeRtk::PROVINSI->value)
                ->where('id', '!=', $rtk->id)
                ->berlaku()
                ->first();

            if ($rtkBerlaku) {
                // RTK lama yang berlaku → set EXPIRED + is_active = false
                $rtkBerlaku->update([
                    'status_document' => StatusDocument::EXPIRED->value,
                    'is_active'       => false,
                ]);
            }

            // RTK baru → set VALID, is_active tetap true (sudah di-set admin provinsi)
            $rtk->update([
                'status_document' => StatusDocument::VALID->value,
            ]);

            ToastMagic::success('Dokumen RTK berhasil divalidasi. RTK sekarang berlaku.');

            return ['success' => true, 'message' => 'RTK sekarang berlaku'];
        });
    }


    /**
     * Step 1 — Approve status_verification RTK Kab/Kota
     */
    public function approveVerificationKabKota(RencanaTenagaKerja $rtk): array
    {
        if ($rtk->status_verification !== RTKStatusVerification::PENDING) {
            return [
                'success' => false,
                'message' => 'RTK harus dalam status PENDING untuk diverifikasi',
            ];
        }

        $rtk->update([
            'status_verification' => RTKStatusVerification::APPROVED->value,
            'approved_by'         => Auth::id(),
            'approved_at'         => now(),
        ]);

        ToastMagic::success('Status verifikasi RTK Kab/Kota berhasil disetujui');

        return ['success' => true, 'message' => 'Status verifikasi berhasil diapprove'];
    }

    /**
     * Step 2 — Approve status_document RTK Kab/Kota
     * Scope berlaku menggunakan regency_code
     */
    public function approveDocumentKabKota(RencanaTenagaKerja $rtk): array
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

            // Cek RTK berlaku di regency yang sama (bukan province)
            $rtkBerlaku = RencanaTenagaKerja::where('regency_code', $rtk->regency_code)
                ->where('type', TypeRtk::KAB_KOTA->value)
                ->where('id', '!=', $rtk->id)
                ->berlaku()
                ->first();

            if ($rtkBerlaku) {
                // RTK lama yang berlaku → set EXPIRED + is_active = false
                $rtkBerlaku->update([
                    'status_document' => StatusDocument::EXPIRED->value,
                    'is_active'       => false,
                ]);
            }

            $rtk->update([
                'status_document' => StatusDocument::VALID->value,
            ]);

            ToastMagic::success('Dokumen RTK Kab/Kota berhasil divalidasi. RTK sekarang berlaku.');

            return ['success' => true, 'message' => 'RTK Kab/Kota sekarang berlaku'];
        });
    }
}
