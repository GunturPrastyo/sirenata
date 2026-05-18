<?php

namespace Modules\Permission\Enums;

enum StackHolder: String
{
    case SUPER_ADMIN = 'super-admin';
    case ADMIN_PUSAT = 'admin-pusat';
    case ADMIN_PROVINCE = 'admin-province';
    case ADMIN_KOTA_KAB = 'admin-kota-kab';
    case ADMIN_PENANGGUNG_JAWAB_PENGHITUNGAN = 'penanggung-jawab-penghitungan';
    case ADMIN_ANGGOTA_TIM = 'anggota-tim';
    case USER = 'user';
}
