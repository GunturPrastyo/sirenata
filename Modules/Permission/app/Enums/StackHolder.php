<?php

namespace Modules\Permission\Enums;

enum StackHolder: String
{
    case SUPER_ADMIN = 'Super Admin';
    case ADMIN_PUSAT = 'Admin Pusat';
    case PROVINCE = 'Province';
    case ADMIN_PROVINCE = 'Admin Province';
    case KOTA_KAB = 'Kota/Kab';
    case ADMIN_KOTA_KAB = 'Admin Kota/Kab';
    case PENANGGUNG_JAWAB_PENGHITUNGAN = 'Penanggung Jawab Penghitungan';
    case ANGGOTA_TIM = 'Anggota TIM';
    case USER = 'User';
}
