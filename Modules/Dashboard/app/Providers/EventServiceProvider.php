<?php

namespace Modules\Dashboard\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Peta kejadian (event) -> notifikasi in-app.
     *
     * Modul RTK & Project melemparkan event ketika Admin Pusat
     * memverifikasi / menyetujui data; modul Dashboard yang
     * menerjemahkannya menjadi notifikasi navbar.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        \Modules\RTK\Events\RtkdStatusDecided::class => [
            \Modules\Dashboard\Listeners\SendRtkdNotification::class,
        ],
        \Modules\Project\Events\ProjectApprovalDecided::class => [
            \Modules\Dashboard\Listeners\SendProjectNotification::class,
        ],
    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = false;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void {}
}
