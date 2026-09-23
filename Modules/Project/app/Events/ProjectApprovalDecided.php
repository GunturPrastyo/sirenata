<?php

namespace Modules\Project\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

/**
 * Kejadian: Admin Pusat mengubah status proyek daerah
 * (mis. Draft → On Progress = proyek disetujui).
 *
 * Setelah transaksi tersimpan, listener di modul Dashboard
 * membuat notifikasi untuk Admin Provinsi & Admin Kab/Kota.
 */
class ProjectApprovalDecided implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly int|string $projectId,
        public readonly string $projectName,
        public readonly string $projectType,    // Provinsi | Kab/Kota | Nasional
        public readonly ?string $creatorId = null,
        public readonly ?string $previousStatus = null,
        public readonly string $newStatus = 'On Progress',
        /** Pengguna yang melakukan aksi — tidak menerima notifikasi sendiri. */
        public readonly ?string $actorId = null,
    ) {}
}
