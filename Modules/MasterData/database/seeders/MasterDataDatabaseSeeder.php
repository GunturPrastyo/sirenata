<?php

namespace Modules\MasterData\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\MasterData\Models\Institution;

class MasterDataDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ministries = [
            'Kementerian Koordinator Bidang Politik dan Keamanan',
            'Kementerian Koordinator Bidang Hukum, Hak Asasi Manusia, Imigrasi, dan Pemasyarakatan',
            'Kementerian Koordinator Bidang Perekonomian',
            'Kementerian Koordinator Bidang Pembangunan Manusia dan Kebudayaan',
            'Kementerian Koordinator Bidang Infrastruktur dan Pembangunan Kewilayahan',
            'Kementerian Koordinator Bidang Pemberdayaan Masyarakat',
            'Kementerian Koordinator Bidang Pangan',
            'Kementerian Sekretariat Negara',
            'Kementerian Dalam Negeri',
            'Kementerian Luar Negeri',
            'Kementerian Pertahanan',
            'Kementerian Agama',
            'Kementerian Hukum',
            'Kementerian Hak Asasi Manusia',
            'Kementerian Imigrasi dan Pemasyarakatan',
            'Kementerian Keuangan',
            'Kementerian Pendidikan Dasar dan Menengah',
            'Kementerian Pendidikan Tinggi, Sains, dan Teknologi',
            'Kementerian Kebudayaan',
            'Kementerian Kesehatan',
            'Kementerian Sosial',
            'Kementerian Ketenagakerjaan',
            'Kementerian Pelindungan Pekerja Migran Indonesia/Badan Pelindungan Pekerja Migran Indonesia',
            'Kementerian Perindustrian',
            'Kementerian Perdagangan',
            'Kementerian Energi dan Sumber Daya Mineral',
            'Kementerian Pekerjaan Umum',
            'Kementerian Perumahan dan Kawasan Permukiman',
            'Kementerian Desa dan Pembangunan Daerah Tertinggal',
            'Kementerian Transmigrasi',
            'Kementerian Perhubungan',
            'Kementerian Komunikasi dan Digital',
            'Kementerian Pertanian',
            'Kementerian Kehutanan',
            'Kementerian Kelautan dan Perikanan',
            'Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional',
            'Kementerian Perencanaan Pembangunan Nasional/Badan Perencanaan Pembangunan Nasional',
            'Kementerian Pendayagunaan Aparatur Negara dan Reformasi Birokrasi',
            'Kementerian Badan Usaha Milik Negara',
            'Kementerian Kependudukan dan Pembangunan Keluarga/Badan Kependudukan dan Keluarga Berencana Nasional',
            'Kementerian Lingkungan Hidup/Badan Pengendalian Lingkungan Hidup',
            'Kementerian Investasi dan Hilirisasi/Badan Koordinasi Penanaman Modal',
            'Kementerian Koperasi',
            'Kementerian Usaha Mikro, Kecil, dan Menengah',
            'Kementerian Pariwisata',
            'Kementerian Ekonomi Kreatif/Badan Ekonomi Kreatif',
            'Kementerian Pemberdayaan Perempuan dan Perlindungan Anak',
            'Kementerian Pemuda dan Olahraga'
        ];

        foreach ($ministries as $ministry) {
            Institution::create([
                'name' => $ministry,
                'type' => 'pusat',
                'is_active' => true,
            ]);
        }

        $dummyDaerah = [
            'Dinas Tenaga Kerja Provinsi Jawa Timur',
            'Dinas Komunikasi dan Informatika Provinsi Jawa Tengah',
            'Dinas Pendidikan dan Kebudayaan Provinsi Jawa Barat',
            'Dinas Kesehatan Provinsi DKI Jakarta',
            'Dinas Sosial Provinsi Banten',
            'Dinas Perhubungan Provinsi Daerah Istimewa Yogyakarta',
            'Dinas Lingkungan Hidup Provinsi Bali',
            'Dinas Koperasi dan UMKM Provinsi Sumatera Utara',
            'Dinas Pariwisata Provinsi Sulawesi Selatan',
            'Dinas Pekerjaan Umum dan Penataan Ruang Provinsi Kalimantan Timur'
        ];

        foreach ($dummyDaerah as $daerah) {
            Institution::create([
                'name' => $daerah,
                'type' => 'daerah',
                'is_active' => true,
            ]);
        }
    }
}
