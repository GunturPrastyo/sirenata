<?php

namespace Modules\RTK\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

/**
 * Kejadian: Admin Pusat / Admin Provinsi memutuskan status sebuah RTKD
 * (diverifikasi, disetujui, atau ditolak).
 *
 * Event ini dilemparkan SETELAH transaksi database tersimpan, lalu
 * listener di modul Dashboard menerjemahkannya menjadi notifikasi
 * in-app untuk Admin Provinsi & Admin Kab/Kota.
 */
class RtkdStatusDecided implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly string $decision,      // verified | approved | rejected
        public readonly string $rtkId,
        public readonly string $rtkName,
        public readonly string $rtkType,       // Kab/Kota | Provinsi
        public readonly ?string $provinceCode = null,
        public readonly ?string $regencyCode = null,
        public readonly ?string $creatorId = null,
        public readonly ?string $reason = null,
    ) {}
}
