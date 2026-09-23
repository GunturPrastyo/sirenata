<?php

return [
    'name' => 'Dashboard',

    'notifications' => [
        // Jarak (detik) navbar mengecek notifikasi baru secara ringan
        // (event sudah ditulis ke database saat kejadian terjadi;
        //  polling ini hanya "tarik" tampilan agar tetap segar).
        'poll_interval' => env('NOTIFICATION_POLL_INTERVAL', 30),

        // Jumlah notifikasi yang dikirim ke dropdown navbar.
        'per_page' => 15,
    ],
];
