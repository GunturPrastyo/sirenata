<?php

namespace Modules\Dashboard\Listeners;

use Modules\Dashboard\Services\NotificationService;
use Modules\Project\Events\ProjectApprovalDecided;
use Modules\Project\Enums\ProjectType;

/**
 * Mengubah kejadian "keputusan proyek daerah" menjadi notifikasi in-app.
 */
class SendProjectNotification
{
    public function __construct(
        private NotificationService $notifications
    ) {}

    public function handle(ProjectApprovalDecided $event): void
    {
        // Hanya proyek daerah (Kab/Kota & Provinsi) yang memberi
        // notifikasi ke navbar Admin Kab/Kota & Admin Provinsi.
        $isDaerahProject = in_array($event->projectType, [
            ProjectType::PROVINSI->value,
            ProjectType::KAB_KOTA->value,
        ], true);

        if (! $isDaerahProject) {
            return;
        }

        $approved = $event->newStatus === 'On Progress'
            && in_array($event->previousStatus, ['Draft', null], true);

        if (! $approved && $event->newStatus !== 'Completed') {
            return;
        }

        $this->notifications->projectApproved(
            projectId: $event->projectId,
            projectName: $event->projectName,
            projectType: $event->projectType,
            creatorId: $event->creatorId,
            previousStatus: (string) $event->previousStatus,
            newStatus: $event->newStatus,
        );
    }
}
