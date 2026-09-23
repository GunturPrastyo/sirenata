<?php

namespace Modules\RTK\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\RTK\Enums\StatusDocument;
use Modules\RTK\Enums\TypeRtk;
use Modules\RTK\Enums\RTKStatusVerification;
use Modules\RTK\Events\RtkdStatusDecided;
use Modules\RTK\Models\RencanaTenagaKerja;
use Devrabiul\ToastMagic\Facades\ToastMagic;

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

        $this->dispatchDecision($rtk, 'verified');

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

            $this->dispatchDecision($rtk, 'approved');

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

        $this->dispatchDecision($rtk, 'verified');

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

            $this->dispatchDecision($rtk, 'approved');

            return ['success' => true, 'message' => 'RTK Kab/Kota sekarang berlaku'];
        });
    }

    /**
     * Lemparkan kejadian "RTKD ditolak" — listener akan
     * mengubahnya menjadi notifikasi in-app.
     */
    public function notifyRejected(RencanaTenagaKerja $rtk, string $reason): void
    {
        $this->dispatchDecision($rtk, 'rejected', $reason);
    }

    /**
     * Lemparkan event yang mewakili keputusan terhadap sebuah RTKD.
     *
     * Kejadian: verified (diverifikasi) | approved (disetujui) | rejected (ditolak)
     */
    private function dispatchDecision(RencanaTenagaKerja $rtk, string $decision, ?string $reason = null): void
    {
        $type = $rtk->type instanceof TypeRtk
            ? $rtk->type
            : TypeRtk::tryFrom((string) $rtk->type);

        // Hanya RTKD daerah (Kab/Kota & Provinsi) yang masuk lingkup notifikasi navbar.
        if (! in_array($type, [TypeRtk::KAB_KOTA, TypeRtk::PROVINSI], true)) {
            return;
        }

        event(new RtkdStatusDecided(
            decision: $decision,
            rtkId: $rtk->id,
            rtkName: $rtk->name,
            rtkType: $type->value,
            provinceCode: $rtk->province_code,
            regencyCode: $rtk->regency_code,
            creatorId: $rtk->user_id,
            reason: $reason,
            // Pelaku aksi (mis. admin provinsi yang memverifikasi/menyetujui
            // RTKD kab/kota sendiri) tidak usah menerima notifikasi.
            actorId: Auth::id(),
        ));
    }
}
