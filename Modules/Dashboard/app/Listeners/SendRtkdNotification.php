<?php

namespace Modules\Dashboard\Listeners;

use Modules\Dashboard\Services\NotificationService;
use Modules\RTK\Events\RtkdStatusDecided;

/**
 * Mengubah kejadian "status RTKD diputuskan" menjadi notifikasi in-app.
 */
class SendRtkdNotification
{
    public function __construct(
        private NotificationService $notifications
    ) {}

    public function handle(RtkdStatusDecided $event): void
    {
        $this->notifications->rtkdStatus(
            event: $event->decision,
            rtkName: $event->rtkName,
            rtkType: $event->rtkType,
            provinceCode: $event->provinceCode,
            regencyCode: $event->regencyCode,
            creatorId: $event->creatorId,
            reason: $event->reason,
        );
    }
}
