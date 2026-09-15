<?php

namespace Modules\LMS\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Modules\LMS\Models\Library;
use Modules\LMS\Models\LibraryCategory;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user pertama sebagai pembuat data (creator) jika ada
        $adminUser = User::first();
        $userId = $adminUser ? $adminUser->id : null;

        // 1. Buat Kategori Pustaka (Hanya kolom name)
        $catPeraturan = LibraryCategory::firstOrCreate(
            ['name' => 'Peraturan & Regulasi']
        );

        $catModul = LibraryCategory::firstOrCreate(
            ['name' => 'Modul Pembelajaran']
        );

        $catKajian = LibraryCategory::firstOrCreate(
            ['name' => 'Kajian & Analisis']
        );

        // 2. Data Dokumen Perpustakaan (Konteks RTK Makro, Mikro, dan IPK)
        $libraries = [
            [
                'library_category_id' => $catPeraturan->id,
                'title'               => 'Undang-Undang Cipta Kerja Klaster Ketenagakerjaan',
                'description'         => 'Dokumen resmi regulasi ketenagakerjaan nasional yang menjadi acuan dasar dalam penyusunan kebijakan makro dan mikro.',
                'external_link'       => 'https://www.dpr.go.id/dokjdih/document/uu/UU_2020_11.pdf',
            ],
            [
                'library_category_id' => $catPeraturan->id,
                'title'               => 'Peraturan Menteri Ketenagakerjaan tentang Penyusunan Rencana Tenaga Kerja',
                'description'         => 'Panduan yuridis dan teknis bagi instansi pusat maupun daerah dalam menyusun dokumen Rencana Tenaga Kerja (RTK) Makro dan Mikro.',
                'external_link'       => 'https://jdih.kemnaker.go.id/',
            ],
            [
                'library_category_id' => $catModul->id,
                'title'               => 'Modul Utama Penyusunan Rencana Tenaga Kerja Makro (RTK Makro)',
                'description'         => 'Modul komprehensif mengenai metodologi proyeksi angkatan kerja, penyerapan tenaga kerja sektoral, dan indikator demografi nasional.',
                'external_link'       => 'https://www.kemnaker.go.id/',
            ],
            [
                'library_category_id' => $catModul->id,
                'title'               => 'Panduan Teknis Analisis Kebutuhan Tenaga Kerja Mikro (RTK Mikro)',
                'description'         => 'Buku pegangan operasional untuk menganalisis formasi jabatan, produktivitas, serta perencanaan pegawai di level instansi dan perusahaan.',
                'external_link'       => 'https://www.kemnaker.go.id/',
            ],
            [
                'library_category_id' => $catKajian->id,
                'title'               => 'Buku Panduan Pengukuran Indeks Pembangunan Ketenagakerjaan (IPK)',
                'description'         => 'Kajian mendalam mengenai indikator komposit penilainya performa pembangunan ketenagakerjaan daerah dari 9 dimensi utama.',
                'external_link'       => 'https://www.bps.go.id/',
            ],
            [
                'library_category_id' => $catKajian->id,
                'title'               => 'Laporan Analisis Tren Pasar Kerja dan Produktivitas Nasional',
                'description'         => 'Hasil riset berkala terkait dinamika penawaran dan permintaan tenaga kerja (supply & demand) serta tantangan bonus demografi.',
                'external_link'       => 'https://bappenas.go.id/',
            ],
        ];

        foreach ($libraries as $item) {
            Library::updateOrCreate(
                ['title' => $item['title']],
                [
                    'library_category_id' => $item['library_category_id'],
                    'description'         => $item['description'],
                    'external_link'       => $item['external_link'],
                    'created_by'          => $userId, // Menyertakan ID creator
                ]
            );
        }
    }
}